<?php


namespace Module\NullosAdmin\SokoForm\Renderer;


use Bat\StringTool;
use Core\Services\Hooks;
use SokoForm\Form\SokoFormInterface;
use SokoForm\Renderer\SokoFormRenderer;
use Theme\NullosTheme;

class NullosBootstrapFormRenderer extends SokoFormRenderer
{
    public function __construct()
    {
        parent::__construct();
        $this->setNotificationRenderer(NullosBootstrapNotificationRenderer::create());
    }


    public static function displayForm(SokoFormInterface $form, $cssId = null)
    {

        if (null === $cssId) {
            $cssId = StringTool::getUniqueCssId("form-");
        }
        $r = NullosBootstrapFormRenderer::create()
            ->setForm($form); // form is a soko form instance
        $controlNames = array_keys($form->getControls());
        ?>


        <form <?php $r->formAttributes(); ?>
            <?php if ($cssId): ?>
                id="<?php echo $cssId; ?>"
            <?php endif; ?>
                data-parsley-validate=""
                class="form-horizontal form-label-left soko-form"
                novalidate=""
        >
            <?php $r->notifications(); ?>
            <?php
            $name = $form->getName();
            foreach ($controlNames as $col): ?>
                <?php if ($name !== $col): ?>
                    <?php $r->render($col); ?>
                <?php endif; ?>
            <?php endforeach; ?>




            <div class="dropzone"></div>


            <?php $r->submitKey(); ?>
            <?php $r->submitButton(); ?>
        </form>


        <script>
            jqueryComponent.ready(function () {
                $(".dropzone").dropzone({ url: "/file/post" });
            });
        </script>

        <?php
    }

    //--------------------------------------------
    // MAIN METHODS
    //--------------------------------------------
    protected function getRenderIdentifier(array $controlModel)
    {

        /**
         * This is the default algorithm that maps a control to
         * a renderer method name.
         * Feel free to override this method as you wish.
         */

        $ret = null;
        $className = $controlModel['class'];
        switch ($className) {
            /**
             * @todo-ling: this is a logic error,
             * Ekom should not appear at the Nullos module level
             */
            case "EkomSokoDateControl":
                $type = $controlModel['type'];
                $ret = "input-$type";
                break;
            case "NullosSokoReactiveChoiceControl":
                $type = $controlModel['type'];
                $ret = "choice-$type";
                break;
        }
        if ($ret) {
            return $ret;
        }
        return parent::getRenderIdentifier($controlModel);

    }


    public function render($controlName, array $preferences = [])
    {
        NullosTheme::useLib("form");
        parent::render($controlName, $preferences);
    }


    protected function renderInputText(array $model, array $preferences = [])
    {
        $this->doRenderInputControl($model, $preferences);
    }

    protected function renderInputTextarea(array $model, array $preferences = [])
    {
        $this->doRenderInputControl($model, $preferences);
    }

    protected function renderAutocompleteInputText(array $model, array $preferences = [])
    {
        $this->doRenderInputControl($model, $preferences);
    }

