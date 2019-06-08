<?php

namespace AlexClaimer\Generator\App\Services\Generator;

use Illuminate\Support\Str;
use  Illuminate\Support\Arr;

class Helper
{
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

        self::filterDirNameClassName($dirName, $ClassName);

        $dirName = self::checkAndMakeDir($dirName);

        // dd(__METHOD__, $dirName, $ClassName, $dirName . $ClassName . '.php');//11
        return $dirName . $ClassName . '.php';
    }

    public static function filterDirNameClassName(&$dirName, &$ClassName)
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
    public static function checkAndMakeDir($dirNameBegin)
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

    public static function writeAlreadyMade($alreadyMade)
    {
        $alreadyMade = Arr::sort($alreadyMade);

        $str_alreadyMade = "<?php\r\nreturn [\r\n";
        foreach ($alreadyMade as $type => $arr) {

            $str_alreadyMade .= "    '$type' => [\r\n";

            foreach ($arr as $name) {
                $str_alreadyMade .= "        '" . $name . "',\r\n";

            }
            $str_alreadyMade .= "   ],\r\n";
        }
        $str_alreadyMade .= "];";


        file_put_contents(base_path() . '\config\alex-claimer-generator\already_made.php', $str_alreadyMade);

    }
}
