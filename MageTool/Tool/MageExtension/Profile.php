<?php
require_once 'MageTool/Tool/MageExtension/Profile/FileParser/Xml.php';
/**
 * @see Zend_Tool_Project_Profile_FileParser_Xml
 */
require_once 'Zend/Tool/Project/Profile/FileParser/Xml.php';

/**
 * @see Zend_Tool_Project_Profile_Resource_Container
 */
require_once 'Zend/Tool/Project/Profile/Resource/Container.php';

/**
 * This class is the front most class for utilizing Zend_Tool_Project
 *
 * A profile is a hierarchical set of resources that keep track of
 * items within a specific project.
 *
 * @category   Zend
 * @package    Zend_Tool
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class MageTool_Tool_MageExtension_Profile extends Zend_Tool_Project_Profile
{   
    /**
     * loadFromData() - Load a profile from data provided by the
     * 'profilData' attribute
     *
     */
    public function loadFromData()
    {
        if (!isset($this->_attributes['profileData'])) {
            require_once 'Zend/Tool/Project/Exception.php';
            throw new Zend_Tool_Project_Exception('loadFromData() must have "profileData" set.');
        }

        $profileFileParser = new MageTool_Tool_MageExtension_Profile_FileParser_Xml();
        $profileFileParser->unserialize($this->_attributes['profileData'], $this);

        $this->rewind();
    }

    /**
     * isLoadableFromFile() - can a profile be loaded from a file
     *
     * wether or not a profile can be loaded from the
     * file in attribute 'projectProfileFile', or from a file named
     * '.zfproject.xml' inside a directory in key 'projectDirectory'
     *
     * @return bool
     */
    public function isLoadableFromFile()
    {
        return true;
    }

    /**
     * loadFromFile() - Load data from file
     *
     * this attempts to load a project profile file from a variety of locations depending
     * on what information the user provided vie $options or attributes, specifically the
     * 'projectDirectory' or 'projectProfileFile'
     *
     */
    public function loadFromFile()
    {
        // if no data is supplied, need either a projectProfileFile or a projectDirectory
        if (!isset($this->_attributes['projectProfileFile']) && !isset($this->_attributes['projectDirectory'])) {
            require_once 'Zend/Tool/Project/Exception.php';
            throw new Zend_Tool_Project_Exception('loadFromFile() must have at least "projectProfileFile" or "projectDirectory" set.');
        }

        if (isset($this->_attributes['projectProfileFile'])) {
            $projectProfileFilePath = $this->_attributes['projectProfileFile'];
            if (!file_exists($projectProfileFilePath)) {
                require_once 'Zend/Tool/Project/Exception.php';
                throw new Zend_Tool_Project_Exception('"projectProfileFile" was supplied but file was not found at location ' . $projectProfileFilePath);
            }
            $this->_attributes['projectDirectory'] = dirname($projectProfileFilePath);
        } else {
            $projectProfileFilePath = rtrim($this->_attributes['projectDirectory'], '/\\') . '/.zfproject.xml';
            if (!file_exists($projectProfileFilePath)) {
                require_once 'Zend/Tool/Project/Exception.php';
                throw new Zend_Tool_Project_Exception('"projectDirectory" was supplied but no profile file file was not found at location ' . $projectProfileFilePath);
            }
            $this->_attributes['projectProfileFile'] = $projectProfileFilePath;
        }

        $profileData = file_get_contents($projectProfileFilePath);

        $profileFileParser = new Zend_Tool_Project_Profile_FileParser_Xml();
        $profileFileParser->unserialize($profileData, $this);

        $this->rewind();
    }
}