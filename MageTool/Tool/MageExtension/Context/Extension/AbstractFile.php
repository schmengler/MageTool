<?php

abstract class MageTool_Tool_MageExtension_Context_Extension_AbstractFile extends Zend_Tool_Project_Context_Filesystem_File
{

    /**
     * @var string
     */
    protected $_className = 'ClassName';
    
    /**
     * @var string
     */
    protected $_filesystemName = 'fileName';

    /**
     * init()
     *
     */
    public function init()
    {
        $this->_className = $this->_resource->getAttribute('name');
        $this->_filesystemName = ucfirst($this->_className) . '.php';
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
            'name' => $this->getName()
            );
    }

    /**
     * getHelperName()
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->_className;
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
        $name = $profile->getAttribute('name');
        
        $className = sprintf($this->getClassPath(), $vendor, $name, ucfirst($this->_className));
        
        $codeGenFile = new Zend_CodeGenerator_Php_File(array(
            'fileName' => $this->getPath(),
            'classes' => array(
                new Zend_CodeGenerator_Php_Class(array(
                    'name' => $className,
                    'extendedClass' => $this->getExtends(),
                    'methods' => array()
                	))
            	)
        	));

        // store the generator into the registry so that the addAction command can use the same object later
        Zend_CodeGenerator_Php_File::registerFileCodeGenerator($codeGenFile); // REQUIRES filename to be set
        return $codeGenFile->generate();
    }
    
    /**
     * Return a class path template for use with sprintf()
     *
     * @return string
     * @author Alistair Stead
     **/
    abstract public function getClassPath();
    
    /**
     * Return the class name that will be extended
     *
     * @return string
     * @author Alistair Stead
     **/
    abstract public function getExtends();
}
