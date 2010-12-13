<?php
require_once 'MageTool/Tool/MageTheme/Profile.php';
/**
 * @see MageTool_Tool_MageExtension_Provider_Abstract
 */
require_once 'MageTool/Tool/MageTheme/Provider/Abstract.php';
require_once 'MageTool/Tool/MageTheme/Provider/Exception.php';
/**
 * undocumented class
 *
 * @package default
 * @author Alistair Stead
 **/
class MageTool_Tool_MageTheme_Provider_Theme extends MageTool_Tool_MageTheme_Provider_Abstract
    implements Zend_Tool_Framework_Provider_Pretendable
{
    /**
     * The package name under which the theme should be created
     *
     * @var string
     **/
    protected $package;
    
    /**
     * The name of the theme to be created
     *
     * @var string
     **/
    protected $name;
    
    /**
     * Define the name of the provider
     *
     * @return string
     * @author Alistair Stead
     **/
    public function getName()
    {
        return 'MageTheme';
    }
    
    /**
     * Clear the magento cache
     *
     * @return void
     * @author Alistair Stead
     **/
    public function create($package, $name, $area = 'frontend', $fileOfProfile = null)
    {
        $this->_bootstrap();
        $this->_chApplicationDir();
        $path = getcwd();
        
        $this->package = strtolower($package);
        $this->name = strtolower($name);
        $this->pool = strtolower($pool);
        
        $path = sprintf("%s/app/design/%s/%s/%s", $path, $area, $this->package, $this->name);

        if (file_exists($path)) {
            throw new MageTool_Tool_MageExtension_Provider_Exception(
                "A Theme {$this->name} already exists in the {$this->package} package in the {$this->area} area."
                );
        } else {
            try {
                mkdir($path, 0755, true);
            } catch (Exception $e) {
                throw new MageTool_Tool_MageExtension_Provider_Exception(
                    "Unable to create Theme {$this->name} directory."
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
            'profileData' => $profileData,
            'package' => $this->package,
            'name' => $this->name,
            'area' => $this->area
            ));

        $newProfile->loadFromData();

        $response = $this->_registry->getResponse();
        
        $response->appendContent('Created Theme at ' . $path, array('color' => 'green'));
        $response->appendContent('Note: ', array('separator' => true, 'color' => 'yellow'));
        $response->appendContent(
            'This command created a new Theme, '
            . 'you will now need to configure it in the Magento admin at: System -> General -> Design');

        foreach ($newProfile->getIterator() as $resource) {
            $resource->create();
        }
    }
    
    protected function _getDefaultProfile()
    {
        $data = <<<EOS
<?xml version="1.0" encoding="UTF-8"?>
<themeProfile type="default" version="0.1">
    <ExtensionDirectory>
        <BlockDirectory></BlockDirectory>
        <ControllerDirectory></ControllerDirectory>
        <ControllersDirectory></ControllersDirectory>
        <etcDirectory>
            <ConfigFile/>
            <SystemFile/>
            <AdminhtmlFile/>
            <ApiFile/>
            <WsdlFile/>
        </etcDirectory>
        <HelperDirectory>
            <HelperFile name="Data"/>
        </HelperDirectory>
        <ModelDirectory>
            <ModelFile name="{$this->name}"/>
            <ObserverFile name="Observer"/>
            <EntityDirectory>
                <SetupFile/>
            </EntityDirectory>
        </ModelDirectory>
        <sqlDirectory>
            <SetupDirectory>
                <InstallFile version="0.1.0"/>
            </SetupDirectory>
        </sqlDirectory>
    </ExtensionDirectory>
</themeProfile>
EOS;
        return $data;
    }
}