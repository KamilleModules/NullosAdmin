<?php


namespace Module\NullosAdmin\Models\StandardPage\Top;


class RightBar
{
    /**
     * @var array of elements, each of which:
     *      - type: string (button|dropdown)
     *      - ...depends on the type
     *          (if button): array with:
     *              - text: string, the text of the link
     *              - link: string, the link (href)
     *              - ?icon: string, the optional icon identifier
     *          (if dropdown), a SimpleDropDownModel array
     * @see https://github.com/lingtalfi/Models/blob/master/DropDown/SimpleDropDownModel.php
     *
     */
    protected $elements;

    public function __construct()
    {
        $this->elements = [];
    }

    public static function create()
    {
        return new static();
    }


    //--------------------------------------------
    //
    //--------------------------------------------
    /**
     * @return array
     */
    public function getElements(): array
    {
        return $this->elements;
    }


    //--------------------------------------------
    //
    //--------------------------------------------
    public function addButton(string $text, string $link, string $icon = null)
    {
        $this->elements[] = $this->getButtonElement($text, $link, $icon);
        return $this;
    }

    public function prependButton(string $text, string $link, string $icon = null)
    {
        array_unshift($this->elements, $this->getButtonElement($text, $link, $icon));
        return $this;
    }


    public function addDropDown(array $dropDownModel)
    {
        $el = $dropDownModel;
        $el["type"] = "dropdown";
        $this->elements[] = $el;
        return $this;
    }

    public function reset()
    {
        $this->elements = [];
        return $this;
    }

    //--------------------------------------------
    //
    //--------------------------------------------
    private function getButtonElement(string $text, string $link, string $icon = null)
    {
        $el = [
            "type" => "button",
            "text" => $text,
            "link" => $link,
        ];
        if ($icon) {
            $el['icon'] = $icon;
        }
        return $el;
    }

}