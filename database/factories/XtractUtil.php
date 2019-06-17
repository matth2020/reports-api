<?php

namespace database\factories;

class XtractUtil
{
    // sanitize string so it only contains xtract supported chars
    public static function standard($string)
    {
        $validChars = "a-zA-Z0-9-\./\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_:%\"";
        $pattern = "/[^".preg_quote($validChars, "/")."]/";
        return preg_replace($pattern, "", $string);
    }
}
