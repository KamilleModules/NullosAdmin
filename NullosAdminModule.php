<?php


namespace Module\NullosAdmin;


use Kamille\Module\KamilleModule;

class NullosAdminModule extends KamilleModule
{

    public function getDependencies()
    {
        return [
            "KamilleModules.UploadProfile",
        ];
    }

    protected function getPlanets()
    {
        return [
            'ling.Kamille',
            'ling.Models',
            'ling.ModelRenderers',
        ];
    }

}


