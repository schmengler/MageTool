<?php

/**
 * @see MageTool_Tool_MageExtension_Context_Extension_AbstractFile
 */
require_once 'MageTool/Tool/MageExtension/Context/Extension/AbstractFile.php';

class MageTool_Tool_MageExtension_Context_Extension_HelperFile extends MageTool_Tool_MageExtension_Context_Extension_AbstractFile
{
    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'HelperFile';
    }
    
    /**
     * class path template
     *
     * @return string
     * @author Alistair Stead
     **/
    public function getClassPath()
    {
        return '%s_%s_Helper_%s';
    }
    
    /**
     * Return the name of the class to extend
     *
     * @return string
     * @author Alistair Stead
     **/
    public function getExtends()
    {
        return 'Mage_Core_Helper_Abstract';
    }
}
