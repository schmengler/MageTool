<?php

/**
 * @see MageTool_Tool_Core_Provider_Abstract
 */
require_once 'MageTool/Tool/MageApp/Provider/Abstract.php';
require_once 'Zend/Tool/Framework/Provider/Pretendable.php';

/**
 * MageTool_Tool_MageApp_Provider_Core_Compiler provides commands to interact with the Magento compiler
 *
 * @package MageTool_MageApp_Providor_Core
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
    public function run()
    {
        $this->_bootstrap();

        try {
            Mage::getModel('compiler/process')->run();
            $this->response->appendContent(
            Mage::helper('compiler')->__('The compilation has completed.'),
            array('color' => array('green'))
            );
        } catch (Mage_Core_Exception $e) {
            throw new Exception($e->getMessage());
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
        $this->_bootstrap();

        try {
            Mage::getModel('compiler/process')->clear();
            $this->response->appendContent(
                    Mage::helper('compiler')->__('ompilation successfully cleared.'),
                    array('color' => array('green'))
                );           
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
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
        $this->_bootstrap();
        
        if (Mage::getModel('compiler/process')->getCompiledFilesCount() == 0) {
            throw new Exception(Mage::helper('compiler')->__('Not Compiled! Please run zf run mage-core-compiler first.'));
        }
        
        Mage::getModel('compiler/process')->registerIncludePath();

        $this->response->appendContent(
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
        $this->_bootstrap();
        Mage::getModel('compiler/process')->registerIncludePath(false);
        
        $this->response->appendContent(
            Mage::helper('compiler')->__('Compiler include path is disabled.'),
            array('color' => array('green'))
        );
    }
    
    /**
     * Return the status of the compilation process
     *
     * @return void
     * @author Alistair Stead
     **/
    public function stat()
    {
        $this->_bootstrap();
        
        $compilerConfig = './includes/config.php';
        if (file_exists($compilerConfig)) {
            include $compilerConfig;
        }
        $status = defined('COMPILER_INCLUDE_PATH') ? 'Enabled' : 'Disabled';
        $state  = Mage::getModel('compiler/process')->getCollectedFilesCount() > 0 ? 'Compiled' : 'Not Compiled';

        $this->response->appendContent(
            "Compiler Status:          " . $status,
            array('color' => array('green'))
        );
        $this->response->appendContent(
            "Compilation State:        " . $state,
            array('color' => array('green'))
        );
        $this->response->appendContent(
            "Collected Files Count:    " . Mage::getModel('compiler/process')->getCollectedFilesCount(),
            array('color' => array('green'))
        );
        $this->response->appendContent(
            "Compiled Scopes Count:    " . Mage::getModel('compiler/process')->getCompiledFilesCount(),
            array('color' => array('green'))
        );
    }
}