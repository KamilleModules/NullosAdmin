<?php


namespace Module\NullosAdmin\Morphic\Helper;


use ArrayToString\ArrayToStringTool;
use Module\NullosAdmin\Exception\NullosException;

class NullosMorphicHelper
{
    public static function getStandardColTransformer($name, array $options = [])
    {
        switch ($name) {
            case "active":
                return function ($value, array $row) {
                    if (1 === (int)$value) {
                        return '<i style="color: #02c302" class="fa fa-check"></i>';
                    } else {
                        return '<i style="color: #c30118" class="fa fa-remove"></i>';
                    }
                };
                break;
            case "unserialize":
                return function ($value, array $row) {
                    $val = unserialize($value);
                    if ($val) {
                        if (is_array($val)) {
                            return self::mydump($val);
                        }
                        return (string)$val;
                    }
                    return "";
                };
                break;
            case "toolong":
                return function ($value, array $row) use ($options) {
                    $length = $options['length'] ?? 50;
                    $s = substr($value, 0, $length);
                    if (strlen($value) > $length) {
                        $s .= '...';
                    }
                    return $s;
                };
                break;
            default:
                throw new NullosException("Unknown col transformer with name $name");
                break;
        }
    }


    private static function mydump()
    {
        foreach (func_get_args() as $arg) {
            ob_start();
            var_dump($arg);
            $output = ob_get_clean();
            if ('1' !== ini_get('xdebug.default_enable')) {
                $output = preg_replace("!\]\=\>\n(\s+)!m", "] => ", $output);
            }
            return '<pre>' . $output . '</pre>';
        }
    }
}