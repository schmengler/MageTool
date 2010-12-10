<?php

/**
 * @see MageTool_Tool_Core_Provider_Abstract
 */
require_once 'MageTool/Tool/MageApp/Provider/Abstract.php';

/**
 * undocumented class
 *
 * @package default
 * @author Alistair Stead
 **/
class MageTool_Tool_MageApp_Provider_Core_Compiler extends MageTool_Tool_MageApp_Provider_Abstract
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
        return 'MageCoreCompiler';
    }
    
    /**
     * Compile the Magento classes so that no autoloader is required during execution
     *
     * @return void
     * @author Alistair Stead
     **/
    public function compile()
    {
        // get request/response object
        $request = $this->_registry->getRequest();
        $response = $this->_registry->getResponse();
        try {
            Mage::getModel('compiler/process')->run();
            $response->appendContent(
                Mage::helper('compiler')->__('The compilation has completed.'),
                array('color' => array('green'))
                );
        } catch (Mage_Core_Exception $e) {
            $response->appendContent(
                $e->getMessage(),
                array('color' => array('red'))
                );
        } catch (Exception $e) {
            $response->appendContent(
                Mage::helper('compiler')->__('Compilation error') . " " . $e->getMessage(),
                array('color' => array('red'))
                );
        }
    }
    
    /**
     * Clear the compiled files
     *
     * @return void
     * @author Alistair Stead
     **/
    public function clear()
    {
        // get request/response object
        $request = $this->_registry->getRequest();
        $response = $this->_registry->getResponse();
        try {
            Mage::getModel('compiler/process')->clear();
            $response->appendContent(
                Mage::helper('compiler')->__('The compiled files have been cleared.'),
                array('color' => array('green'))
                );           
        } catch (Exception $e) {
            $response->appendContent(
                $e->getMessage(),
                array('color' => array('red'))
                );
        }

    }
    
    /**
     * Enable the compiled files for Magento Core
     *
     * @return void
     * @author Alistair Stead
     **/
    public function enable()
    {
        Mage::getModel('compiler/process')->registerIncludePath();
        // get request/response object
        $request = $this->_registry->getRequest();
        $response = $this->_registry->getResponse();

        $response->appendContent(
            Mage::helper('compiler')->__('Compiler include path is enabled.'),
            array('color' => array('green'))
            );
    }
    
    /**
     * Disable the compiled files for Magento
     *
     * @return void
     * @author Alistair Stead
     **/
    public function disable()
    {
        Mage::getModel('compiler/process')->registerIncludePath(false);
        
        // get request/response object
        $request = $this->_registry->getRequest();
        $response = $this->_registry->getResponse();
        
        $response->appendContent(
            Mage::helper('compiler')->__('Compiler include path is disabled.'),
            array('color' => array('green'))
            );
    }
}