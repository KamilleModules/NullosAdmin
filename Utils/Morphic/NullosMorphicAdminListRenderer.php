<?php


namespace Module\NullosAdmin\Utils\Morphic;


use Kamille\Utils\Morphic\ListRenderer\MorphicAdminListRenderer;

class NullosMorphicAdminListRenderer extends MorphicAdminListRenderer
{
    public function __construct()
    {
        parent::__construct();
        $this->setWidgetRendererIdentifier('GuiAdminTableRenderer\NullosGuiAdminTableWidgetRenderer');
    }
}