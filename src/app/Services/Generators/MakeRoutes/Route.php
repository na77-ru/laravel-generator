<?php

namespace AlexClaimer\Generator\App\Services\Generators\MakeRoutes;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use AlexClaimer\Generator\App\Services\Generators\Helper;

class Route
{
    protected static $output_web;

    /**
     * @return mixed
     */
    public static function getOutputWeb()
    {
        if ($start = strpos(self::$output_web, self::getRoutesBeginComment())) {
            $end = strpos(self::$output_web, self::getRoutesEndComment()) + strlen(self::getRoutesEndComment());
            return substr(self::$output_web, 0, $start);
            //return substr(self::$output_web, 0, $start) . substr(self::$output_web, $end, strlen(self::$output_web) - $end);
        } else {
            return self::$output_web;
        }

    }

    /**
     * @param mixed $output_web
     */
    public static function setOutputWeb($output_web): void
    {
        self::$output_web = $output_web;
    }

    public static function shorterName($tName)
    {
        $max = 22;
        if (strlen($tName) < $max) {
            return $tName;
        }

        $arrTableName = explode('_', $tName);
        foreach ($arrTableName as &$str) {
            $str = substr($str, 0, strlen($str) - 1);
        }
        $tName = implode($arrTableName);

        if (strlen($tName) < $max) {
            return $tName;
        } else {
            self::shorterName($tName);
        }
    }

    /**
     * @param $tName
     * @return string
     */
    public static function make_routes_url($tName, $type = '')
    {
        $tName = self::shorterName($tName);

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
        $tName = self::shorterName($tName);

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

    /**
     * @return string
     */
    public static function getRoutesBeginComment()
    {
        $comment = "// >> \\" . Helper::getPostfix() . "\ GENERATED ROUTES BEGIN >> ";
        if (strpos(self::$output_web, $comment)) {
            $rn = '';
        } else {
            $rn = "\r\n\r\n";
        }
        return $rn . $comment;
    }

    /**
     * @return string
     */
    public static function getRoutesEndComment()
    {
        return "// << \\" . Helper::getPostfix() . "\ GENERATED ROUTES END << ";
    }

    /**
     * @return string
     */
    public static function getGroupForAllRoutesClassName()
    {
        return "" . Helper::getPostfix() . "\GroupForAllRoutes";
    }

    /**
     * @param bool $rewrite
     * @return bool|string
     */
    public static function getGroupForAllRoutes($rewrite = false)
    {
        $needle = "// GROUP FOR ALL \\" . Helper::getPostfix() . "\ ROUTES";

        if ($rewrite || !strpos(self::$output_web, $needle)) {
            return "Route::group(['middleware' => ['auth', 'permission']],function () { " . $needle;
        } else {
            $posStart = strpos(self::$output_web, self::getRoutesBeginComment()) + strlen(self::getRoutesBeginComment()) + 2;
            $posEnd = strpos(self::$output_web, $needle) + strlen($needle);
            return substr(self::$output_web, $posStart, $posEnd - $posStart);
        }
    }

    /**
     * @param $tName
     * @return bool|string
     */
    public static function getModelRoutesFromWebPhp($tName)
    {
        $start = strpos(self::$output_web, self::getModelRoutesBeginComment($tName));
        $end = strpos(self::$output_web, self::getModelRoutesEndComment($tName)) + strlen(self::getModelRoutesEndComment($tName));
        if ($start && $end) {
            return substr(self::$output_web, $start, $end - $start);
        } else {
            return "";
        }

    }

    /**
     * @return string
     */
    public static function getEndGroupForAllRoutes()
    {
        return "\r\n\t});";
    }

    /**
     * @param $tName
     * @return string
     */
    public static function getFullClassName($tName)
    {
        $className = Helper::className($tName) . "\Routes";
        $nameSpace = Helper::makeNameSpace('route');

        return $nameSpace . "\\" . $className;

    }

    /**
     * @return string
     */
    public static function getModelRoutesBeginComment($tName)
    {
        return "// >> " . self::getFullClassName($tName);
    }

    /**
     * @return string
     */
    public static function getModelRoutesEndComment($tName)
    {
        return "// << " . self::getFullClassName($tName);
    }
}
