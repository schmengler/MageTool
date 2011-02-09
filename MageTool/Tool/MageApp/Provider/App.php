<?php

/**
 * @see MageTool_Tool_MageApp_Provider_Abstract
 */
require_once 'MageTool/Tool/MageApp/Provider/Abstract.php';
require_once 'Zend/Tool/Framework/Provider/Pretendable.php';

/**
 * MageTool_Tool_MageApp_Provider_App adds command that provide information about Magento
 *
 * @package MageTool_MageApp_Providor
 * @author Alistair Stead
 **/
class MageTool_Tool_MageApp_Provider_App extends MageTool_Tool_MageApp_Provider_Abstract 
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
        return 'MageApp';
    }
    
    /**
     * Clear the magento cache
     *
     * @return void
     * @author Alistair Stead
     **/
    public function version()
    {
        $this->_bootstrap();
        
        $version = Mage::getVersion();        
        $this->_response->appendContent(
            'Magento Version: ' . $version,
            array('color' => array('green'))
            );
    }
    
    /**
     * Dispatch a Magento event from the cli to allow testing.
     * 
     * This can be used to test the observers configuration only. The data object
     * will not be correctly constructed.
     *
     * @param string $name The name of the event to be dispatched
     * @return void
     * @author Alistair Stead
     **/
    public function dispatchEvent($name, $data = array())
    {
        $this->_bootstrap();
        
        $this->_response->appendContent(
            "Dispatching event: {$name}",
            array('color' => array('white'))
            );
        Mage::dispatchEvent($name, $data);
        $this->_response->appendContent(
            "Dispatched event: {$name}",
            array('color' => array('green'))
            );
    }
}