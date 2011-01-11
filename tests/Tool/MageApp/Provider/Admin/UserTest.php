<?php

require_once "MageTool/Tool/MageApp/Provider/Admin/User.php";

class UserTest extends PHPUnit_Framework_TestCase 
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
        
        $this->extension = new MageTool_Tool_MageApp_Provider_Admin_User;
    }
    
    /**
     * getNameShouldReturnSTring
     * @author Alistair Stead
     * @test
     */
    public function getNameShouldReturnSTring()
    {
        $this->assertEquals( $this->extension->getName(), 'MageAdminUser', 'The providor does not return the expected string name' );
    } // getNameShouldReturnSTring
    
}