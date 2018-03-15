<?php


namespace Module\NullosAdmin\SokoForm\Renderer;


use Bat\StringTool;
use Core\Services\A;
use Core\Services\Hooks;
use Module\Ekom\Utils\E;
use Module\NullosAdmin\Helper\ConfigHelperInterface;
use Module\NullosAdmin\Helper\TrumbowygConfigHelper;
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


    public static function displayForm(SokoFormInterface $form, $cssId = null, array $options = [])
    {

        NullosTheme::useLib("form");


        $options = array_replace([
            "description" => null,
            "isFakeForm" => false,
            "submitBtnLabel" => "Submit",
        ], $options);
        $submitBtnPreferences = [
            "label" => $options['submitBtnLabel'],
        ];
        $isFakeForm = $options['isFakeForm'];
        $description = $options['description'];


        if (null === $cssId) {
            $cssId = StringTool::getUniqueCssId("form-");
        }
        $r = NullosBootstrapFormRenderer::create()
            ->setForm($form); // form is a soko form instance
        $controlNames = array_keys($form->getControls());
        ?>


        <?php if (false === $isFakeForm): ?>
        <form <?php $r->formAttributes(); ?>
        <?php if ($cssId): ?>
            id="<?php echo $cssId; ?>"
        <?php endif; ?>
        data-parsley-validate=""
        class="form-horizontal form-label-left soko-form"
        novalidate=""
        >
        <?php $r->notifications(); ?>
    <?php endif; ?>


        <?php if (null !== $description): ?>
        <?php echo $description; ?>
    <?php endif; ?>


        <?php
        $name = $form->getName();


        $groups = $form->getGroups();
        if (empty($groups)) {
            foreach ($controlNames as $col) {
                if ($name !== $col) {
                    $r->render($col);
                }
            }
        } else {

            foreach ($groups as $group) {
                $label = $group['label'];
                $controls = $group['controls'];
                ?>
                <fieldset>
                    <legend><?php echo $label; ?></legend><?php
                    foreach ($controls as $col) {
                        if ($name !== $col) {
                            $r->render($col);
                        }
                    }
                    ?></fieldset>
                <?php
            }
        }


        $r->submitKey();

        ?>

        <?php if (false === $isFakeForm): ?>
        <?php $r->submitButton($submitBtnPreferences); ?>
        </form>
    <?php endif; ?>

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

    protected function renderFileSafeUpload(array $model, array $preferences = [])
    {

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

        $profileId = $model['profileId'];
        $payload = $model['payload'];


        ?>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $model['label']; ?></label>
            <div class="col-md-9 col-sm-9 col-xs-12 form-group has-feedback <?php echo $sErrorClass; ?>">
                <div class="dropzone"></div>
                <script>
                    jqueryComponent.ready(function () {
                        $(".dropzone").dropzone({
                            url: "<?php echo E::getEcpUri("upload_handler", [
                                'profile_id' => $profileId,
                                'payload' => $payload,
                            ]); ?>"
                        });
                    });
                </script>


                <?php $this->doRenderError($model, $preferences); ?>
            </div>
        </div>
        <?php
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
                    <div class="col-md-9 col-sm-9 col-xs-12 form-group has-feedback <?php echo $sErrorClass; ?>">
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


                        var api = nullosApi.inst();
                        jTarget
                            .off('change.nullosReactive')
                            .on('change.nullosReactive', function () {
                                var value = $(this).val();
                                api.request("<?php echo $service; ?>", {
                                    parentIdentifier: value
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

                        // trigger at init for free (let the child initialize itself...)
                        jTarget.trigger('change');
                    });
                </script>


            <?php else: ?>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $model['label']; ?></label>
                    <div class="col-md-9 col-sm-9 col-xs-12 <?php echo $sErrorClass; ?>">
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
            <?php echo $this->renderExtraLink($properties); ?>
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
            <div class="col-md-9 col-sm-9 col-xs-12">
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


    protected function renderComboboxChoice(array $model, array $preferences = [])
    {

        $cssId = StringTool::getUniqueCssId("combobox-");
        $cssId2 = StringTool::getUniqueCssId("sortbox-");
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


        $useSortBox = (array_key_exists("useSortBox", $properties) && true === $properties['useSortBox']) ? true : false;

        $comboBoxName = $model['name'];
        $sortBoxName = $model['name'];
        if (true === $useSortBox) {
            $comboBoxName = null;
        }


        /**
         * if useSortBox, it's an array of values
         */
        $comboboxValue = $model['value'];
        if (!is_array($comboboxValue)) {
            $comboboxValue = [];
        }

        ?>


        <style>
            .custom-combobox {
                position: relative;
                display: inline-block;
                width: 95%;
            }

            .custom-combobox-toggle {
                position: absolute;
                top: 0;
                bottom: 0;
                margin-left: -1px;
                padding: 0;
            }

            .custom-combobox-input {
                margin: 0;
                padding: 5px 10px;
                width: 100%;
            }

            .combobox-sortbox {
                min-height: 130px;
                border: 1px solid #dbdbdb;
            }

            .combobox-sortbox .combobox-sort-item {
                cursor: move;
            }

            .combobox-sortbox .combobox-sort-item.focused {
                background: #2759c2;
                color: white;
            }
        </style>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $model['label']; ?></label>
            <div class="col-md-9 col-sm-9 col-xs-12 <?php echo $sErrorClass; ?>">
                <div class="ui-widget">
                    <select <?php echo $sDisabled; ?>

                        <?php if ($comboBoxName): ?>
                            name="<?php echo htmlspecialchars($comboBoxName); ?>"
                        <?php endif; ?>

                            id="<?php echo $cssId; ?>"
                            class="form-control">
                        <?php foreach ($model['choices'] as $value => $label):
                            if ($useSortBox) {
                                $sSel = (in_array($value, $comboboxValue, true)) ? ' selected="selected"' : '';
                            } else {
                                $sSel = ((string)$value === (string)$model['value']) ? ' selected="selected"' : '';
                            }
                            ?>
                            <option
                                <?php echo $sSel; ?>
                                    value="<?php echo htmlspecialchars($value); ?>"><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php $this->doRenderError($model, $preferences); ?>

            </div>
        </div>
        <?php echo $this->renderExtraLink($properties); ?>

        <?php if (true === $useSortBox):
        $sortBoxValues = [];
        foreach ($comboboxValue as $value) {
            $label = $model['choices'][$value];
            $sortBoxValues[$value] = $label;
        }

        ?>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
            <div class="col-md-9 col-sm-9 col-xs-12 <?php echo $sErrorClass; ?>">
                <div
                        id="<?php echo $cssId2; ?>"
                        class="form-control combobox-sortbox">
                    <?php foreach ($sortBoxValues as $value => $label): ?>
                        <div class="combobox-sort-item"><input type="hidden" name="<?php echo $sortBoxName ?>[]"
                                                               value="<?php echo $value; ?>"><?php echo $label; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="row action-buttons">
                    <div class="col-sm-4">
                        <button type="button" class="move_up btn btn-block"><i
                                    class="glyphicon glyphicon-arrow-up"></i></button>
                    </div>
                    <div class="col-sm-4">
                        <button type="button" class="move_down btn btn-block col-sm-4"><i
                                    class="glyphicon glyphicon-arrow-down"></i></button>
                    </div>
                    <div class="col-sm-4">
                        <button type="button" class="remove btn btn-block col-sm-4"><i
                                    class="glyphicon glyphicon-remove"></i></button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>


        <script>

            jqueryComponent.ready(function () {

                var jSortBox = $('#<?php echo $cssId2; ?>');
                var jSortActionButtons = jSortBox.next(".action-buttons");
                var jCombobox = $("#<?php echo $cssId; ?>");


                function onComboBoxSelect(value, text) {
                    <?php if(true === $useSortBox): ?>


                    if ('unique') {
                        var jInput = jSortBox.find('input[value="' + value + '"]');
                        if (jInput.length) {
                            jSortBox.find('.combobox-sort-item').removeClass("focused");
                            jInput.parent().addClass("focused");
                            return;
                        }
                    }

                    var sItem = '<div class="combobox-sort-item focused"><input type="hidden" name="<?php echo $sortBoxName?>[]" value="' + value + '">' + text + '</div>';
                    jSortBox.find('.combobox-sort-item').removeClass("focused");
                    jSortBox.append(sItem);

                    <?php endif; ?>
                }

                jSortBox.sortable();


                jSortBox.on('click.sortBox1', function (e) {
                    var jTarget = $(e.target);
                    if (jTarget.hasClass('combobox-sort-item')) {
                        jSortBox.find('.combobox-sort-item').removeClass("focused");
                        jTarget.addClass("focused");
                    }
                });


                jSortActionButtons.on('click.sortBox2', 'button', function (e) {
                    var jFocused = jSortBox.find('.focused');
                    if (jFocused.length) {

                        var jTarget = $(e.target);
                        if (jTarget.hasClass('move_up')) {
                            var jPrev = jFocused.prev('.combobox-sort-item');
                            if (jPrev.length) {
                                jPrev.before(jFocused);
                            }
                        }
                        else if (jTarget.hasClass('move_down')) {
                            var jNext = jFocused.next('.combobox-sort-item');
                            if (jNext.length) {
                                jNext.after(jFocused);
                            }
                        }
                        else if (jTarget.hasClass('remove')) {
                            jFocused.remove();
                        }
                    }
                });


                $.widget("custom.combobox", {
                    _create: function () {
                        this.wrapper = $("<span>")
                            .addClass("custom-combobox")
                            .insertAfter(this.element);

                        this.element.hide();
                        this._createAutocomplete();
                        this._createShowAllButton();
                    },

                    _createAutocomplete: function () {
                        var selected = this.element.children(":selected"),
                            value = selected.val() ? selected.text() : "";

                        var zis = this;

                        this.input = $("<input>")
                            .appendTo(this.wrapper)
                            .val(value)
                            .attr("title", "")
                            .addClass("custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left")
                            .autocomplete({
                                delay: 0,
                                minLength: 0,
                                source: $.proxy(this, "_source")
                            })
                            .tooltip({
                                classes: {
                                    "ui-tooltip": "ui-state-highlight"
                                }
                            });

                        this._on(this.input, {
                            autocompleteselect: function (event, ui) {
                                ui.item.option.selected = true;
                                var value = ui.item.option.value;
                                var text = ui.item.option.text;
                                onComboBoxSelect(value, text);
                            },

                            autocompletechange: "_removeIfInvalid"
                        });
                    },

                    _createShowAllButton: function () {
                        var input = this.input,
                            wasOpen = false;

                        $("<a>")
                            .attr("tabIndex", -1)
                            // .attr("title", "Show All Items")
                            // .tooltip()
                            .appendTo(this.wrapper)
                            .button({
                                icons: {
                                    primary: "ui-icon-triangle-1-s"
                                },
                                text: false
                            })
                            .removeClass("ui-corner-all")
                            .addClass("custom-combobox-toggle ui-corner-right")
                            .on("mousedown", function () {
                                wasOpen = input.autocomplete("widget").is(":visible");
                            })
                            .on("click", function () {
                                input.trigger("focus");

                                // Close if already visible
                                if (wasOpen) {
                                    return;
                                }

                                // Pass empty string as value to search for, displaying all results
                                input.autocomplete("search", "");
                            });
                    },

                    _source: function (request, response) {
                        var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
                        response(this.element.children("option").map(function () {
                            var text = $(this).text();
                            if (this.value && (!request.term || matcher.test(text)))
                                return {
                                    label: text,
                                    value: text,
                                    option: this
                                };
                        }));
                    },

                    _removeIfInvalid: function (event, ui) {

                        // Selected an item, nothing to do
                        if (ui.item) {
                            return;
                        }

                        // Search for a match (case-insensitive)
                        var value = this.input.val(),
                            valueLowerCase = value.toLowerCase(),
                            valid = false;
                        this.element.children("option").each(function () {
                            if ($(this).text().toLowerCase() === valueLowerCase) {
                                this.selected = valid = true;
                                return false;
                            }
                        });

                        // Found a match, nothing to do
                        if (valid) {
                            return;
                        }

                        // Remove invalid value
                        this.input
                            .val("")
                            .attr("title", value + " didn't match any item")
                            .tooltip("open");
                        this.element.val("");
                        this._delay(function () {
                            this.input.tooltip("close").attr("title", "");
                        }, 2500);
                        this.input.autocomplete("instance").term = "";
                    },

                    _destroy: function () {
                        this.wrapper.remove();
                        this.element.show();
                    }
                });


                jCombobox.combobox();

            });


        </script>
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

        $properties = (array_key_exists("properties", $model)) ? $model['properties'] : [];
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

            <div class="col-md-9 col-sm-9 col-xs-12 soko-error-container
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

        <?php echo $this->renderExtraLink($properties); ?>

        <?php
    }


    protected function renderFree(array $model, array $preferences = [])
    {

        $properties = $model['properties'];
        $html = $properties['html'];
        $belowLabel = (array_key_exists("belowLabel", $properties) && true === $properties['belowLabel']) ? true : false;
        ?>

        <?php if (true === $belowLabel): ?>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $model['label']; ?></label>
        </div>
        <div class="form-group">
            <?php echo $html; ?>
        </div>
    <?php else: ?>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $model['label']; ?></label>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <?php echo $html; ?>
            </div>
        </div>
    <?php endif; ?>


        <?php

    }


    public function submitButton(array $preferences = [])
    {
        $label = $this->getPreference("label", $preferences, "Submit");
        $labelUpdate = $this->getPreference("labelUpdate", $preferences, "Submit and update");
        $attributes = $this->getPreference("attributes", $preferences, []);
        unset($attributes['class']);
        ?>
        <div class="ln_solid"></div>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success"><?php echo $label; ?></button>
                <?php if (array_key_exists('form', $_GET) && 1 === count($_GET)): ?>
                    <button name="submit-and-update" value="1" type="submit"
                            class="btn btn-info"><?php echo $labelUpdate; ?></button>
                <?php endif; ?>
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

        $properties = (array_key_exists('properties', $model)) ? $model['properties'] : [];

        $placeHolder = array_key_exists("placeholder", $model) ? $model['placeholder'] : null;

        $useLeftBox = false;
        $leftBoxText = "";
        $leftBoxIcon = "";
        if (array_key_exists('leftBoxText', $properties)) {
            $leftBoxText = $properties['leftBoxText'];
            if (null === $cssClass) {
                $cssClass = '';
            }
            $useLeftBox = true;
            $cssClass .= 'form-control has-feedback-left';
        }

        $infoBox = (array_key_exists('info', $properties)) ? $properties['info'] : [];
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
            <?php if ($placeHolder): ?>
                placeholder="<?php echo htmlspecialchars($placeHolder); ?>"
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

        <!--        <span class="fa fa-calendar-o form-control-feedback left"></span>-->
        <?php if ($leftBoxText || $leftBoxIcon): ?>
        <span class="<?php echo $leftBoxIcon; ?> form-control-feedback left left-box"><?php echo $leftBoxText; ?></span>
    <?php endif; ?>
        <div class="clearfix"></div>

        <?php if ($infoBox):
        if (!is_array($infoBox)) {
            $infoBox = [
                'type' => 'warning',
                'text' => $infoBox,
            ];
        }
        ?>
        <div class="alert alert-<?php echo $infoBox['type']; ?> alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">×</span>
            </button>
            <?php echo $infoBox['text']; ?>
        </div>
    <?php endif; ?>

        <?php
    }


    protected function displayTextareaWidget($model, $inputType, $cssId)
    {

        $properties = $model['properties'];
        $wysiwyg = (array_key_exists('wysiwyg', $properties)) ? $properties['wysiwyg'] : false;


        ?>

        <?php if ($wysiwyg): ?>
        <div class="alert alert-info alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">×</span>
            </button>
            Conseils pour utiliser l'éditeur ci-dessous:
            <ul>
                <li>Pour faire des retours à la ligne, utilisez SHIFT+RETURN</li>
                <li>Pour créer un nouveau paragraphe, utilisez RETURN</li>
            </ul>
        </div>
    <?php endif; ?>


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
                class="form-control col-md-7 col-xs-12 an-editor-wrapper"
        ><?php echo $model['value']; ?></textarea>


        <?php if (true === $wysiwyg):
        $wysiwygConfig = [];
        if (array_key_exists("wysiwygConfig", $properties)) {
            $config = $properties['wysiwygConfig'];
            if ($config instanceof ConfigHelperInterface) {
                $wysiwygConfig = $config->getConfig();
            }
        } else {
            $wysiwygConfig = TrumbowygConfigHelper::create()->prepareByPreset("minimal")->getConfig();
        }

        $hasEmoji = TrumbowygConfigHelper::has("emoji", $wysiwygConfig);

        ?>

        <script>
            jqueryComponent.ready(function () {

                <?php if(true === $hasEmoji): ?>
                emojify.setConfig({
                    img_dir: '//cdnjs.cloudflare.com/ajax/libs/emojify.js/1.1.0/images/basic/'
                });
                <?php endif; ?>

                $('#<?php echo $cssId; ?>').trumbowyg(<?php echo json_encode($wysiwygConfig); ?>);


                <?php if(true === $hasEmoji): ?>
                emojify.run();
                $('.trumbowyg-editor').on('input propertychange', function () {
                    emojify.run();
                });
                <?php endif; ?>


            });
        </script>
    <?php endif; ?>
        <?php
    }


    protected function getExtraLink(array $extraLink)
    {
        return array_replace([
            'color' => '#498dcb',
            'link' => '#',
            'icon' => null,
            'isExternal' => true,
            'text' => "just a link",

        ], $extraLink);
    }

    protected function renderExtraLink(array $properties)
    {
        if (array_key_exists("extraLink", $properties)):
            $extraLink = $this->getExtraLink($properties['extraLink']);
            $external = $extraLink['isExternal'];
            ?>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <a
                        <?php if ($external): ?>
                            target="_blank"
                        <?php endif; ?>
                            class="" style="white-space: nowrap; color: <?php echo $extraLink['color']; ?>"
                            href="<?php echo htmlspecialchars($extraLink['link']); ?>">

                        <?php if ($extraLink['icon']): ?>
                            <i class="<?php echo $extraLink['icon']; ?>"></i>
                        <?php endif; ?>

                        <?php echo $extraLink['text']; ?>

                        <?php if ($external): ?>
                        <i class="fa fa-external-link"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif;
    }

}



