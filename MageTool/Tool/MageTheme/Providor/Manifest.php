<?php

require_once 'MageTool/Tool/MageTheme/Provider/Package.php';
require_once 'MageTool/Tool/MageTheme/Provider/Theme.php';

/**
 * @see Zend_Tool_Framework_Manifest_ProviderManifestable
 */
require_once 'Zend/Tool/Framework/Manifest/ProviderManifestable.php';

class MageTool_Tool_MageTheme_Provider_Manifest 
    implements Zend_Tool_Framework_Manifest_ProviderManifestable, Zend_Tool_Framework_Manifest_ActionManifestable
{
    public function getProviders()
    {
        $providers = array(
                new MageTool_Tool_MageTheme_Provider_Package(),
                new MageTool_Tool_MageTheme_Provider_Theme(),
            );

        return $providers;
    }

    public function getActions()
    {
        $actions = array();

        return $actions;
    }
}