<?php


namespace Module\NullosAdmin\SokoForm\Control;


use SokoForm\Control\SokoChoiceControl;


/**
 * HOW TO
 * =========
 *
 *
 * $form
 * ->addControl(NullosSokoReactiveChoiceControl::create()
 *      ->setName("feature_value_id")
 *      ->setLabel('Feature value id')
 *      ->setProperties([
 *          "listenTo" => "feature_id",
 *          "service" => "back.reactive.feature_value",
 *          "defaultLabel" => "Veuillez choisir une feature d'abord",
 *      ])
 * )
 */
class NullosSokoReactiveChoiceControl extends SokoChoiceControl
{


    protected function getSpecificModel() // override me
    {

        $defaultLabel = "Please wait...";
        if (array_key_exists("defaultLabel", $this->properties)) {
            $defaultLabel = $this->properties['defaultLabel'];
        }

        return [
            "type" => "list",
            "choices" => [
                0 => $defaultLabel,
            ],
        ];
    }


}