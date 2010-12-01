<?php

class MageTool_Tool_MageExtension_Context_Extension_HelperFile extends Zend_Tool_Project_Context_Filesystem_File
{

    /**
     * @var string
     */
    protected $_helperName = 'Data';

    /**
     * @var string
     */
    protected $_moduleName = null;
    
    /**
     * @var string
     */
    protected $_filesystemName = 'helperName';

    /**
     * init()
     *
     */
    public function init()
    {
        $this->_helperName = $this->_resource->getAttribute('helperName');
        $this->_filesystemName = ucfirst($this->_helperName) . '.php';
        parent::init();
    }

    /**
     * getPersistentAttributes
     *
     * @return array
     */
    public function getPersistentAttributes()
    {
        return array(
            'helperName' => $this->getHelperName()
            );
    }

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
     * getHelperName()
     *
     * @return string
     */
    public function getHelperName()
    {
        return $this->_helperName;
    }

    /**
     * getContents()
     *
     * @return string
     */
    public function getContents()
    {
        $className = ($this->_helperName) ? ucfirst($this->_helperName) : '';
        
        $codeGenFile = new Zend_CodeGenerator_Php_File(array(
            'fileName' => $this->getPath(),
            'classes' => array(
                new Zend_CodeGenerator_Php_Class(array(
                    'name' => $className,
                    'extendedClass' => 'Mage_Core_Helper_Abstract',
                    'methods' => array()
                	))
            	)
        	));

        // store the generator into the registry so that the addAction command can use the same object later
        Zend_CodeGenerator_Php_File::registerFileCodeGenerator($codeGenFile); // REQUIRES filename to be set
        return $codeGenFile->generate();
    }
}
