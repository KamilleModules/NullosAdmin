<?php


namespace Module\NullosAdmin\SokoForm\Renderer;


use Bat\StringTool;
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

            <?php $r->submitKey(); ?>
            <?php $r->submitButton(); ?>
        </form>
        <?php
    }

    //--------------------------------------------
    // MAIN METHODS
    //--------------------------------------------
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

    protected function renderChoiceList(array $model, array $preferences = [])
    {
        $type = $model['type'];
        if ('list' === $type) {

            $properties = array_key_exists("properties", $model) ? $model['properties'] : [];
            $readOnly = array_key_exists("readonly", $properties);
            $sDisabled = "";
            if (true === $readOnly) {
                $sDisabled = ' disabled="true"';
            }

            ?>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $model['label']; ?></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select <?php echo $sDisabled; ?> name="<?php echo htmlspecialchars($model['name']); ?>"
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

                </div>
            </div>
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
                    <?php $this->displayInputWidget($model, $inputType, $cssId); ?>
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

    protected function displayInputWidget($model, $inputType, $cssId)
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
                class="form-control col-md-7 col-xs-12"
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



