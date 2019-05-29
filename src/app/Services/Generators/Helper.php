<?php

namespace AlexClaimer\Generator\App\Services\Generator;

use Illuminate\Support\Str;

class Helper
{
    /**
     * @param $type
     * @return bool|string
     */
    public static function makeNameSpace($type)
    {
        $str = config('alex-claimer-generator.config.' . $type . '.namespace') .
            config('alex-claimer-generator.config.namespace_postfix');

        $str = mb_substr($str, 1, strlen($str) - 2);
        $str = ucfirst($str);

        return $str;
    }

    /**
     * @param $ClassName
     * @return string
     */
    public static function makeFileDirName($type, $ClassName)
    {
        $dir_name = base_path() .
            config('alex-claimer-generator.config.' . $type . '.namespace') .
            config('alex-claimer-generator.config.namespace_postfix');

        if (!is_dir($dir_name)) {
            mkdir($dir_name);
        }
        //dd(__METHOD__,$dir_name, $ClassName,$dir_name . $ClassName . '.php');//11
        return $dir_name . $ClassName . '.php';
    }

    /**
     * @param $key
     * @return bool|string
     */
    public static function makeFuncBelongsTo($key)
    {
        return substr($key, 0, strpos($key, '_id'));
    }
    /**
     * @param $tab_name
     * @return string
     */
    public static function className($tab_name)
    {
        $ClassName = Str::singular(ucfirst(Str::camel($tab_name)));

        return $ClassName;
    }
    /**
     * @param $arrFirst
     * @param $arrSecond
     * @return array
     */
    public static function addArr($arrFirst, $arrSecond)
    {
        $arr = $arrFirst;
        foreach ($arrSecond as $item) {
            if (!is_array($arrFirst) || !in_array($item, $arrFirst)) {

                $arr[] = $item;

            }
        }
        return $arr;
    }
}
