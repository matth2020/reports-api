<?php

/*
|--------------------------------------------------------------------------
| Swagger Routes
|--------------------------------------------------------------------------
|
| Routes relating to swagger
|
*/

use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;

$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->get('/', function () use ($router) {
        return redirect('v1/documentation');
    });

    $router->get('documentation', function () use ($router) {
        $config = app()->make('config');
        $basePath = $config->get('app.base_path') === '/' ? '' : $config->get('app.base_path');
        return view(
            'swagger-ui.index',
            [
                //url to docs is full url not relative path like urltoassets
                'urlToDocs' => 'https://'.$_SERVER['HTTP_HOST'] . $basePath . '/v1/docs',
                'urlToAssets' => $basePath . '/public/vendor/swagger-ui',
                'swaggerId' => $config->get('app.swagger_id'),
                'swaggerSecret' => $config->get('app.swagger_secret')
            ]
        );
    });

    $router->get('docs', function () use ($router) {
        $host = $_SERVER['HTTP_HOST'];

        $config = app()->make('config');
        //Where to write the resulting json swagger spec
        $docDir = storage_path('api-docs');
        //Filename for the resulting json swagger spec
        $filename = $docDir.'/api-docs.json';
        //Directory to scan for files with swagger annotations
        $appDir = base_path('app');
        //Subdirectories to exclude from the scan
        $excludes = array('Auth', 'Console', 'Events', 'Exceptions', 'Jobs', 'Listeners', 'Providers', 'Http/Middleware', 'swagger/swagger-v2-template.php');

        //Read swagger spec bookmark template.
        $swaggerTemplate = base_path('app/swagger/swagger-v2-template.php');
        //Swagger config file
        $swaggerConfig = base_path('app/swagger/swagger-v2.php');

        $file_contents = file_get_contents($swaggerTemplate);

        //Replace the bookmark in the swagger config file with the real host
        $file_contents = str_replace("\$\$Host", $host, $file_contents);
        //Get base path
        $base_path = $config->get('app.base_path') === '/' ? '' : $config->get('app.base_path');
        //Replace bookmark in swagger config with real base path
        $file_contents = str_replace("\$\$BasePath", $base_path . '/v1', $file_contents);
        //Replace bookmark in swagger config with real API verson (only major digit)
        $file_contents = str_replace("\$\$Version", substr('v1', 1), $file_contents);
        //Replace bookmark in swagger config with real auth base path.
        $auth_path = $host . $base_path;
        $file_contents = str_replace("\$\$AuthPath", $auth_path, $file_contents);
        //Write template with bookmarks replaced to actual swagger file.
        file_put_contents($swaggerConfig, $file_contents);

        if ($config->get('app.debug')) {
            // only regenerate swagger json file if .env APP_DEBUG=true
            if (! File::exists($docDir) || is_writable($docDir)) {
                // delete all existing documentation
                if (File::exists($docDir)) {
                    File::deleteDirectory($docDir);
                }
                //self::defineConstants(config('swagger-lume.constants') ?: []);
                File::makeDirectory($docDir);
                $swagger = \Swagger\scan($appDir, array('exclude' => $excludes));
                $swagger->saveAs($filename);
            }
        }

        if (File::extension($filename) === '') {
            $filename .= '.json';
        }
        if (! File::exists($filename)) {
            App::abort(404, "Cannot find {$filename}");
        }
        $content = File::get($filename);
        return new Response($content, 200, [
            'Content-Type' => 'application/json',
        ]);
    });
});
