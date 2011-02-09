<?php

require_once "MageTool/Tool/MageExtension/Provider/Extension.php";

class ExtensionTest extends PHPUnit_Framework_TestCase 
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
        
        $this->extension = new MageTool_Tool_MageExtension_Provider_Extension;
    }
    
    /**
     * getNameShouldReturnString
     * @author Alistair Stead
     * @test
     */
    public function getNameShouldReturnString()
    {
        $this->assertEquals( $this->extension->getName(), 'MageExtension', 'The providor does not return the expected string name' );
    } // getNameShouldReturnString
    
}