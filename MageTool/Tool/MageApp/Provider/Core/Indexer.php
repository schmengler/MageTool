<?php

/**
 * @see MageTool_Tool_Core_Provider_Abstract
 */
require_once 'MageTool/Tool/MageApp/Provider/Abstract.php';

/**
 * MageTool_Tool_MageApp_Provider_Core_Indexer commands that can be used to build
 * the magento flat table indexes.
 *
 * @package MageTool_MageApp_Providor_Core
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
        $processes = $this->_parseIndexerString($code);
        foreach ($processes as $process) {
            $this->response->appendContent(
                sprintf(
                    '%-30s %-25s %-15s %-20s', 
                    $process->getIndexerCode(),
                    $process->getIndexer()->getName(),
                    $this->_cleanStatus($process->getStatus()),
                    $this->_cleanMode($process->getMode())
                ),
                array('color' => array('white'))
                );
            
        }
    }
    
    /**
     * Update the mode of the index processor
     *
     * @return void
     * @author Alistair Stead
     **/
    public function mode($mode, $code = 'all')
    {
        $this->_bootstrap();   
        $processes = $this->_parseIndexerString($code);
        if ($mode != Mage_Index_Model_Process::MODE_REAL_TIME || $mode != Mage_Index_Model_Process::MODE_MANUAL) {
            throw new Exception("Unsupported mode value supplied. {$mode}");
        }
        foreach ($processes as $process) {
            $process->setMode($mode)->save();
            $this->response->appendContent(
                sprintf(
                    "%s index was successfully changed index mode to %s",
                    $process->getIndexer()->getName(),
                    $this->_cleanMode($process->getMode())
                ),
                array('color' => array('green'))
            );
        }
    }
    
    /**
     * Run the indexer and build the flat table indexes for Magento
     *
     * @param string $code 
     * @return void
     * @author Alistair Stead
     */
    public function run($code = 'all') {
        $this->_bootstrap();   
        $processes = $this->_parseIndexerString($code);
        
        foreach ($processes as $process) {
            // TODO this process needs to be optimised
            $process->reindexEverything();
            $this->response->appendContent(
                sprintf(
                    "%s index was rebuilt successfully",
                    $process->getIndexer()->getName(),
                    $this->_cleanMode($process->getMode())
                ),
                array('color' => array('green'))
            );
        }
    }
    
    
    /**
     * Clean the raw mode value for display
     *
     * @param string $rawMode 
     * @return string
     * @author Alistair Stead
     */
    protected function _cleanMode($rawMode) {
        switch ($rawMode) {
            case Mage_Index_Model_Process::MODE_REAL_TIME:
                $mode = 'Update on Save';
                break;
            case Mage_Index_Model_Process::MODE_MANUAL:
                $mode = 'Manual Update';
                break;
        }
        
        return $mode;
    }
    
    /**
     * Clean the raw status for display
     *
     * @param string $rawStatus 
     * @return string
     * @author Alistair Stead
     */
    protected function _cleanStatus($rawStatus) {
        switch ($rawStatus) {
            case Mage_Index_Model_Process::STATUS_PENDING:
                $status = 'Pending';
                break;
            case Mage_Index_Model_Process::STATUS_REQUIRE_REINDEX:
                $status = 'Require Reindex';
                break;

            case Mage_Index_Model_Process::STATUS_RUNNING:
                $status = 'Running';
                break;
            default:
                $status = 'Ready';
                break;
        }
        
        return $status;
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