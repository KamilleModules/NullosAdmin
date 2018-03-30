<?php


namespace Module\NullosAdmin\Utils;


use Core\Services\A;
use Kamille\Architecture\ApplicationParameters\ApplicationParameters;

class N
{

    public static function link($routeId, array $params = [], $absolute = false, $https = null)
    {
        return A::link($routeId, $params, $absolute, $https);
    }

    public static function defaultUserImage()
    {
        $prefixUri = "/theme/" . ApplicationParameters::get("theme");
        $imgPrefix = $prefixUri . "/production";
        return $imgPrefix . '/images/ninja-dab.png';
    }


    /**
     * @return string, the lang for backoffice display (iso code 3-letters).
     * Every module using nullos environment should use this method to know in which
     * lang the backoffice should be displayed (i.e. what's the lang of the current
     * nullos gui user...).
     *
     * This will be used to define (for instance):
     *
     * - lang in datepicker js widgets
     * - general strings everywhere in the nullos gui environment
     *
     *
     */
    public static function lang()
    {
        return "fra";
    }
}