<?php


namespace Module\NullosAdmin\Helper;


use Core\Services\A;
use Models\AdminSidebarMenu\Lee\Objects\Item;
use Models\AdminSidebarMenu\Lee\Objects\Section;
use SokoForm\Control\SokoBooleanChoiceControl;

class NullosAdminBackHooksHelper
{
    public static function Application_decorateLeftMenu(array &$menuItems)
    {
        $section = $menuItems['Application.section'] ?? null;
        if ($section instanceof Section) {

            $modulesListItem = Item::create()
                ->setActive(true)
                ->setName("Application.NullosAdmin.messages")
                ->setLabel("Messages")
                ->setIcon("fa fa-envelope")
                ->setLink(A::link("NullosAdmin_messageList"));
            $section->addItem($modulesListItem);
        }
    }


    public static function Application_MorphicModuleConfigurationUtil_getControlMap(array &$controlMap)
    {

        $controlMap['booleanChoice'] = function ($typeParams) {
            return SokoBooleanChoiceControl::create();
        };
    }

}