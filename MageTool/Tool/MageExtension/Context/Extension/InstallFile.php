<?php

/**
 * @see Zend_Tool_Project_Context_Filesystem_File
 */
require_once 'Zend/Tool/Project/Context/Filesystem/File.php';


class MageTool_Tool_MageExtension_Context_Extension_InstallFile extends Zend_Tool_Project_Context_Filesystem_File
{
    /**
     * @var string
     */
    protected $_version = '0.1.0';
    
    /**
     * @var string
     */
    protected $_filesystemName = 'mysql4-install-%s.php';
    
    /**
     * init()
     *
     */
    public function init()
    {
        $this->_version = $this->_resource->getAttribute('version');
        parent::init();
    }

    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'InstallFile';
    }
    
    /**
     * get the setup version number
     *
     * @return void
     * @author Alistair Stead
     **/
    public function getVersion()
    {
        return $this->_version;
    }
    
    /**
     * getFilesystemName() the name of the file that will be created
     *
     * @return string
     */
    public function getFilesystemName()
    {
        return sprintf($this->_filesystemName, $this->getVersion());
    }
    
    /**
     * getPath()
     *
     * @return string
     */
    public function getPath()
    {
        $path = $this->_baseDirectory;
        if ($this->getFilesystemName()) {
            $path .= '/' . $this->getFilesystemName();
        }
        return $path;
    }

    /**
     * getContents()
     *
     * @return string
     */
    public function getContents()
    {
        $profile = $this->_resource->getProfile();
        $vendor = $profile->getAttribute('vendor');
        $xmlVendor = strtolower($vendor);
        $name = $profile->getAttribute('name');
        $xmlName = strtolower($name);
        $pool = $profile->getAttribute('pool');

        $content = '<?php
/* @var $installer ' . "{$vendor}_{$name}_Model_Entity_Setup" . ' */
$installer = $this;
$installer->startSetup();

$installer->endSetup();
';
        
        return $content;
    }

}