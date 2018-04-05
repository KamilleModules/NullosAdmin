<?php


namespace Module\NullosAdmin\Models\StandardPage\Top;


class PageTop
{
    protected $title;
    protected $description;
    /**
     * @var BreadCrumbs
     */
    protected $_breadcrumbs;

    /**
     * @var RightBar
     */
    protected $_rightBar;


    public function __construct()
    {
        $this->title = "";
        $this->description = "";
    }


    public static function create()
    {
        return new static();
    }


    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }


    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return BreadCrumbs
     */
    public function breadcrumbs()
    {
        if (null === $this->_breadcrumbs) {
            $this->_breadcrumbs = BreadCrumbs::create();
        }
        return $this->_breadcrumbs;
    }


    /**
     * @return RightBar
     */
    public function rightBar()
    {
        if (null === $this->_rightBar) {
            $this->_rightBar = RightBar::create();
        }
        return $this->_rightBar;
    }
}