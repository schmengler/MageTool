<?php

require_once "MageTool/Tool/MageApp/Provider/Core/Config.php";

class ConfigTest extends PHPUnit_Framework_TestCase 
{
    /**
     * Member variable that will hold the object under test
     *
     * @var MageTool_Tool_MageExtension_Provider_Extension
     **/
    protected $extension;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->extension = new MageTool_Tool_MageApp_Provider_Core_Config;
    }
    
    /**
     * getNameShouldReturnString
     * @author Alistair Stead
     * @test
     */
    public function getNameShouldReturnString()
    {
        $this->assertEquals(
            $this->extension->getName(),
            'MageCoreConfig',
            'The providor does not return the expected string name'
        );
    } // getNameShouldReturnString
    
}