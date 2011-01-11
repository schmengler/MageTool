<?php

require_once "MageTool/Tool/MageApp/Provider/Core/Resource.php";

class ResourceTest extends PHPUnit_Framework_TestCase 
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
        
        $this->extension = new MageTool_Tool_MageApp_Provider_Core_Resource;
    }
    
    /**
     * getNameShouldReturnString
     * @author Alistair Stead
     * @test
     */
    public function getNameShouldReturnString()
    {
        $this->assertEquals( $this->extension->getName(), 'MageCoreResource', 'The providor does not return the expected string name' );
    } // getNameShouldReturnString
    
}