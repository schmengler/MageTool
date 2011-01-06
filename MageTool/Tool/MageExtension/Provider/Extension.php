<?php
require_once 'MageTool/Tool/MageExtension/Profile.php';
/**
 * @see MageTool_Tool_MageExtension_Provider_Abstract
 */
require_once 'MageTool/Tool/MageExtension/Provider/Abstract.php';
require_once 'MageTool/Tool/MageExtension/Provider/Exception.php';
require_once 'Zend/Tool/Framework/Provider/Pretendable.php';
/**
 * undocumented class
 *
 * @package default
 * @author Alistair Stead
 **/
class MageTool_Tool_MageExtension_Provider_Extension 
    extends MageTool_Tool_MageExtension_Provider_Abstract
    implements Zend_Tool_Framework_Provider_Pretendable
{
    /**
     * The vendor name under which the module should be created
     *
     * @var string
     **/
    protected $_vendor;
    
    /**
     * The name of the module to be created
     *
     * @var string
     **/
    protected $_name;
    
    /**
     * The code pool into which the module should be placed
     *
     * @var string
     **/
    protected $_pool;
    
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
        
        $this->_vendor = ucfirst($vendor);
        $this->_name = ucfirst($name);
        $this->_pool = strtolower($pool);
        
        $path = sprintf("%s/app/code/%s/%s/%s", $path, $this->_pool, $this->_vendor, $this->_name);

        if (file_exists($path)) {
            throw new MageTool_Tool_MageExtension_Provider_Exception(
                "An Extension {$this->name} already exists in the {$this->pool} 
                code pool for the vendor {$this->vendor}."
            );
        } else {
            try {
                mkdir($path, 0755, true);
            } catch (Exception $e) {
                throw new MageTool_Tool_MageExtension_Provider_Exception(
                    "Unable to create Extension {$this->name} directory."
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
                'vendor' => $this->_vendor,
                'name' => $this->_name,
                'pool' => $this->_pool
            ));

        $newProfile->loadFromData();

        $response = $this->_registry->getResponse();
        
        $response->appendContent('Created extension at ' . $path, array('color' => 'green'));
        $response->appendContent('Note: ', array('separator' => true, 'color' => 'yellow'));
        $response->appendContent(
            'This command created a new extension, 
            you will now need to create a config file to enable this module in app/etc/modules'
        );
        $response->appendContent('Example: ', array('separator' => true, 'color' => 'yellow'));
        $xmlExample = <<< EOS
<?xml version="1.0"?>
<config>
    <modules>
        <{$this->_vendor}_{$this->_name}>
             <active>true</active>
             <codePool>{$this->_pool}</codePool>
             <depends>
             </depends>
        </{$this->_vendor}_{$this->_name}>
    </modules>
</config>
EOS;
        $response->appendContent($xmlExample);

        foreach ($newProfile->getIterator() as $resource) {
            $resource->create();
        }
    }
    
    protected function _getDefaultProfile()
    {
        $data = <<<EOS
<?xml version="1.0" encoding="UTF-8"?>
<extensionProfile type="default" version="0.1">
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
            <ModelFile name="{$this->_name}"/>
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
</extensionProfile>
EOS;
        return $data;
    }
}