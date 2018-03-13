<?php


namespace Module\NullosAdmin\Morphic\Renderer;


use Kamille\Utils\Morphic\ListRenderer\MorphicAdminListRenderer;

class NullosMorphicAdminListRenderer extends MorphicAdminListRenderer
{
    public function __construct()
    {
        parent::__construct();
        $this->setWidgetRendererIdentifier('GuiAdminTableRenderer\NullosGuiAdminTableWidgetRenderer');
    }
}