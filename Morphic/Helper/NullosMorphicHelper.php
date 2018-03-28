<?php


namespace Module\NullosAdmin\Morphic\Helper;


use ArrayToString\ArrayToStringTool;
use Core\Services\Hooks;
use Module\NullosAdmin\Exception\NullosException;

class NullosMorphicHelper
{


    public static function getStandardSearchList($name)
    {
        switch ($name) {
            /**
             * @todo-ling: should be localized?
             */
            case "active":
                return [
                    '1' => 'Oui',
                    '0' => 'Non',
                ];
                break;
            default:
                throw new NullosException("Unknown search list with name $name");
                break;
        }
    }

    public static function getStandardIcon($type)
    {
        switch ($type) {
            case "check":
                return '<i style="color: #02c302" class="fa fa-check"></i>';
                break;
            case "remove":
                return '<i style="color: #c30118" class="fa fa-remove"></i>';
                break;
            default:
                break;
        }
    }


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
            case "color":
                return function ($value, array $row) use ($options) {
                    return '<div style="width:20px; height: 20px; border: 1px solid #e0e0e0; background-color: ' . $value . '">&nbsp;</div>';
                };
                break;
            case "pill":
                return function ($value, array $row) use ($options) {
                    $class = $options['class'] ?? "success";
                    return '<div class="label label-pill label-' . $class . '">' . $value . '</div>';
                };
                break;
            case "nowrap":
                return function ($value, array $row) {
                    return '<span style="white-space: nowrap">' . $value . '</span>';
                };
                break;
            case "date":
            case "datetime":
                if ("date" === $name) {
                    $forbidden = '0000-00-00';
                } else {
                    $forbidden = '0000-00-00 00:00:00';
                }
                return function ($value, array $row) use ($forbidden) {
                    if ($value && $forbidden !== $value) {
                        return '<span style="white-space: nowrap">' . $value . '</span>';
                    }
                };
                break;
            case "image":
                $width = $options['width'] ?? 80;
                return function ($value, array $row) use ($width) {
                    return '<img src="' . $value . '" alt="image" width="' . $width . '">';
                };
                break;
            default:
                $func = null;
                Hooks::call("NullosAdmin_MorphicHelper_StandardColTransformer", $func, $name, $options);

                if (is_callable($func)) {
                    return $func;
                } else {
                    throw new NullosException("Unknown col transformer with name $name");
                }
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