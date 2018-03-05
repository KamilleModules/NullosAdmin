<?php


namespace Theme\NullosAdmin\Ekom\Back\GuiAdminTableRenderer;


use GuiAdminTable\Renderer\MorphicBootstrap3GuiAdminHtmlTableRenderer;
use Kamille\Mvc\Theme\ThemeWidget\Renderer\BaseThemeWidgetRenderer;
use Kamille\Services\XConfig;
use Theme\NullosTheme;

class GuiAdminTableWidgetRenderer extends BaseThemeWidgetRenderer
{
    public function render()
    {

        NullosTheme::useLib("datatable");

        $model = $this->model;
        if (array_key_exists("serviceUri", $this->model)) {
            $serviceUri = $this->model['serviceUri'];
        } else {
            if (true === XConfig::get("Core.dualSite")) {
                $backUri = XConfig::get("Core.uriPrefixBackoffice");
                $serviceUri = $backUri . "/service/Ekom/ecp/api";
            } else {
                $serviceUri = "/service/Ekom/ecp/api";
            }
        }

        $sorts = [];
        if (array_key_exists("sort", $model)) {
            $sorts = $model['sort'];
        }

        $filters = [];
        if (array_key_exists("filters", $model)) {
            $filters = $model['filters'];
        }
        $title = $model['title'];
        $viewId = $model['viewId'];
        $rows = $model['rows'];
        $page = $model['page'];
        $nbPages = $model['nbPages'];
        $nbItems = $model['nbItems'];
        $nippChoices = $model['nippChoices'];
        $nipp = $model['nipp'];
        $listActions = $model['listActions'];
        $rowActions = $model['rowActions'];
        $headers = $model['headers'];


        $context = array_key_exists("context", $model) ? $model['context'] : [];


        $headersVisibility = [];
        if (array_key_exists("headersVisibility", $model)) {
            $headersVisibility = $model['headersVisibility'];
        }


        ob_start();

        ?>
        <div class="morphic-context" style="display:none"></div>
        <script>
            jqueryComponent.ready(function () {
                window.Morphic.context = <?php echo json_encode($context); ?>;
            });
        </script>
        <div class="x_title">
            <h2><?php echo $title; ?>
                <span class="badge bg-blue"><?php echo $nbItems; ?></span>
            </h2>
            <ul class="nav navbar-right panel_toolbox morphic-panel-toolbox">
                <?php foreach ($listActions as $action):
                    $classDisabled = $this->getClassDisabled($action);
                    ?>
                    <li
                            class="morphic morphic-action <?php echo $classDisabled; ?>"
                            data-name="<?php echo htmlspecialchars($action['name']); ?>"
                        <?php $this->displayConfirmAttributes($action); ?>
                            data-toggle="tooltip" data-placement="top"
                            title="<?php echo htmlspecialchars($action['label']); ?>"><a
                                class="collape-link"><i
                                    class="<?php echo $action['icon']; ?>"></i></a></li>
                <?php endforeach; ?>
            </ul>
            <div class="clearfix"></div>
        </div>
        <script>
            jqueryComponent.ready(function () {
                /**
                 * (dirty?) workaround to refresh the tooltip when the page is refreshed
                 */
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
        <div class="x_content">
            <!--        <div class="x_content table-responsive">-->
            <?php


            $r = MorphicBootstrap3GuiAdminHtmlTableRenderer::create()
                ->addHtmlAttributes("table", [
                    'data-view-id' => $viewId,
                    'data-page' => $page,
                    'data-nipp' => $nipp,
                    'data-service-uri' => $serviceUri,
                ])
                ->setSearchValues($filters)
                ->setHeadersDirection($sorts)
                ->setHeaders($headers)
                ->setHeadersVisibility($headersVisibility)
                ->setRows($rows);
            if ($rowActions) {
                $r->addColTransformer("_action", function ($v, $row) use ($rowActions) {
                    $action = array_shift($rowActions);
                    $classDisabled = $this->getClassDisabled($action);
                    ob_start();
                    ?>
                    <div class="btn-group" style="display: flex">
                        <a
                                type="button"
                                class="btn btn-default morphic morphic-row-action <?php echo $classDisabled; ?>"
                            <?php $this->displayAllAttributes($action, $v, $row); ?>
                        >
                            <i class="<?php echo htmlspecialchars($action['icon']); ?>"></i>
                            <?php echo $action['label']; ?>
                        </a>
                        <?php if ($rowActions): ?>
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                    aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <?php foreach ($rowActions as $action):
                                    $classDisabled = $this->getClassDisabled($action);
                                    ?>
                                    <li><a
                                                class="morphic morphic-row-action <?php echo $classDisabled; ?>"
                                            <?php $this->displayAllAttributes($action, $v, $row); ?>
                                        >
                                            <i class="<?php echo htmlspecialchars($action['icon']); ?>"></i>
                                            <?php echo $action['label']; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>

                    <?php
                    return ob_get_clean();
                });
            }


            $r->render();


            ?>


            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div style="margin-top: 20px;">
                        Affichage
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-xs dropdown-toggle"
                                    data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                <?php echo $nipp; ?> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <?php foreach ($nippChoices as $key => $label): ?>
                                    <li><a href="#" class="morphic-nipp morphic"
                                           data-nipp="<?php echo $key; ?>"><?php echo $label; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        / <?php echo $nbItems; ?> résultat(s)
                    </div>
                </div>
                <div class="col-md-4">

                    <nav aria-label="Page navigation" style="display: flex; justify-content: flex-end">
                        <ul class="pagination pagination-sm">
                            <li>
                                <a href="#" aria-label="First"
                                   class="morphic morphic-page"
                                   data-page="<?php echo 1; ?>"
                                >
                                    <i class="fa fa-angle-double-left morphic morphic-page"
                                       data-page="<?php echo 1; ?>"
                                    ></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" aria-label="Prev"
                                   class="morphic morphic-page"
                                   data-page="<?php echo($page - 1); ?>"
                                >
                                    <i class="fa fa-angle-left morphic morphic-page"
                                       data-page="<?php echo($page - 1); ?>"
                                    ></i>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $nbPages; $i++): ?>
                                <?php
                                if ($i === $page) {
                                    $sClass = ' class="active"';
                                } else {
                                    $sClass = '';
                                }
                                ?>
                                <li<?php echo $sClass; ?>><a href="#" class="morphic morphic-page"
                                                             data-page="<?php echo $i; ?>"
                                    ><?php echo $i; ?></a></li>
                            <?php endfor; ?>
                            <li>
                                <a href="#" aria-label="Next"
                                   class="morphic morphic-page"
                                   data-page="<?php echo($page + 1); ?>"
                                >
                                    <i
                                            class="fa fa-angle-right morphic morphic-page"
                                            data-page="<?php echo($page + 1); ?>"
                                    ></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" aria-label="Last"
                                   class="morphic morphic-page"
                                   data-page="<?php echo($nbPages); ?>"
                                >
                                    <i
                                            class="fa fa-angle-double-right morphic morphic-page"
                                            data-page="<?php echo($nbPages); ?>"
                                    ></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>


        <?php
        return ob_get_clean();
    }


    //--------------------------------------------
    //
    //--------------------------------------------
    private function displayAllAttributes(array $action, $value, array $row)
    {
        ?>
        data-name="<?php echo htmlspecialchars($action['name']); ?>"
        <?php if (array_key_exists("link", $action)): ?>
        href="<?php echo htmlspecialchars(call_user_func($action['link'], $row)); ?>"
    <?php endif;
        $this->displayConfirmAttributes($action);
    }


    private function displayConfirmAttributes(array $action)
    {
        if (array_key_exists("confirm", $action)): ?>
            data-confirm="<?php echo htmlspecialchars($action['confirm']); ?>"
        <?php endif; ?>
        <?php if (array_key_exists("confirmTitle", $action)): ?>
        data-confirm-title="<?php echo htmlspecialchars($action['confirmTitle']); ?>"
    <?php endif; ?>
        <?php if (array_key_exists("confirmOkBtn", $action)): ?>
        data-confirm-ok-text="<?php echo htmlspecialchars($action['confirmOkBtn']); ?>"
    <?php endif; ?>
        <?php if (array_key_exists("confirmCancelBtn", $action)): ?>
        data-confirm-cancel-text="<?php echo htmlspecialchars($action['confirmCancelBtn']); ?>"
    <?php endif; ?>
        <?php
    }


    private function getClassDisabled(array $action)
    {
        $classDisabled = "";
        if (array_key_exists("disabled", $action)) {
            $disabled = $action['disabled'];
            if (true === $disabled) {
                $classDisabled = "disabled";
            } elseif ("selection" === $disabled) {
                $classDisabled = "will-enable disabled";
            }
        }
        return $classDisabled;
    }

}