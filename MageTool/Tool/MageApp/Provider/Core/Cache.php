<?php

/**
 * @see MageTool_Tool_Core_Provider_Abstract
 */
require_once 'MageTool/Tool/MageApp/Provider/Abstract.php';
require_once 'Zend/Tool/Framework/Provider/Pretendable.php';

/**
 * MageTool_Tool_MageApp_Provider_Core_Cache provides commands to clear the 
 * Magento cache from the command line
 *
 * @package MageTool_MageApp_Providor_Core
 * @author Alistair Stead
 **/
class MageTool_Tool_MageApp_Provider_Core_Cache extends MageTool_Tool_MageApp_Provider_Abstract
    implements Zend_Tool_Framework_Provider_Pretendable
{
    
    /**
     * Cache types
     *
     * @var array
     **/
    protected $_cacheTypes = array();
    
    protected function _getCacheTypes()
    {
        if (!$this->_cacheTypes) {
            $cacheTypes = array();
            foreach (Mage::app()->getCacheInstance()->getTypes() as $type) {
                $cacheTypes[] = $type->getId();
            }
            
            $this->_cacheTypes = $cacheTypes;
        }
        
        return $this->_cacheTypes;
    }
    
    /**
     * Define the name of the provider
     *
     * @return string
     * @author Alistair Stead
     **/
    public function getName()
    {
        return 'MageCoreCache';
    }
    
    /**
     * Clear the magento cache
     *
     * @param string $tags comma separated list of tags to be cleared
     * @return void
     * @author Alistair Stead
     **/
    public function clear($tags = 'all')
    {
        $this->_bootstrap();
        
        $this->_getCache()->clean($this->_parseTagsString($tags));
        $this->_response->appendContent(
            'Magento Cache Cleaned for tags',
            array('color' => array('green'))
        );
    }
    
    /**
     * Flush the cache storage
     *
     * @return void
     * @author Alistair Stead
     **/
    public function flush()
    {
        $this->_getCache()->flush();
        $this->_response->appendContent(
            'Magento Cache Flushed',
            array('color' => array('green'))
        );
    }
    
    /**
     * Enable the Magento cache
     *
     * @param string $tags comma separated list of tags to be enabled
     * @return void
     * @author Alistair Stead
     **/
    public function enable($tags = 'all')
    {
        $this->_bootstrap();
        $allTypes = Mage::app()->useCache();

        $updatedTypes = 0;
        foreach ($this->_getCacheTypes() as $code) {
            if (empty($allTypes[$code])) {
                $allTypes[$code] = 1;
                $updatedTypes++;
            }
        }
        if ($updatedTypes > 0) {
            Mage::app()->saveUseCache($allTypes);
        }
        
        $this->_response->appendContent(
            'Magento Cache Enabled',
            array('color' => array('green'))
        );
    }
    
    /**
     * Disable the Magento cache
     *
     * @param string $tags comma separated list of tags to be disabled
     * @return void
     * @author Alistair Stead
     **/
    public function disable($tags = 'all')
    {
        $this->_bootstrap();
        $allTypes = Mage::app()->useCache();

        $updatedTypes = 0;
        foreach ($this->_getCacheTypes() as $code) {
            if (!empty($allTypes[$code])) {
                $allTypes[$code] = 0;
                $updatedTypes++;
            }
            $tags = Mage::app()->getCacheInstance()->cleanType($code);
        }
        if ($updatedTypes > 0) {
            Mage::app()->saveUseCache($allTypes);
        }
        
        $this->_response->appendContent(
            'Magento Cache Disabled',
            array('color' => array('green'))
        );
    }
    
    /**
     * Retreive the cache object from App
     *
     * @return Zend_Cache
     * @author Alistair Stead
     **/
    protected function _getCache()
    {
        return Mage::app()->getCacheInstance();
    }
    
    /**
     * Parse string with tags and return array of tags
     *
     * @param string $string
     * @return array
     */
    protected function _parseTagsString($string)
    {
        $tags = array();
        if (!$string == 'all') {
            $tags = explode(',', $string);
        }
        
        return $tags;
    }
}