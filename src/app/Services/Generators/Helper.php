<?php

namespace AlexClaimer\Generator\App\Services\Generators;

use Illuminate\Support\Str;
use  Illuminate\Support\Arr;

class Helper
{

    /**
     * @param $tab_name
     * @return string
     */
    public static function className($table_name, $classType = '')
    {
        $ClassName = Str::singular(ucfirst(Str::camel($table_name)));

        return $ClassName . $classType;
    }

    /**
     * @param $tab_name
     * @return string
     */
    public static function fullNameSpace($table_name, $classType = 'model')
    {

        return self::makeNameSpace($classType) . '' . self::className($table_name);
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
     * @param $type
     * @return bool|string
     */
    public static function makeFullNameSpaceForView($tName)
    {
        return config('alex-claimer-generator.config.view.namespace') . '\\' . self::makeNameSpaceForView($tName);
    }

    /**
     * @param $postfix
     * @return string
     */
    public static function BaseClassName()
    {
        $postfix = self::getPostfix();
        if ($pos = strpos($postfix, '\\')) {
            $postfix = substr($postfix, $pos + 1);
        }

        return 'Base' . $postfix;
    }

    /**
     * @param $ClassName
     * @return string
     */
    public static function makeFileDirName($type, $ClassName, $viewTableName = '')
    {

        $postfix = config('alex-claimer-generator.config.namespace_postfix');
        if ($type === 'package') $postfix = '';
        if ($viewTableName !== '') {
            $postfix = lcfirst($postfix);
        }
        if (trim($postfix) !== '') $postfix .= '\\';
        if ($type == 'view') {
            $postfix = self::make_views_address_prefix() . '\\';
        }
        $dirName = base_path() . "\\" .
            config('alex-claimer-generator.config.' . $type . '.namespace') . '\\' . $postfix . $viewTableName;
        // bbb(__METHOD__, $dirName, $ClassName);
        self::filterDirNameClassName($dirName, $ClassName);
        // dd(__METHOD__, $dirName, $ClassName);
        $dirName = self::checkAndMakeDir($dirName);

        // dd(__METHOD__, $dirName, $ClassName, $dirName . $ClassName . '.php');//11
        return $dirName . "\\" . $ClassName . '.php';
    }

    protected static function filterDirNameClassName(&$dirName, &$ClassName)
    {

        while ($pos = strpos($ClassName, '/')) {

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
                //if (strpos($newDirName, 'Auth33')) dd(__METHOD__, $newDirName);
                $newDirName = str_replace("/", "\\", $newDirName);
                if (!is_dir($newDirName)) {
                    mkdir($newDirName);
                }
            }
        }
        //dd(__METHOD__, $newDirName);
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
        //dd(__METHOD__, $arrFirst, $arrSecond);
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
    public static function make_views_routes_url($tName, $type = '')
    {
        $postfix = str_replace('\\', '/', self::getPostfix());
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
    public static function make_views_routes_name($tName, $type = '')
    {
        $postfix = str_replace('\\', '_', self::getPostfix());
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
    public static function make_views_routes_prefix()
    {
        $postfix = str_replace('\\', '.', self::getPostfix());

        return strtolower($postfix);
    }

    /**
     * @param $tName
     * @return string
     */
    public static function make_views_address_prefix()
    {
        return strtolower(self::getPostfix());
    }


    /**
     * @param $tName
     * @return string
     */
    public static function make_views_directory($tName, $type = '')
    {
        $postfix = str_replace('\\', '.', self::getPostfix());
        $tName = substr($tName, strpos($tName, '.'));
        if ($type !== '') {
            $type = '.' . $type;
        }
        return strtolower($postfix) . "." . $tName . $type;
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    public static function getPostfix()
    {
        return config('alex-claimer-generator.config.namespace_postfix');
    }
}
