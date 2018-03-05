<?php


namespace Theme;


use Kamille\Mvc\HtmlPageHelper\HtmlPageHelper;

class NullosTheme
{

    private static $loaded = [];


    public static function useLib($libName, $extra = null)
    {
        if (false === array_key_exists($libName, self::$loaded)) {
            self::$loaded[$libName] = true;

            $prefixUri = "/theme/nullosAdmin";

            switch ($libName) {
                case 'bionic':
                    HtmlPageHelper::js($prefixUri . "/lib/bionic/bionic.js", null, null, false);
                    break;
                case 'datatable':
                    HtmlPageHelper::css($prefixUri . "/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css", null, null);
                    HtmlPageHelper::js($prefixUri . "/js/morphic.js");
                    break;
                case 'fancytree':

                    self::useLib("jqueryUi");

                    HtmlPageHelper::css($prefixUri . "/lib/fancytree/src/skin-win8/ui.fancytree.css", null, null);
//                    HtmlPageHelper::css($prefixUri . "/src/skin-bootstrap/ui.fancytree.css", null, null);
                    HtmlPageHelper::css($prefixUri . "/css/fancy-tree/fancy-tree.css", null, null);

                    HtmlPageHelper::js($prefixUri . "/lib/fancytree/src/jquery.fancytree.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/lib/fancytree/src/jquery.fancytree.dnd.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/lib/fancytree/src/jquery.fancytree.edit.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/lib/fancytree/src/jquery.fancytree.gridnav.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/lib/fancytree/src/jquery.fancytree.table.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/lib/fancytree/src/jquery.fancytree.filter.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/lib/fancytree/src/jquery.fancytree.glyph.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/lib/fancytree/src/jquery.fancytree.wide.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/lib/fancytree/src/jquery.fancytree.persist.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/lib/js-cookie/js.cookie.min.js", null, null, false);
                    break;
                case 'form':
                    HtmlPageHelper::css($prefixUri . "/vendors/switchery/dist/switchery.min.css", null, null);
                    HtmlPageHelper::js($prefixUri . "/vendors/switchery/dist/switchery.min.js", null, null, false);

                    HtmlPageHelper::css($prefixUri . "/vendors/dropzone/dist/min/dropzone.min.css", null, null);
                    HtmlPageHelper::js($prefixUri . "/vendors/dropzone/dist/min/dropzone.min.js", null, null, false);
                    break;
                case 'jqueryUi':
                    HtmlPageHelper::css($prefixUri . "/lib/jquery-ui/jquery-ui.min.css", 'jqueryui', null);
                    HtmlPageHelper::js($prefixUri . "/lib/jquery-ui/jquery-ui.min.js", 'jqueryui', null, false);
//                    HtmlPageHelper::css("//code.jquery.com/ui/1.8.10/themes/smoothness/jquery-ui.css", 'jqueryui', null);
//                    HtmlPageHelper::js("//ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/jquery-ui.min.js", 'jqueryui', null, false);


                    break;
                case 'jqueryUiDate':

                    self::useLib("jqueryUi");


                    // default date picker
                    HtmlPageHelper::js($prefixUri . "/vendors/moment/min/moment.min.js", "moment", null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/bootstrap-daterangepicker/daterangepicker.js", null, null, false);


                    // datetime picker
                    HtmlPageHelper::js("/libs/timepicker/jquery-ui-timepicker-addon.js", null, null, false);


                    // translation files
                    if ($extra) {

                        $langDate = 'en-GB';
                        $langDatepicker = 'am';

                        switch ($extra) {
                            case "fra":
                                $langDate = 'fr';
                                $langDatepicker = 'fr';
                                break;
                            case "eng":
                                $langDate = 'en-GB';
                                $langDatepicker = 'am';
                                break;
                            default:
                                break;
                        }
                        HtmlPageHelper::js($prefixUri . "/lib/jquery-ui/datepicker-i18n/datepicker-$langDate.js", null, null, false);
                        HtmlPageHelper::js($prefixUri . "/lib/jquery-ui-datetimepicker/lang/jquery-ui-timepicker-$langDatepicker.js", null, null, false);
                    }


                    HtmlPageHelper::css($prefixUri . "/vendors/bootstrap-daterangepicker/daterangepicker.css", null, null);
                    HtmlPageHelper::css("/libs/timepicker/jquery-ui-timepicker-addon.css", null, null);


                    break;
                case 'pnotify':
                    HtmlPageHelper::css($prefixUri . "/vendors/pnotify/dist/pnotify.css", null, null);
                    HtmlPageHelper::css($prefixUri . "/vendors/pnotify/dist/pnotify.buttons.css", null, null);
                    HtmlPageHelper::css($prefixUri . "/vendors/pnotify/dist/pnotify.nonblock.css", null, null);

                    HtmlPageHelper::js($prefixUri . "/vendors/pnotify/dist/pnotify.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/pnotify/dist/pnotify.buttons.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/pnotify/dist/pnotify.nonblock.js", null, null, false);


                    break;
                case 'soko':


                    self::useLib("jqueryUi");
                    HtmlPageHelper::js("/theme/lee/js/soko-form-error-removal-tool.js", null, null, false);

                    break;
                case 'stats':

                    HtmlPageHelper::js($prefixUri . "/vendors/Chart.js/dist/Chart.min.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/jquery-sparkline/dist/jquery.sparkline.min.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/Flot/jquery.flot.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/Flot/jquery.flot.pie.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/Flot/jquery.flot.time.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/Flot/jquery.flot.stack.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/Flot/jquery.flot.resize.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/Flot/jquery.flot.navigate.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/flot.orderbars/js/jquery.flot.orderBars.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/flot-spline/js/jquery.flot.spline.min.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/flot.curvedlines/curvedLines.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/DateJS/build/date.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/moment/min/moment.min.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/bootstrap-daterangepicker/daterangepicker.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/iCheck/icheck.min.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/jqvmap/dist/jquery.vmap.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/jqvmap/dist/maps/jquery.vmap.world.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js", null, null, false);
                    HtmlPageHelper::js($prefixUri . "/vendors/gauge.js/dist/gauge.min.js", null, null, false);


                    HtmlPageHelper::css($prefixUri . "/vendors/bootstrap-daterangepicker/daterangepicker.css", null, null);
                    HtmlPageHelper::css($prefixUri . "/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css", null, null);



                    break;
                case 'treeview':
                    HtmlPageHelper::js($prefixUri . "/lib/bootstrap-treeview/bootstrap-treeview.min.js", null, null, false);
                    HtmlPageHelper::css($prefixUri . "/lib/bootstrap-treeview/bootstrap-treeview.min.css", null, null);
                    break;
            }
        }
    }


}