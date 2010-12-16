<?php

/**
 * @see MageTool_Tool_Core_Provider_Abstract
 */
require_once 'MageTool/Tool/MageApp/Provider/Abstract.php';

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
        
        $response->appendContent(
            'Magento Core Resource: [VERSION] [DATA_VERSION]',
            array('color' => array('yellow'))
            );
            
        $resTable = Mage::getSingleton('core/resource')->getTableName('core/resource');
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        
        $select = $read->select()->from($resTable, array('code', 'version', 'data_version'));
        if(is_string($code)) {
            $select->where('code = ?', $code);
        }
        $resourceCollection = $read->fetchAll($select);
        $read->closeConnection();

        foreach($resourceCollection as $key => $resource) {
            $this->response->appendContent(
                "{$resource['code']} [{$resource['version']}] [{$resource['data_version']}]",
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
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');

        $write->delete($resTable, "code = '{$code}'");
        $write->closeConnection();
    }
}