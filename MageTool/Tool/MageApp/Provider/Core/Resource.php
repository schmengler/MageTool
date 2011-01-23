<?php

/**
 * @see MageTool_Tool_Core_Provider_Abstract
 */
require_once 'MageTool/Tool/MageApp/Provider/Abstract.php';
require_once 'Zend/Tool/Framework/Provider/Pretendable.php';

/**
 * MageTool_Tool_MageApp_Provider_Core_Resource provides commands to obtain detail
 * about the installed modules and to clear the internal registry of specific versions
 *
 * @package MageTool_MageApp_Providor_Core
 * @author Alistair Stead
 **/
class MageTool_Tool_MageApp_Provider_Core_Resource extends MageTool_Tool_MageApp_Provider_Abstract
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
        return 'MageCoreResource';
    }
    
    /**
     * Retrive a list of installed resources
     *
     * @return void
     * @author Alistair Stead
     **/
    public function show($code = null)
    {
        $this->_bootstrap();
        
        $this->_response->appendContent(
            sprintf(
                '%-40s %-15s %-15s', 
                'Magento Core Resource:',
                '[VERSION]',
                '[DATA_VERSION]'
            ),
            array('color' => array('yellow'))
        );
            
        $resTable = Mage::getSingleton('core/resource')->getTableName('core/resource');
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        
        $select = $read->select()->from($resTable, array('code', 'version', 'data_version'));
        if (is_string($code)) {
            $select->where('code = ?', $code);
        }
        $resourceCollection = $read->fetchAll($select);
        if (count($resourceCollection) === 0) {
            throw new Exception(
                "This resource does not exist in the core_resource table. 
                Try running zf show mage-core-resource to find the correct name."
            );
        }
        $read->closeConnection();

        foreach ($resourceCollection as $key => $resource) {
            $this->_response->appendContent(
                sprintf(
                    '%-40s %-15s %-15s', 
                    $resource['code'],
                    $resource['version'],
                    $resource['data_version']
                ),
                array('color' => array('white'))
            );
        }
    }
    
    /**
     * Delete a core resource
     *
     * @return void
     * @author Alistair Stead
     **/
    public function delete($code)
    {
        $this->_bootstrap();
        
        $resTable = Mage::getSingleton('core/resource')->getTableName('core/resource');
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        
        $select = $read->select()->from($resTable, array('code', 'version', 'data_version'));
        $select->where('code = ?', $code);
        $resourceCollection = $read->fetchAll($select);
        if (count($resourceCollection) === 0) {
            throw new Exception(
                "This resource does not exist in the core_resource table. 
                Try running zf show mage-core-resource to find the correct name."
            );
        }
        
        $write->delete($resTable, "code = '{$code}'");
        $write->closeConnection();
        $this->_response->appendContent(
            "Core Resource {$code} deleted successfully",
            array('color' => array('green'))
        );
    }
    
    /**
     * Run the setup class for the supplied resource
     *
     * @return void
     * @author Alistair Stead
     **/
    public function update($module = null)
    {
        $this->_bootstrap();
        Mage::app()->setUpdateMode(true);

        $resources = Mage::getConfig()->getNode('global/resources')->children();
        $afterApplyUpdates = array();
        foreach ($resources as $resName => $resource) {
            if (!$resource->setup) {
                continue;
            }
            if (!is_null($module) && $resource->setup->module != $module) {
                continue;
            }
            
            $className = 'Mage_Core_Model_Resource_Setup';
            if (isset($resource->setup->class)) {
                $className = $resource->setup->getClassName();
            }
            $this->_response->appendContent(
                "Running {$className}->applyUpdates()",
                array('color' => array('white'))
            );
            $setupClass = new $className($resName);
            $setupClass->applyUpdates();
            if ($setupClass->getCallAfterApplyAllUpdates()) {
                $afterApplyUpdates[] = $setupClass;
            }
        }

        foreach ($afterApplyUpdates as $setupClass) {
            $setupClass->afterApplyAllUpdates();
        }

        Mage::app()->setUpdateMode(false);
        $this->_response->appendContent(
            "Core Resources updated successfully",
            array('color' => array('green'))
        );
    }
}