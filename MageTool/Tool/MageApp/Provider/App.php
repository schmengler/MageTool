<?php

/**
 * @see MageTool_Tool_MageApp_Provider_Abstract
 */
require_once 'MageTool/Tool/MageApp/Provider/Abstract.php';

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
        $this->response->appendContent(
            'Magento Version: ' . $version,
            array('color' => array('yellow'))
            );
    }
}