<?php

require_once "MageTool/Tool/MageApp/Provider/Core/Cache.php";


class CacheTest extends PHPUnit_Framework_TestCase 
{
    /**
     * Member variable that will hold the object under test
     *
     * @var MageTool_Tool_MageExtension_Provider_Extension
     **/
    protected $_cache;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->_cache = new MageTool_Tool_MageApp_Provider_Core_Cache;
    }
    
    /**
     * getNameShouldReturnString
     * @author Alistair Stead
     * @test
     */
    public function getNameShouldReturnString()
    {
        $this->assertEquals(
            $this->_cache->getName(),
            'MageCoreCache',
            'The providor does not return the expected string name'
        );
    } // getNameShouldReturnString
    
    /**
     * _getCacheShouldReturnVarienCache
     * @author Alistair Stead
     * @test
     */
    public function _getCacheShouldReturnVarienCache()
    {
        $_getCacheMethod = self::getMethod('_getCache');
        $cache = $_getCacheMethod->invoke($this->_cache);
        $this->assertInstanceOf('Mage_Core_Model_Cache',
            $cache,
            "_getCache does not return the unexpected expected {get_class($cache)} object"
        );
    } // _getCacheShouldReturnVarienCache
    
    /**
     * _parseTagsStringShouldReturnArray
     * @author Alistair Stead
     * @test
     */
    public function _parseTagsStringShouldReturnArray()
    {
        $_parseTagsStringMethod = self::getMethod('_parseTagsString');
        $result = $_parseTagsStringMethod->invoke($this->_cache, 'all');
        
        $this->assertTrue(
            is_array($result),
            '_parseTagsString does not return an array as expected'
        );
    } // _parseTagsStringShouldReturnArray
    
    
    /**
     * Provide access to protected methods by using reflection
     *
     * @param string $name 
     * @return void
     * @author Alistair Stead
     */
    protected static function getMethod($name)
    {
        $class = new ReflectionClass('MageTool_Tool_MageApp_Provider_Core_Cache');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
      
        return $method;
    }
}