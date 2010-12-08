<?php

/**
 * @see MageTool_Tool_MageExtension_Context_Extension_AbstractFile
 */
require_once 'MageTool/Tool/MageExtension/Context/Extension/AbstractFile.php';

class MageTool_Tool_MageExtension_Context_Extension_SetupFile extends MageTool_Tool_MageExtension_Context_Extension_AbstractFile
{
    /**
     * @var string
     */
    protected $_filesystemName = 'Setup';
    
    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'SetupFile';
    }
    
    /**
     * getHelperName()
     *
     * @return string
     */
    public function getClassName()
    {
        return 'Setup';
    }
    
    /**
     * class path template
     *
     * @return string
     * @author Alistair Stead
     **/
    public function getClassPath()
    {
        return '%s_%s_Model_Entity_%s';
    }
    
    /**
     * Return the name of the class to extend
     *
     * @return string
     * @author Alistair Stead
     **/
    public function getExtends()
    {
        return 'Mage_Eav_Model_Entity_Setup';
    }
}
