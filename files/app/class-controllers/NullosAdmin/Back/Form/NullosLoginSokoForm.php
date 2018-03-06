<?php


namespace Controller\NullosAdmin\Back\Form;


use Bat\UriTool;
use Kamille\Architecture\Response\Web\RedirectResponse;
use Module\NullosAdmin\Authenticate\User\NullosUserHelper;
use SokoForm\Control\SokoInputControl;
use SokoForm\Form\SokoForm;
use SokoForm\Form\SokoFormInterface;
use SokoForm\ValidationRule\SokoEmailValidationRule;

class NullosLoginSokoForm
{


    /**
     * @return SokoFormInterface
     */
    public static function getPreparedForm(&$response = null)
    {
        $form = self::getForm();
        $response = self::prepareForm($form);
        return $form;
    }


    public static function getForm()
    {


        $form = SokoForm::create();
        $form->setName("nullosLoginForm")
            //--------------------------------------------
            // CONTROLS
            //--------------------------------------------
            ->addControl(SokoInputControl::create()
                ->setName("email")
                ->setLabel('Email')
                ->setPlaceholder('Email')
            )
            ->addControl(SokoInputControl::create()
                ->setName("pass")
                ->setLabel('Mot de passe')
                ->setPlaceholder('Mot de passe')
            );
        $form->addValidationRule("email", SokoEmailValidationRule::create());
        return $form;
    }


    public static function prepareForm(SokoFormInterface $form)
    {
        $response = null;
        $form->process(function ($data) use ($form, &$response) {
            $errorType = 0;
            $isConnected = NullosUserHelper::connectDbUser($data['email'], $data['pass'], $errorType);
            if (false === $isConnected) {
                $form->addNotification("No active user found with the given credentials", "error");
            } else {
                /**
                 * Refresh the page
                 */
                $response = RedirectResponse::create(UriTool::uri(null, [], true, true));
            }
        });
        return $response;
    }

}