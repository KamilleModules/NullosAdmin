<?php


namespace Controller\NullosAdmin;


use Core\Services\Hooks;
use Kamille\Architecture\ApplicationParameters\ApplicationParameters;
use Kamille\Architecture\Controller\Web\KamilleClawsController;
use Kamille\Services\XLog;
use Kamille\Utils\Claws\ClawsWidget;
use Models\AdminSidebarMenu\Lee\LeeAdminSidebarMenuModel;
use Module\Ekom\Utils\E;
use Module\NullosAdmin\Authenticate\User\NullosUser;
use Module\NullosAdmin\Helper\NullosGuiEnvironment;
use Module\NullosAdmin\Session\NullosSession;
use Module\NullosAdmin\Utils\N;

class NullosBaseController extends KamilleClawsController
{

    protected function handleClawsException(\Exception $e)
    {
        $res = parent::handleClawsException($e);
        if (false === $res) {
            $this->getClaws()
                ->setLayout("admin/default")
                ->setWidget("top_notifications.nullosNotifs", ClawsWidget::create()
                    ->setTemplate("NullosAdmin/Notifications/Notifications/top-notifications")
                    ->setConf([
                        'notifications' => [
                            [
                                "title" => "An error occurred",
                                "type" => "error",
                                "msg" => $e->getMessage(),
                            ],
                        ],
                    ])
                );
            $res = $this->doRenderClaws();
            XLog::error("$e");
        }
        return $res;
    }


    /**
     * See original file when in doubt:
     * /myphp/leaderfit/leaderfit/class-modules/NullosAdmin/Controller/NullosAdminController.php
     */
    protected function prepareClaws()
    {
        parent::prepareClaws();


        $sidebarModel = LeeAdminSidebarMenuModel::create();
        Hooks::call("NullosAdmin_layout_sideBarMenuModelObject", $sidebarModel);


        // https://github.com/lingtalfi/Models/blob/master/Notification/NotificationsModel.php
        $topNotifications = [];
        Hooks::call("NullosAdmin_layout_topNotifications", $topNotifications);
        if ($topNotifications) {
            $this->getClaws()->setWidget("top_notifications.nullosNotifs", ClawsWidget::create()
                ->setTemplate("NullosAdmin/Notifications/Notifications/top-notifications")
                ->setConf([
                    'notifications' => $topNotifications,
                ])
            );
        }


        $prefixUri = "/theme/" . ApplicationParameters::get("theme");
        $imgPrefix = $prefixUri . "/production";
        $defaultImage = N::defaultUserImage();


        $this->getClaws()
            ->setLayout('admin/default')
            //--------------------------------------------
            // SIDEBAR
            //--------------------------------------------
            ->setWidget("sidebar.title", ClawsWidget::create()
                ->setTemplate('NullosAdmin/NavTitle/default')
                ->setConf([
                    "link" => N::link("NullosAdmin_home"),
                    "iconClass" => "fa fa-paw",
                    "title" => "NullosAdmin",
                ])
            )
            ->setWidget("sidebar.menuProfileQuickInfo", ClawsWidget::create()
                ->setTemplate('NullosAdmin/MenuProfileQuickInfo/default')
                ->setConf([
                    "imgSrc" => NullosUser::get('avatar', $defaultImage),
                    "imgAlt" => "...",
                    "welcomeText" => "Welcome,",
                    "userName" => NullosUser::get('pseudo', "user"),
                ])
            )
            ->setWidget("sidebar.menuFooterButtons", ClawsWidget::create()
                ->setTemplate('NullosAdmin/MenuFooterButtons/default')
                ->setConf([
                    'buttons' => [
                        [
                            "title" => 'Settings',
                            "icon" => 'glyphicon glyphicon-cog',
                        ],
                        [
                            "title" => 'FullScreen',
                            "icon" => 'glyphicon glyphicon-fullscreen',
                        ],
                        [
                            "title" => 'Lock',
                            "icon" => 'glyphicon glyphicon-eye-close',
                        ],
                        [
                            "title" => 'Logout',
                            "link" => '/logout',
                            "icon" => 'glyphicon glyphicon-off',
                        ],
                    ],
                ])
            )
            ->setWidget("sidebar.menu", ClawsWidget::create()
                ->setTemplate('NullosAdmin/SidebarMenu/default')
                ->setConf([
                    "sidebarMenuModel" => [
                        "sections" => $sidebarModel->getArray(),
                    ],
                ])
            )
            //--------------------------------------------
            // TOPBAR
            //--------------------------------------------
            ->setWidget("topbar_left.menuToggle", ClawsWidget::create()
                ->setTemplate('NullosAdmin/MenuToggle/default')
                ->setConf([])
            )
            ->setWidget("topbar_right.userMenuDropdown", ClawsWidget::create()
                ->setTemplate('NullosAdmin/TopBar/UserMenuDropdown/default')
                ->setConf([
                    "userImgSrc" => NullosUser::get('avatar', $defaultImage),
                    "userName" => NullosUser::get('pseudo', "user"),
                    "items" => [
                        [
                            'text' => "Profile",
                            'link' => N::link("NullosAdmin_currentUserProfile"),
                        ],
                        [
                            'text' => "Settings",
                            'badge' => [
                                'color' => "red",
                                'text' => "50%",
                            ],
                        ],
                        [
                            'text' => "Help",
                        ],
                        [
                            'text' => "Log out",
                            'icon' => "fa fa-sign-out",
                            'link' => "?disconnect",
                        ],
                    ],
                ])
            )
            ->setWidget("topbar_right.userMessages", ClawsWidget::create()
                ->setTemplate('NullosAdmin/TopBar/UserMessages/default')
                ->setConf([
                    'nbMessages' => 10,
                    'badgeColor' => 'red',
                    'showAllMessagesLink' => true,
                    'allMessagesText' => "See All Alerts",
                    'allMessagesLink' => "/user-alerts",
                    "messages" => [
                        [
                            "link" => "/ji",
                            "title" => "John Smith",
                            "image" => NullosUser::get('avatar', $defaultImage),
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
                ])
            );


        $notifs = NullosGuiEnvironment::getNotifications();
        if ($notifs) {
            $this->getClaws()->setWidget("notifications.notif", ClawsWidget::create()
                ->setTemplate("NullosAdmin/Notifications/Notifications/default")
                ->setConf([
                    "notifications" => $notifs,
                ])
            );
        }

    }


    protected function addNotification($message, $type, $title = null)
    {
        NullosGuiEnvironment::addNotification($message, $type, $title);
    }

}