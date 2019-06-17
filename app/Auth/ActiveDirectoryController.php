<?php

namespace App\Auth;

use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GuzzleHttp\Client;
use App\Models\User;

class ActiveDirectoryController
{
    private function LDAPAuthenticate ($username, $password, $adServer, $adDomain, $appname, $useSSL = false){

        $definedGroups = [-1 => 'none', 'limitedUser', 'User', 'Admin', 'sysAdmin'];

        $response = (object) [];

        $response->status = true;

        $parts = explode ('\\', str_replace('/', '\\', $username));
        switch (count($parts)){
            case 1: {
                // use domain from config and the username as it is
                if (isset($adDomain) && $adDomain != ''){
                    $domain = $adDomain;
                }
                else{
                    $response->status  = false;
                    $response->message = "Domain not specified.";
                }
                break;
            }
            case 2: {
                $domain = $parts[0];
                $username = $parts[1];
                break;
            }
            default: {
                $response->status  = false;
                $response->message = "Badly formed domain\user.";
            }
        }

        if ($response->status && !function_exists ('ldap_connect')){
            $response->status  = false;
            $response->message = "LDAP functions not present. (uncomment 'extension=php_ldap.dll' in php.ini).";
        }

        if ($response->status && strlen ($password) === 0) {
            $response->status  = false;
            $response->message = "Incorrect username/password combination.";
        }

        if ($response->status && !function_exists ('ldap_start_tls')){
            $response->status  = false;
            $response->message = "LDAP function ldap_start_tls not present.";
        }

        if ($response->status){
            $protocol = $useSSL ? 'ldaps://' : 'ldap://';

            $ldap = ldap_connect($protocol . $adServer);

            $parts = explode ('.', $domain);
            $dc_format_domain = 'dc=' . implode(',dc=', $parts);

            ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

            if ($useSSL) {
                $old_error_handler = set_error_handler(null);

                $tls_started = @ldap_start_tls($ldap);

                set_error_handler($old_error_handler);

                if ($useSSL && !$tls_started) {
                    $response->status  = false;
                    $response->message = "Could not start secure LDAP TLS connection.";
                }
            }

            if ($response->status){

                // avoid global error handler

                $old_error_handler = set_error_handler(null);

                // bind using the user name & domain

                $bind = @ldap_bind($ldap, "$username@$adDomain", $password);

                set_error_handler($old_error_handler);

                // get account information

                if ($bind) {
                    $filter="(|(sAMAccountName=$username)(userPrincipalName=$username@$adDomain))";

                    // do the search

                    $result = ldap_search($ldap, $dc_format_domain, $filter);
                    $info = ldap_get_entries($ldap, $result);
                    @ldap_close($ldap);

                    // get user info

                    if($info['count'] > 0){
                        $info0 = $info[0];
                        $response->status  = false;

                        if (!isset($info0["givenname"])) {
                            $response->message = "This user is missing the first name field in their Active Directory configuration.";
                        } else if (!isset($info0["sn"])) {
                            $response->message = "This user is missing the surname field in their Active Directory configuration.";
                        } else if (!isset($info0["samaccountname"])) {
                            $response->message = "This user is missing the SAM account name field in their Active Directory configuration.";
                        } else if (!isset($info0["distinguishedname"])) {
                            $response->message = "This user is missing the distinguished name field in their Active Directory configuration.";
                        } else if (!isset($info0["displayname"])) {
                            $response->message = "This user is missing the display name field in their Active Directory configuration.";
                        } else if (!isset($info0["memberof"])) {
                            $response->message = "This user is missing membership information in their Active Directory configuration.";
                        } else {
                            $response->status = true;
                            $response->firstname = $info0["givenname"][0];
                            $response->lastname = $info0["sn"][0];
                            $response->title = isset($info0["title"]) ? $info0["title"][0] : '';
                            $response->email = isset($info0["mail"]) ? $info0["mail"][0] : '';
                            $response->accountname = $info0["samaccountname"][0];
                            $response->distinguishedname = $info0["distinguishedname"][0];
                            $response->displayname = $info0["displayname"][0];

                            // find highest privilege within app

                            $maxPrivilege = -1;

                            for ($j = 0; $j < $info0["memberof"]["count"]; $j++) {
                                $group = $info0["memberof"][$j];
                                if (preg_match('/^CN=APP-Xtract-' . $appname . '-([^,]+),.*/', $group, $matches)) {
                                    $maxPrivilege = max($maxPrivilege, array_search($matches[1], $definedGroups));
                                }
                            }
                            $response->group = $definedGroups [$maxPrivilege];
                        }
                    }
                    else{
                        $response->status  = false;
                        $response->message = "No information for user $username found in $adDomain";
                    }
                } else {
                    $response->status  = false;
                    $err = ldap_errno ($ldap);
                    if ($err == 49){
                        // invalid credentials
                        $response->message = "Incorrect username/password combination.";
                    } else {
                        // some connection error
                        $response->message = "Failed to connect to LDAP server (err = {$err}).";
                    }
                }
            }
        }

        return $response;
    }

    public function validateUser($username, $password)
    {
        $config = app()->make('config');
        $domain = $config->get('auth.adDomain');
        $server = $config->get('auth.adServer');
        $useSSL = $config->get('auth.adUseSSL');

        $response = $this->LDAPAuthenticate ($username, $password, $server, $domain, 'XPS', $useSSL);

        if (!$response->status){
            \Log::info($response->message);
            return false;
        }

        try {
            $user = User::where('deleted', 'F')->where('username', $username)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            // the user didn't exist but if AD validated them then the user
            // should be created
            $user = new User;
        }
        // now we have a user object either way weather its pre-existing
        // or a new (empty) user so we should add all of the details about
        // the user that AD identified.
        $user->firstname = $response->firstname;        //required
        $user->lastname = $response->lastname;          //required
        $user->displayname = $response->displayname;
        $user->title = $response->title;                //optional I think
        $user->privilege = $response->group;            //required
        $user->email = $response->email;                //optional I think

        //I know you wont have account_id but I think it might be required
        //based on some of our newer stuff. Might be something we have to find
        //a way to tell them to add to their AD groups somehow. Or maybe the
        //app will just have to let users login if its null but just display
        //a message that an admin must configure their account?
        $user->account_id = null;
        $user->deleted='F';
        $user->password = crypt($password, '$2a$10$Okp.dWAMf9fWjTGlW77MxOYDbbK81wA8YPSHjTTiohAFSiCAiJVF2');
        $user->save();

        return true;
    }
}
