<?php


namespace Module\NullosAdmin;




class NullosAdminHooks
{
    //--------------------------------------------
    // NULLOS ADMIN
    //--------------------------------------------
    protected static function NullosAdmin_layout_addTopBarRightWidgets(array &$topbarRightWidgets)
    {
        // mit-start:MyWildCode
        $prefixUri = "/theme/" . \Kamille\Architecture\ApplicationParameters\ApplicationParameters::get("theme");
        $imgPrefix = $prefixUri . "/production";

        unset($topbarRightWidgets['topbar_right.userMessages']);

        $topbarRightWidgets["topbar_right.shopListDropDown"] = [
            "tpl" => "Ekom/ShopListDropDown/prototype",
            "conf" => [
                'nbMessages' => 10,
                'badgeColor' => 'red',
                'showAllMessagesLink' => true,
                'allMessagesText' => "See All Alerts",
                'allMessagesLink' => "/user-alerts",
                "messages" => [
                    [
                        "link" => "/ji",
                        "title" => "John Smith",
                        "image" => $imgPrefix . '/images/ling.jpg',
                        "aux" => "3 mins ago",
                        "message" => "Film festivals used to be do-or-die moments for movie makers. They were where...",
                    ],
                    [
                        "link" => "/ji",
                        "title" => "John Smith",
                        "image" => $imgPrefix . '/images/img.jpg',
                        "aux" => "12 mins ago",
                        "message" => "Film festivals used to be do-or-die moments for movie makers. They were where...",
                    ],
                ],
            ],
        ];
        // mit-end:MyWildCode
    }

    protected static function NullosAdmin_layout_sideBarMenuModelObject(\Models\AdminSidebarMenu\Lee\LeeAdminSidebarMenuModel $sideBarMenuModel)
    {

    }




    protected static function NullosAdmin_prepareHomePageClaws(\Kamille\Utils\Claws\ClawsInterface $claws)
    {

    }

//    protected static function NullosAdmin_SokoForm_NullosBootstrapRenderer_AutocompleteInitialValue(&$label, $action, $value)
//    {
//        BackHooksHelper::NullosAdmin_SokoForm_NullosBootstrapRenderer_AutocompleteInitialValue($label, $action, $value);
//    }
//
//    protected static function NullosAdmin_User_hasRight(&$hasRight, $privilege)
//    {
//        BackHooksHelper::NullosAdmin_User_hasRight($hasRight, $privilege);
//    }
//
//    protected static function NullosAdmin_User_populateConnectedUser(array &$user)
//    {
//        BackHooksHelper::NullosAdmin_User_populateConnectedUser($user);
//    }

}


