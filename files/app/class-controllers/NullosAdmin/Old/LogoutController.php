<?php


namespace Controller\NullosAdmin;


use Authenticate\SessionUser\SessionUser;
use Bat\UriTool;
use Kamille\Architecture\Controller\Web\KamilleController;
use Kamille\Architecture\Response\Web\RedirectResponse;

class LogoutController extends KamilleController
{


    public function logout()
    {
        $uri = (array_key_exists("HTTP_REFERER", $_SERVER)) ? $_SERVER['HTTP_REFERER'] : UriTool::getWebsiteAbsoluteUrl();
        SessionUser::disconnect();
        return RedirectResponse::create($uri);
    }


}