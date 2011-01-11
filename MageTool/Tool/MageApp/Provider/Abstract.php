<?php

require_once 'MageTool/Tool/MageApp/Provider/Exception.php';
/**
 * @see Zend_Tool_Framework_Provider_Interface
 */
require_once 'Zend/Tool/Framework/Provider/Interface.php';

/**
 * @see Zend_Tool_Framework_Registry_EnabledInterface
 */
require_once 'Zend/Tool/Framework/Registry/EnabledInterface.php';

/**
 * MageTool_MageApp_Providor_Abstract
 *
 * @package MageTool_MageApp_Providor
 * @author Alistair Stead
 **/
abstract class MageTool_Tool_MageApp_Provider_Abstract
    implements Zend_Tool_Framework_Provider_Interface, Zend_Tool_Framework_Registry_EnabledInterface
{
    /**
     * @var Zend_Tool_Framework_Registry_Interface
     */
    protected $_registry = null;
    
    /**
     * Internal request object
     *
     * @var Zend_Tool_Framework_Request
     **/
    protected $_request;
    
    /**
     * Internal response object
     *
     * @var Zend_Tool_Framework_Response
     **/
    protected $_response;

    /**
     * setRegistry() - required by Zend_Tool_Framework_Registry_EnabledInterface
     *
     * @param Zend_Tool_Framework_Registry_Interface $registry
     * @return Zend_Tool_Framework_Provider_Abstract
     */
    public function setRegistry(Zend_Tool_Framework_Registry_Interface $registry)
    {
        $this->_registry = $registry;
        return $this;
    }
    
    /**
     * undocumented function
     *
     * @return void
     * @author Alistair Stead
     **/
    protected function _bootstrap()
    {
        //load Magento
        $mageFilename = 'app/Mage.php';
        $this->_isInstalled($mageFilename);
        require_once $mageFilename;
        Mage::app();
        
        // get request/response object
        $this->_request = $this->_registry->getRequest();
        $this->_response = $this->_registry->getResponse();
    }
    /**
     * Find the mage file and confirm Magento is installed
     *
     * @return void
     * @author Alistair Stead
     **/
    protected function _isInstalled($mageFilename)
    {
        if (!file_exists($mageFilename)) {
            throw new MageTool_Tool_MageApp_Provider_Exception(
                'The mage.php file can not be located. 
                You must run this command within a Magento project.'
            );
        }
        
        return true;
    }
    
    /**
     * Build the current directory path
     *
     * @return void
     * @author Alistair Stead
     **/
    protected function _chApplicationDir()
    {
        chdir(getcwd());
    }
}