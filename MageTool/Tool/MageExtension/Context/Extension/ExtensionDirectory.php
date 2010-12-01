<?php

/**
 * @see Zend_Tool_Project_Context_Filesystem_Directory
 */
require_once 'Zend/Tool/Project/Context/Filesystem/Directory.php';


class MageTool_Tool_MageExtension_Context_Extension_ExtensionDirectory extends Zend_Tool_Project_Context_System_ProjectDirectory
{

    /**
     * @var string
     */
    protected $_filesystemName = null;

    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'ExtensionDirectory';
    }
}