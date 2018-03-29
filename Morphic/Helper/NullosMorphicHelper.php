<?php


namespace Module\NullosAdmin\Morphic\Helper;


use ArrayToString\ArrayToStringTool;
use Bat\StringTool;
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
                $title = $options['title'] ?? null;
                return function ($value, array $row) use ($width, $title) {

                    $s = '<img src="' . $value . '" alt="image" width="' . $width . '"';
                    if ($title) {
                        if (is_callable($title)) {
                            $title = call_user_func($title, $row);
                        }
                        $s .= ' title="' . htmlspecialchars($title) . '"';
                    }
                    $s .= '>';
                    return $s;
                };
                break;
            case "rating":
                $maxValue = $options['maxValue'] ?? 100; // percent by default
                $maxNbStars = $options['maxNbStars'] ?? 5; // 5 stars max by default
                $class = $options['class'] ?? 'nullos-morphic-rating'; // 5 stars max by default

                // doing some preparation work now...
                if ($maxValue < $maxNbStars) {
                    throw new \LogicException("The maxValue ($maxValue) cannot be less than the maxNbStars ($maxNbStars)");
                }
                $step = $maxValue / $maxNbStars; // try to make this number without decimals when you define the params... (because I don't handle them)
                $halfStep = $step / 2;


                return function ($value, array $row) use ($halfStep, $maxNbStars, $class) {

                    $nbHalfSteps = ceil($value / $halfStep);

                    $nbSteps = $nbHalfSteps / 2;
                    $extraHalfStep = (($nbHalfSteps % 2) === 1);
                    $s = '<div style="white-space: nowrap" class="' . $class . '">';
                    $extraDone = false;
                    for ($i = 1; $i <= $maxNbStars; $i++) {
                        if ($i <= $nbSteps) {

                            $s .= <<<EEE
<span class="fa fa-star"></span>
EEE;
                        } else {
                            if (true === $extraHalfStep && false === $extraDone) {
                                $extraDone = true;
                                $s .= <<<EEE
<span class="fa fa-star-half-o"></span>
EEE;
                            } else {

                                $s .= <<<EEE
<span class="fa fa-star-o"></span>
EEE;
                            }
                        }
                    }
                    $s .= '</div>';
                    return $s;
                };
                break;
            case "dropdown":
                $callback = $options['callback'] ?? null;
                if (null === $callback) {
                    $callback = function ($value, $row) {
                        return [
                            "label" => "Actions",
                            "openingSide" => "left", // left|right
                            "items" => [
                                [
                                    "label" => "Action1",
                                    "link" => "#",
                                ],
                                [

                                    "label" => "Action2",
                                    "link" => "#",
                                ],
                                [
                                    "label" => "Action3",
                                    "link" => "#",
                                ],
                            ],
                        ];

                    };
                }


                return function ($value, array $row) use ($callback) {


                    $config = call_user_func($callback, $value, $row);

                    $label = $config['label'] ?? "Action";
                    $openingSide = $config['openingSide'] ?? "right";
                    $class = '';
                    if ('left' === $openingSide) {
                        $class = 'dropdown-menu-right';
                    }
                    $items = $config['items'] ?? [];
                    $sLinks = "";
                    foreach ($items as $item) {
                        $link = htmlspecialchars($item['link']);
                        $attributes = $item['attributes'] ?? [];
                        $classes = $item['class'] ?? [];
                        if (!is_array($classes)) {
                            $classes = [$classes];
                        }

                        $sClasses = implode(' ', $classes);
                        $sAttr = '';
                        if($attributes){
                            $sAttr = StringTool::htmlAttributes($attributes);
                        }


                        $sLinks .= <<<EEE
<li><a class="$sClasses" $sAttr href="$link">$item[label]</a></li>
EEE;

                        $sLinks .= PHP_EOL;
                    }


                    return <<<EEE
<div class="dropdown">
  <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    $label
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu $class">
    $sLinks
  </ul>
</div>
EEE;


//                    $link = sprintf($linkFmt, $row['product_type_id'], $row['card_id'], $row['product_id']);
                    $link = "#";
                    return <<<EEE
<a href="$link" class="btn btn-default btn-xs">Voir le produit</a>
EEE;

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