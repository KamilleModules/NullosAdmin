<?php


namespace Module\NullosAdmin\Architecture\Router;


use Authenticate\SessionUser\SessionUser;
use Bat\UriTool;
use Kamille\Architecture\ApplicationParameters\ApplicationParameters;
use Kamille\Architecture\Request\Web\HttpRequestInterface;
use Kamille\Architecture\Response\Web\HttpResponseInterface;
use Kamille\Architecture\Response\Web\RedirectResponse;
use Kamille\Architecture\Router\Helper\RouterHelper;
use Kamille\Architecture\Router\Web\WebRouterInterface;
use Kamille\Ling\Z;
use Kamille\Services\XConfig;
use Module\NullosAdmin\Authenticate\User\NullosUser;

class BackOfficeAuthenticationRouter implements WebRouterInterface
{

    public static function create()
    {
        return new static();
    }

    public function match(HttpRequestInterface $request)
    {


        if ("dual.back" === $request->get("siteType")) {

            if (false === NullosUser::isConnected()) {
                return XConfig::get("NullosAdmin.controllerLoginForm");
            } else {


                //--------------------------------------------
                // USER DISCONNECTION SYSTEM
                //--------------------------------------------
                $dkey = XConfig::get("NullosAdmin.disconnectGetKey");
                if (array_key_exists($dkey, $_GET)) {
                    $get = $_GET;
                    unset($get[$dkey]);


                    $request->set("response", RedirectResponse::create(Z::uri(null, $get, true, true)));
                    NullosUser::disconnect();

                    /**
                     * By not returning null, we make the router believe a controller was found,
                     * so that it doesn't loop the other routers.
                     */
                    return "";
                } else {

//                    if (true === XConfig::get("Authenticate.allowSessionRefresh")) {
//                        SessionUser::refresh();
//                    }
                }
            }
        }
    }
}