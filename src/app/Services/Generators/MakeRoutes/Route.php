<?php

namespace AlexClaimer\Generator\App\Services\Generators\MakeRoutes;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use AlexClaimer\Generator\App\Services\Generators\Helper;

class Route
{

    /**
     * @param $tName
     * @return string
     */
    public static function make_routes_url($tName, $type = '')
    {
        $postfix = str_replace('\\', '/', Helper::getPostfix());
        $tName = substr($tName, strpos($tName, '_') + 1);
        if ($type !== '') {
            $type = '/' . $type;
        }
        return strtolower($postfix) . "/" . $tName . $type;
    }

    /**
     * @param $tName
     * @return string
     */
    public static function make_routes_name($tName, $type = '')
    {
        $postfix = str_replace('\\', '_', Helper::getPostfix());
        $tName = substr($tName, strpos($tName, '_') + 1);
        if ($type !== '') {
            $type = '.' . $type;
        }
        return strtolower($postfix) . "_" . $tName . $type;
    }

    /**
     * @param $tName
     * @return string
     */
    public static function make_routes_prefix()
    {
        $postfix = str_replace('\\', '.', Helper::getPostfix());

        return strtolower($postfix);
    }

}
