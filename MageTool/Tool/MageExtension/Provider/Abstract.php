<?php


/**
 * @see Zend_Tool_Framework_Provider_Interface
 */
require_once 'Zend/Tool/Framework/Provider/Interface.php';
require_once 'Zend/Tool/Project/Provider/Abstract.php';

/**
 * undocumented class
 *
 * @package default
 * @author Alistair Stead
 **/
abstract class MageTool_Tool_MageExtension_Provider_Abstract extends Zend_Tool_Project_Provider_Abstract
{
    public function __construct()
    {
        $contextRegistry = Zend_Tool_Project_Context_Repository::getInstance();
        $contextRegistry->addContextsFromDirectory(
            dirname(dirname(__FILE__)) . '/Context/Extension/', 'MageTool_Tool_MageExtension_Context_Extension_'
        );
        
        parent::__construct();
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
            throw new MageTool_Tool_Provider_Exception('The mage.php file can not be located. You must run this command within a Magento project.');
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