    protected function renderChoiceList(array $model, array $preferences = [])
    {
        $type = $model['type'];
        if ('list' === $type) {

            $cssId = StringTool::getUniqueCssId("select-");
            $properties = array_key_exists("properties", $model) ? $model['properties'] : [];
            $readOnly = (array_key_exists("readonly", $properties) && (true === $properties['readonly']));
            $sDisabled = "";
            if (true === $readOnly) {
                $sDisabled = ' disabled="true"';
            }

            $sErrorClass = "";
            if ($model['errors']) {
                $sErrorClass = "soko-error-container soko-active";
            }

            ?>
            <?php if (array_key_exists("listenTo", $properties)): ?>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $model['label']; ?></label>
                    <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback <?php echo $sErrorClass; ?>">
                        <select <?php echo $sDisabled; ?> name="<?php echo htmlspecialchars($model['name']); ?>"
                                                          id="<?php echo $cssId; ?>"
                                                          class="form-control has-feedback-left">
                            <?php foreach ($model['choices'] as $value => $label):
                                $sSel = ((string)$value === (string)$model['value']) ? ' selected="selected"' : '';
                                ?>
                                <option
                                    <?php echo $sSel; ?>
                                        value="<?php echo htmlspecialchars($value); ?>"><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>


                        <span class="fa fa-spinner form-control-feedback left" aria-hidden="true"></span>
                        <?php $this->doRenderError($model, $preferences); ?>
                    </div>
                </div>
                <?php

                //--------------------------------------------
                // HANDLING REACTIVE BEHAVIOUR
                // - NullosSokoReactiveChoiceControl
                //--------------------------------------------
                $listenTo = $properties['listenTo'];
                $service = $properties['service'];
                ?>
                <script>
                    jqueryComponent.ready(function () {
                        var jComponent = $("#<?php echo $cssId; ?>");
                        var jForm = jComponent.closest('form');
                        var jTarget = jForm.find('select[name="<?php echo $listenTo; ?>"]');
                        var api = ekomApi.inst();
                        jTarget
                            .off('change.nullosReactive')
                            .on('change.nullosReactive', function () {
                                var value = $(this).val();
                                api.utils.request("<?php echo $service; ?>", {
                                    feature_id: value
                                }, function (r) {
                                    /**
                                     * make the potential error message disappear (if using soko),
                                     * see soko doc and soko-form-error-removal-tool.js for more info
                                     *
                                     */
                                    jComponent.parent().removeClass('soko-active');
                                    jComponent.empty();
                                    for (var k in r) {
                                        var v = r[k];
                                        jComponent.append('<option value="' + k + '">' + v + '</option>');
                                    }

                                });
                            });
                    });
                </script>


            <?php else: ?>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $model['label']; ?></label>
                    <div class="col-md-6 col-sm-6 col-xs-12 <?php echo $sErrorClass; ?>">
                        <select <?php echo $sDisabled; ?> name="<?php echo htmlspecialchars($model['name']); ?>"
                                                          id="<?php echo $cssId; ?>"
                                                          class="form-control">
                            <?php foreach ($model['choices'] as $value => $label):
                                $sSel = ((string)$value === (string)$model['value']) ? ' selected="selected"' : '';
                                ?>
                                <option
                                    <?php echo $sSel; ?>
                                        value="<?php echo htmlspecialchars($value); ?>"><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>

                        <?php if (true === $readOnly): ?>
                            <input type="hidden" name="<?php echo htmlspecialchars($model['name']); ?>"
                                   value="<?php echo htmlspecialchars($model['value']); ?>"
                            >
                        <?php endif; ?>
                        <?php $this->doRenderError($model, $preferences); ?>
                    </div>
                </div>

            <?php endif; ?>
            <?php

        } else {
            throw new \Exception("not handled yet with type=$type");
        }
    }

    protected function renderChoiceBooleanList(array $model, array $preferences = [])
    {
        $sChecked = (1 === (int)$model['value']) ? "checked" : "";
        ?>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $model['label']; ?></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="">
                    <label class="switchery-label">
                        <input name="<?php echo htmlspecialchars($model['name']); ?>" type="checkbox"
                               class="js-switch" <?php echo $sChecked; ?>/>
                    </label>
                </div>
            </div>
        </div>
        <?php

    }


    protected function renderInputHidden(array $model, array $preferences = [])
    {
        $preferences['inputType'] = "hidden";
        $this->doRenderInputControl($model, $preferences);
    }


