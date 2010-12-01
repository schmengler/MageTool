<?php

/**
 * @see Zend_Tool_Project_Context_Filesystem_File
 */
require_once 'Zend/Tool/Project/Context/Filesystem/File.php';


class MageTool_Tool_MageExtension_Context_Extension_AdminhtmlFile extends Zend_Tool_Project_Context_Filesystem_File
{

    /**
     * @var string
     */
    protected $_filesystemName = 'adminhtml.xml';

    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'AdminhtmlFile';
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

        return <<< EOS
<?xml version="1.0"?>
<!-- Remove this file if your module does not have an admin interface -->
<config>
    <menu>
    </menu>
    <acl>
        <resources>
        </resources>
    </acl>
</config>
EOS;
    }

}