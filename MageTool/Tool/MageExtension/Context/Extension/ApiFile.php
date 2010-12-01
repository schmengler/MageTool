<?php

/**
 * @see Zend_Tool_Project_Context_Filesystem_File
 */
require_once 'Zend/Tool/Project/Context/Filesystem/File.php';


class MageTool_Tool_MageExtension_Context_Extension_ApiFile extends Zend_Tool_Project_Context_Filesystem_File
{

    /**
     * @var string
     */
    protected $_filesystemName = 'api.xml';

    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'ApiFile';
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
<!-- Remove this file if your module does not have profide an api -->
<config>
    <api>
        <resources>
        </resources>
        <v2>
        </v2>
        <acl>
            <resources>
            </resources>
        </acl>
    </api>
</config>
EOS;
    }

}