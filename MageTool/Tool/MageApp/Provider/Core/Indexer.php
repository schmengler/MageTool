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
class MageTool_Tool_MageApp_Provider_Core_Indexer extends MageTool_Tool_MageApp_Provider_Abstract
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
        return 'MageCoreIndexer';
    }
    
    /**
     * Retrieve info for the idex process
     *
     * @return void
     * @author Alistair Stead
     **/
    public function info($code = 'all')
    {
        $this->_bootstrap();
        // get request/response object
        $request = $this->_registry->getRequest();
        $response = $this->_registry->getResponse();
        
        $processes = $this->_parseIndexerString($code);
        foreach ($processes as $process) {
            /* @var $process Mage_Index_Model_Process */
            $output = sprintf('%-30s', $process->getIndexerCode());
            $output .= $process->getIndexer()->getName();
            $response->appendContent(
                $output,
                array('color' => array('white'))
                );
            
        }
    }
    
    /**
     * Parse string with indexers and return array of indexer instances
     *
     * @param string $string
     * @return array
     */
    protected function _parseIndexerString($string)
    {
        $processes = array();
        if ($string == 'all') {
            $collection = $this->_getIndexer()->getProcessesCollection();
            foreach ($collection as $process) {
                $processes[] = $process;
            }
        } else if (!empty($string)) {
            $codes = explode(',', $string);
            foreach ($codes as $code) {
                $process = $this->_getIndexer()->getProcessByCode(trim($code));
                if (!$process) {
                    throw new Exception('Warning: Unknown indexer with code ' . trim($code));
                } else {
                    $processes[] = $process;
                }
            }
        }
        return $processes;
    }
    
    /**
     * Get Indexer instance
     *
     * @return Mage_Index_Model_Indexer
     */
    protected function _getIndexer()
    {
        return Mage::getSingleton('index/indexer');
    }
}