    protected function doRenderInputControl(array $model, array $preferences = [])
    {
        $attr = $this->getHtmlAtributesAsString($preferences);
        $cssId = StringTool::getUniqueCssId("f");
        $type = $model['type'];


        $inputType = "text";
        if (array_key_exists("inputType", $preferences)) {
            $inputType = $preferences['inputType'];
        }
        ?>


        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12"
                   for="<?php echo $cssId; ?>"><?php $this->doRenderLabel($model, $preferences); ?>
                <?php if ($this->isRequired($model)): ?>
                    <span class="required">*</span>
                <?php endif; ?>
            </label>

            <div class="col-md-6 col-sm-6 col-xs-12 soko-error-container
<?php if ($model['errors']): ?>
<?php echo " soko-active"; ?>
    <?php endif; ?>
">


                <?php if ("textarea" === $type): ?>
                    <?php $this->displayTextareaWidget($model, $inputType, $cssId); ?>
                <?php else: ?>
                    <?php if (array_key_exists("autocomplete", $model)):
                        /**
                         * In this case, we display 2 inputs:
                         * - one for the visual cue for the user
                         * - the real input used to hold the real value that we want
                         */
                        NullosTheme::useLib("jqueryUi");

                        $model2 = $model;
                        $model2['name'] .= "-autocomplete";
                        $cssId2 = StringTool::getUniqueCssId("f2");


                        $autocompleteOptions = $model2['autocompleteOptions'];
                        $action = $autocompleteOptions['action'];


                        ?>
                        <div class="form-group auto-complete-container">
                            <?php

                            $value2 = "";
                            Hooks::call("NullosAdmin_SokoForm_NullosBootstrapRenderer_AutocompleteInitialValue", $value2, $action, $model['value']);
                            $model2['value'] = $value2;


                            $extra = 'placeholder="Search..."';
                            if ($this->isReadOnly($model)) {
                                $extra = '';
                            }
                            $this->displayInputWidget($model2, "text", $cssId2, "form-control has-feedback-left", $extra); // visual control for holding label;
                            ?>
                            <?php

                            $this->displayInputWidget($model, "text", $cssId, "form-control-feedback left", 'style="border-left: none;border-top: none;border-bottom: none;top: -2px;"'); // hidden
                            ?>
                            <!--                            <span class="fa fa-cloud-download form-control-feedback left" aria-hidden="true"></span>-->
                        </div>


                        <script>
                            jqueryComponent.ready(function () {

                                /**
                                 * This is basic jqueryUi autocomplete
                                 * http://jqueryui.com/autocomplete/
                                 **/
                                var autoCompleteOptions = <?php echo json_encode($autocompleteOptions, \JSON_FORCE_OBJECT); ?>;
                                autoCompleteOptions.select = function (event, ui) {
                                    var jInput = $("#<?php echo $cssId; ?>");
                                    jInput.val(ui.item.id);
                                    var jInput2 = $("#<?php echo $cssId2; ?>");
                                    jInput2.val(ui.item.label);
                                    /**
                                     * make the potential error message disappear (if using soko),
                                     * see soko doc and soko-form-error-removal-tool.js for more info
                                     *
                                     */
                                    jInput.closest(".soko-error-container").removeClass('soko-active');
                                    return false;
                                };


                                $("#<?php echo $cssId2; ?>").autocomplete(autoCompleteOptions);
                            });
                        </script>


                    <?php else:
                    $properties = $model['properties'];
                    $lang = (array_key_exists("lang", $properties)) ? $properties["lang"] : "en";
                    ?>
                    <?php if (array_key_exists("date", $properties) && true === $properties['date']): ?>

                    <?php if (array_key_exists("useTime", $properties) && true === $properties['useTime']): ?>

                    <input type="text" name="<?php echo htmlspecialchars($model['name']); ?>"
                           class="form-control has-feedback-left" id="<?php echo $cssId; ?>"
                            value="<?php echo htmlspecialchars($model['value']); ?>"
                        <?php if (null !== $model['label']): ?>
                            placeholder="<?php echo htmlspecialchars($model['label']); ?>"
                        <?php endif; ?>
                    >
                        <span class="fa fa-calendar-o form-control-feedback left"></span>
                        <script>
                            jqueryComponent.ready(function () {

                                //$('#<?php //echo $cssId; ?>//').timepicker($.timepicker.regional['fr']);


                                // http://trentrichardson.com/examples/timepicker/
                                moment.locale('<?php echo $lang; ?>'); // see init in custom.js
                                $('#<?php echo $cssId; ?>').datetimepicker({
                                    dateFormat: 'yy-mm-dd',
                                    timeFormat: 'hh:mm:ss'
                                });
                            });
                        </script>
                    <?php else: ?>


                    <input type="text" name="<?php echo htmlspecialchars($model['name']); ?>"
                           class="form-control has-feedback-left" id="<?php echo $cssId; ?>"
                           value="<?php echo htmlspecialchars($model['value']); ?>"
                        <?php if (null !== $model['label']): ?>
                            placeholder="<?php echo htmlspecialchars($model['label']); ?>"
                        <?php endif; ?>
                    >
                        <span class="fa fa-calendar-o form-control-feedback left"></span>
                        <script>
                            jqueryComponent.ready(function () {

                                moment.locale('<?php echo $lang; ?>'); // see init in custom.js
                                $('#<?php echo $cssId; ?>').daterangepicker({
                                    singleDatePicker: true,
                                    locale: {
                                        format: 'YYYY-MM-DD'
                                    },
                                    singleClasses: "picker_1"
                                });
                            });
                        </script>
                    <?php endif; ?>
                        <!-- end of date -->


                    <?php else: ?>
                        <?php $this->displayInputWidget($model, $inputType, $cssId); ?>
                    <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>

                <?php $this->doRenderError($model, $preferences); ?>
            </div>
        </div>


        <?php
    }


