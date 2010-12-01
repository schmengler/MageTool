<?php

class MageTool_Tool_MageExtension_Context_Extension_ModelFile extends MageTool_Tool_MageExtension_Context_Extension_AbstractFile
{
    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'ModelFile';
    }
    
    /**
     * class path template
     *
     * @return string
     * @author Alistair Stead
     **/
    public function getClassPath()
    {
        return '%s_%s_Model_%s';
    }
    
    /**
     * Return the name of the class to extend
     *
     * @return string
     * @author Alistair Stead
     **/
    public function getExtends()
    {
        return 'Mage_Core_Model_Abstract';
    }
}
