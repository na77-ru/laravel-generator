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
        $postfix = config('alex-claimer-generator.config.namespace_postfix');
        if (trim($postfix) !== '') $postfix .= '\\';
        $dirName = base_path() .
            config('alex-claimer-generator.config.' . $type . '.namespace') . '\\' . $postfix;

        $dirName = self::checkAndMakeDir($dirName);

        dd(__METHOD__,$dirName, $ClassName, $dirName . $ClassName . '.php');//11
        return $dirName . $ClassName . '.php';
    }

    /**
     * @param $dirName
     * @return string
     */
    public static function checkAndMakeDir($dirName)
    {
        $arDirName = explode('\\', $dirName);
        $newDirName = '';
        foreach ($arDirName as $key => $dirName) {

            $newDirName = $arDirName[$key];
            bbb(__METHOD__, $arDirName, $key, $dirName, $newDirName);//11
            if (!is_dir($dirName)) {
                mkdir($dirName);bbb('not exist');
            }
        }
        return $newDirName;
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
