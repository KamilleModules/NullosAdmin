<?php


namespace Module\NullosAdmin\SokoForm\Control;


use SokoForm\Control\SokoControl;

/**
 * default is static (vs ajax fetched)
 */
class NullosSokoFancyTreeControl extends SokoControl
{

    protected $categories;
    protected $expanded;


    /**
     * $categories: array of category, each of which contains at least the following:
     *
     * - id
     * - label
     * - children: --recursive--
     *
     */
    public function setCategories(array $categories)
    {
        $this->categories = $categories;
        return $this;
    }

    public function setExpanded(array $expanded)
    {
        $this->expanded = $expanded;
        return $this;
    }

    protected function getSpecificModel() // override me
    {
        $model = parent::getSpecificModel();
        $model['categories'] = $this->categories;
        $model['expanded'] = $this->expanded;
        return $model;
    }


}