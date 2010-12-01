<?php

/**
 * @see Zend_Tool_Project_Context_Filesystem_Directory
 */
require_once 'Zend/Tool/Project/Context/Filesystem/Directory.php';


class MageTool_Tool_MageExtension_Context_Extension_SetupDirectory extends Zend_Tool_Project_Context_Filesystem_Directory
{

    /**
     * @var string
     */
    protected $_filesystemName = 'setup';

    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'SetupDirectory';
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
     * getFilesystemName()
     *
     * @return string
     */
    public function getFilesystemName()
    {
        $profile = $this->_resource->getProfile();
        $vendor = $profile->getAttribute('vendor');
        $xmlVendor = strtolower($vendor);
        $name = $profile->getAttribute('name');
        $xmlName = strtolower($name);
        $pool = $profile->getAttribute('pool');
        
        return sprintf('%s_%s_%s', $xmlVendor, $xmlName, $this->_filesystemName);
    }

}