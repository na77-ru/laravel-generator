<?php

namespace AlexClaimer\Generator\App\Services\Generator;

use Illuminate\Support\Str;
use  Illuminate\Support\Arr;

class Helper
{

    /**
     * @param $tab_name
     * @return string
     */
    public static function className($table_name, $Type = '')
    {
        $ClassName = Str::singular(ucfirst(Str::camel($table_name)));

        return $ClassName . $Type;
    }
    /**
     * @param $type
     * @return bool|string
     */
    public static function makeNameSpace($type)
    {
        $postfix = config('alex-claimer-generator.config.namespace_postfix');
        if (trim($postfix) !== '') $postfix .= '\\';

        $str = config('alex-claimer-generator.config.' . $type . '.namespace') . '\\' . $postfix;

        $str = mb_substr($str, 1, strlen($str) - 2);
        $str = ucfirst($str);

        return $str;
    }

    /**
     * @param $ClassName
     * @return string
     */
    public static function makeFileDirName($type, $ClassName, $viewTableName = '')
    {

        $postfix = config('alex-claimer-generator.config.namespace_postfix');
        if ($viewTableName !== '') {
            $postfix = lcfirst($postfix);
        }
        if (trim($postfix) !== '') $postfix .= '\\';
        $dirName = base_path() .
            config('alex-claimer-generator.config.' . $type . '.namespace') . '\\' . $postfix  . $viewTableName;
      //  if (strpos($dirName, 'resou'))bbb(__METHOD__, $dirName, $ClassName, '');
        self::filterDirNameClassName($dirName, $ClassName);
   // if (strpos($dirName, 'inc'))dd(__METHOD__, $dirName, $ClassName);
        $dirName = self::checkAndMakeDir($dirName);

        // dd(__METHOD__, $dirName, $ClassName, $dirName . $ClassName . '.php');//11
        return $dirName . "\\" . $ClassName . '.php';
    }

    protected static function filterDirNameClassName(&$dirName, &$ClassName)
    {

        if ( $pos = strpos($ClassName,'/') ) {

            $dirName = $dirName . "\\" . substr($ClassName, 0, $pos);
            $ClassName = substr($ClassName, $pos + 1);

            //dd(__METHOD__, $dirName, $ClassName);
        }
    }

    /**
     * @param $dirName
     * @return string
     */
    protected static function checkAndMakeDir($dirNameBegin)
    {
        $arDirName = explode('\\', $dirNameBegin);
        $newDirName = '';
        foreach ($arDirName as $key => $dirName) {
            if ($dirName != '') {
                $newDirName .= $arDirName[$key] . '\\';

                if (!is_dir($newDirName)) {
                    mkdir($newDirName);
                }
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
    /**
     * @param $tName
     * @return string
     */
    public static  function make_views_routes_url($tName, $type = '')
    {
        $tName = substr($tName, strpos($tName, '_') + 1);
        return lcfirst(self::getPostfix()) . "/" . $tName . "/" . $type;
    }
    /**
     * @param $tName
     * @return string
     */
    public static  function make_views_routes_name($tName, $type = '')
    {
        $tName = substr($tName, strpos($tName, '_') + 1);
        return lcfirst(self::getPostfix()) . "." . $tName . "." . $type;
    }
    /**
     * @param $tName
     * @return string
     */
    public static  function make_views_directory($tName, $type = '')
    {
        return lcfirst(self::getPostfix()) . "." . $tName . "." . $type;
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    public static function getPostfix()
    {
        return config('alex-claimer-generator.config.namespace_postfix');
    }
}