    public function submitButton(array $preferences = [])
    {
        $label = $this->getPreference("label", $preferences, "Submit");
        $attributes = $this->getPreference("attributes", $preferences, []);
        unset($attributes['class']);
        ?>
        <div class="ln_solid"></div>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success"><?php echo $label; ?></button>
            </div>
        </div>
        <?php
    }





    //--------------------------------------------
    //
    //--------------------------------------------
    protected function doRenderLabel(array $model, array $preferences = [])
    {
        if (null !== $model['label']) {
            echo $model['label'];
        }
    }


    protected function doRenderError(array $model, array $preferences = [])
    {
        if ($model['errors']): ?>
            <div class="soko-error-parent">
                <?php foreach ($model['errors'] as $errorMsg): ?>
                    <div class="soko-error"
                         data-name="<?php echo htmlspecialchars($model['name']); ?>"><?php echo $errorMsg; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif;
    }

    protected function isRequired(array $model)
    {
        if (
            array_key_exists("required", $model['properties']) &&
            true === $model['properties']['required']
        ) {
            return true;
        }
        return false;
    }

    protected function isDisabled(array $model)
    {
        if (
            array_key_exists("disabled", $model['properties']) &&
            true === $model['properties']['disabled']
        ) {
            return true;
        }
        return false;
    }

    protected function isReadOnly(array $model)
    {
        if (
            array_key_exists("readonly", $model['properties']) &&
            true === $model['properties']['readonly']
        ) {
            return true;
        }
        return false;
    }

    protected function displayInputWidget($model, $inputType, $cssId, $cssClass = null, $extra = null)
    {
        ?>
        <input
            <?php if ($this->isRequired($model)): ?>
                required="required"
            <?php endif; ?>
            <?php if ($this->isDisabled($model)): ?>
                disabled
            <?php endif; ?>
            <?php if ($this->isReadOnly($model)): ?>
                readonly
            <?php endif; ?>
                type="<?php echo $inputType; ?>"

                name="<?php echo htmlspecialchars($model['name']); ?>"
                value="<?php echo htmlspecialchars($model['value']); ?>"
                id="<?php echo $cssId; ?>"
            <?php if ($cssClass): ?>
                class="<?php echo $cssClass; ?>"
            <?php else: ?>
                class="form-control col-md-7 col-xs-12"
            <?php endif; ?>
            <?php if ($extra): ?>
                <?php echo $extra; ?>
            <?php endif; ?>
        >
        <?php
    }


    protected function displayTextareaWidget($model, $inputType, $cssId)
    {
        ?>
        <textarea
            <?php if ($this->isRequired($model)): ?>
                required="required"
            <?php endif; ?>
            <?php if ($this->isDisabled($model)): ?>
                disabled
            <?php endif; ?>
            <?php if ($this->isReadOnly($model)): ?>
                readonly
            <?php endif; ?>
                type="<?php echo $inputType; ?>"
                name="<?php echo htmlspecialchars($model['name']); ?>"
                id="<?php echo $cssId; ?>"
                class="form-control col-md-7 col-xs-12"
        ><?php echo $model['value']; ?></textarea>
        <?php
    }

}



