<?php

namespace Module\NullosAdmin\Models\TopStatObject;


class TopStatObject
{

    protected $elements;


    public function __construct()
    {
        $this->elements = [];
    }


    public static function create()
    {
        return new static();
    }


    public function addElement(array $element)
    {
        $this->elements[] = $element;
        return $this;
    }


    public function getModel()
    {
        $ret = [];
        $elements = [];
        foreach ($this->elements as $el) {
            $el = array_replace([
                "label" => "",
                "icon" => "",
                "iconColor" => "",
                "color" => "",
                "content" => '',
                "theme" => '',
            ], $el);

            $elements[] = $el;
        }
        $ret['elements'] = $elements;
        return $ret;
    }
}