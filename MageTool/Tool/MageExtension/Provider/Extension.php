<?php
require_once 'MageTool/Tool/MageExtension/Profile.php';
/**
 * @see MageTool_Tool_MageExtension_Provider_Abstract
 */
require_once 'MageTool/Tool/MageExtension/Provider/Abstract.php';
require_once 'MageTool/Tool/MageExtension/Provider/Exception.php';
/**
 * undocumented class
 *
 * @package default
 * @author Alistair Stead
 **/
class MageTool_Tool_MageExtension_Provider_Extension extends MageTool_Tool_MageExtension_Provider_Abstract
    implements Zend_Tool_Framework_Provider_Pretendable
{
    /**
     * Define the name of the provider
     *
     * @return string
     * @author Alistair Stead
     **/
    public function getName()
    {
        return 'MageExtension';
    }
    
    /**
     * Clear the magento cache
     *
     * @return void
     * @author Alistair Stead
     **/
    public function create($vendor, $name, $pool = 'local', $fileOfProfile = null)
    {
        $this->_bootstrap();
        $this->_chApplicationDir();
        $path = getcwd();
        
        $path = sprintf("%s/app/code/%s/%s/%s", $path, $pool, $vendor, $name);

        if (file_exists($path)) {
            throw new MageTool_Tool_MageExtension_Provider_Exception(
                "An Extension {$extensionName} already exists in the {$codePool} code pool for the vendor {$vendorName}."
                );
        } else {
            try {
                mkdir($path, 0755, true);
            } catch (Exception $e) {
                throw new MageTool_Tool_MageExtension_Provider_Exception(
                    "Unable to create Extension {$extensionName} directory."
                    );
            }
        }

        $profileData = null;

        if ($fileOfProfile != null && file_exists($fileOfProfile)) {
            $profileData = file_get_contents($fileOfProfile);
        }

        if ($profileData == '') {
            $profileData = $this->_getDefaultProfile();
        }

        $newProfile = new MageTool_Tool_MageExtension_Profile(array(
            'projectDirectory' => $path,
            'profileData' => $profileData
            ));

        $newProfile->loadFromData();

        $response = $this->_registry->getResponse();
        
        $response->appendContent('Creating extension at ' . $path);
        $response->appendContent('Note: ', array('separator' => false, 'color' => 'yellow'));
        $response->appendContent(
            'This command created a new extension, '
            . 'you will now need to create a config file to enable this module in app/etc/modules');

        foreach ($newProfile->getIterator() as $resource) {
            $resource->create();
        }
    }
    
    protected function _getDefaultProfile()
    {
        $data = <<<EOS
<?xml version="1.0" encoding="UTF-8"?>
<?xml version="1.0" encoding="UTF-8"?>
<extensionProfile type="default" version="1.10">
    <extensionDirectory>
        <BlockDirectory></BlockDirectory>
        <ControllerDirectory></ControllerDirectory>
        <controllersDirectory></controllersDirectory>
        <etcDirectory>
            <file filesystemName="config.xaml" defaultContentCallback="MageTool_Tool_MageExtension_Provider_Extension::getDefaultConfigContents"/>
            <file filesystemName="system.xaml" defaultContentCallback="MageTool_Tool_MageExtension_Provider_Extension::getDefaultSystemContents"/>
        </etcDirectory>
        <HelperDiroectory></HelperDiroectory>
        <ModelDirectory></ModelDirectory>
        <sqlDirectory></sqlDirectory>
    </extensionDirectory>
</extensionProfile>
EOS;
        return $data;
    }
    
    public static function getDefaultConfigContents($caller = null)
    {
        $projectDirResource = $caller->getResource()->getProfile()->search('projectDirectory');
        
        return <<< EOS
<?xml version="1.0"?>
<config>
    <modules>
        <{$vendor}_{$name}>
             <version>0.0.1</version>
        </{$vendor}_{$name}>
    </modules>

    <global>
        <models>
        </models>
        <blocks>
            <{$name}>
            </{$name}>
        </blocks>
        <helpers>
            <{$name}>
            </{$name}>
        </helpers>
    </global>
    <default>
    </default>
</config>
EOS;
    }
}