<?php


namespace Module\NullosAdmin\Models\StandardPage\Top;


use Bat\UriTool;

class BreadCrumbs
{

    /**
     * @var array of item, each of which:
     *      - type: string (text|link)
     *      - text: string, the text
     *      - ?link: string, the link (only for type=link)
     *      - ?icon: string, an icon identifier to display
     */
    protected $items;

    public function __construct()
    {
        $this->items = [];
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
    public function getItems(): array
    {
        return $this->items;
    }


    //--------------------------------------------
    //
    //--------------------------------------------
    public function addLink(string $text, string $link = null, string $icon = null)
    {

        if (null === $link) {
            $link = UriTool::uri(null, [], false);
        }

        $item = [
            "type" => "link",
            "text" => $text,
            "link" => $link,
        ];
        if ($icon) {
            $item['icon'] = $icon;
        }

        $this->items[] = $item;
        return $this;
    }

    public function addText(string $text, string $icon = null)
    {
        $item = [
            "type" => "text",
            "text" => $text,
        ];
        if ($icon) {
            $item['icon'] = $icon;
        }

        $this->items[] = $item;
        return $this;
    }

    public function reset()
    {
        $this->items = [];
        return $this;
    }

}