<?php


use Kamille\Architecture\ApplicationParameters\ApplicationParameters;
use Kamille\Mvc\HtmlPageHelper\HtmlPageHelper;




$prefixUri = "/theme/" . ApplicationParameters::get("theme");
HtmlPageHelper::css("$prefixUri/vendors/bootstrap/dist/css/bootstrap.min.css"); // bootstrap
HtmlPageHelper::css("$prefixUri/vendors/font-awesome/css/font-awesome.min.css"); // font awesome
HtmlPageHelper::css("$prefixUri/vendors/nprogress/nprogress.css"); // nprogress
HtmlPageHelper::css("$prefixUri/vendors/animate.css/animate.min.css"); // nprogress
HtmlPageHelper::css("$prefixUri/build/css/custom.min.css"); // custom theme style
HtmlPageHelper::css("$prefixUri/css/nullos.css"); //

HtmlPageHelper::addBodyClass("login");



?>

<div>
<?php $l->position('maincontent'); ?>
</div>
