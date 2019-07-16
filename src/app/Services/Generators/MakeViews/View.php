<?php

namespace AlexClaimer\Generator\App\Services\Generators\MakeViews;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use AlexClaimer\Generator\App\Services\Generators\Helper;

class View
{

    public static function getFullPathNameASSETsResource($type = 'scss')
    {
        return "resources/assets/" . self::getFullPathNameASSETsInBlade($type);
    }

    public static function getFullPathNameASSETsPublic($type = 'css')
    {
        return "public/" . self::getFullPathNameASSETsInBlade($type);
    }

    public static function getFullPathNameASSETsInBlade($type = 'css')
    {

        self::getPostfixPrefix($postfix, $prefix);

        if ($prefix !== "") {
            $fileName = $prefix;
            $prefix .= "/";
        } else {
            $fileName = 'app';
        }
        if ($type == 'variables'){
            $fileName = '_variables';
            $type = 'scss';
        }
        if ($type == 'bootstrap'){
            $fileName = 'bootstrap';
            $type = 'js';
        }

        return $prefix . $type . "/" . $fileName . "." . $type;
    }

    public static function getPostfixPrefix(&$postfix, &$prefix)
    {
        $postfix = lcfirst(config('alex-claimer-generator.config.namespace_postfix'));

        if ($pos = strpos($postfix, '\\'))
            $prefix = substr($postfix, 0, $pos);
        else $prefix = $postfix;
    }

    /**
     * @param $tName
     * @param $bladeName
     * @return string
     */
    public static function makeNameSpace($tName, $bladeName)
    {
        $postfix = config('alex-claimer-generator.config.namespace_postfix');
        if (trim($postfix) !== '') $postfix .= '\\';

        $str = '\\' . $postfix;

        $str = strtolower($str);

        $str .= $tName . '.' . $bladeName;

        return $str;
    }

    /**
     * @param $type
     * @return bool|string
     */
    public static function makeFullNameSpace($tName)
    {
        return config('alex-claimer-generator.config.view.namespace') . '\\' . self::makeNameSpaceForView($tName);
    }

    /**
     * @param $type
     * @return bool|string
     */
    public static function makeNameSpaceForView($tName, $bladeName)
    {
        $postfix = config('alex-claimer-generator.config.namespace_postfix');
        if (trim($postfix) !== '') $postfix .= '\\';

        $str = '\\' . $postfix;

        $str = strtolower($str);

        $str .= $tName . '.' . $bladeName;

        return $str;
    }

    /**
     * @param $tName
     * @return string
     */
    public static function make_directory($tName, $type = '')
    {
        $postfix = str_replace('\\', '.', Helper::getPostfix());
        $tName = substr($tName, strpos($tName, '.'));
        if ($type !== '') {
            $type = '.' . $type;
        }
        return strtolower($postfix) . "." . $tName . $type;
    }

    public static function getColumnName($arrColumns)
    {
        if (Arr::exists($arrColumns, 'name')) {
            $name = 'name';
        } elseif (Arr::exists($arrColumns, 'title')) {
            $name = 'title';
        } elseif (Arr::exists($arrColumns, 'slug')) {
            $name = 'slug';
        } elseif (Arr::exists($arrColumns, 'comment')) {
            $name = 'comment';
        } elseif (Arr::exists($arrColumns, 'id')) {
            $name = 'id';
        } else {
            $name = null;
        }
        return $name;
    }

    public static function getIgnoredColumns($typeBlade = 'edit')
    {
        $ignored_columns = config('alex-claimer-generator.config.ignored_columns_in_edit_create_views');
        if ($typeBlade == 'index') {
            $ignored_columns[] = 'password';
        }
        return $ignored_columns;
    }
}
