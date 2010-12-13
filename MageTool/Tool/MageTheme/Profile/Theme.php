<?php

class MageTool_Tool_MageTheme_Profile_Theme extends Zend_Tool_Project_Profile_FileParser_Xml {
    
    /**
     * unserialize()
     *
     * Create a structure in the object $profile from the structure specficied
     * in the xml string provided
     *
     * @param string xml data
     * @param Zend_Tool_Project_Profile The profile to use as the top node
     * @return Zend_Tool_Project_Profile
     */
    public function unserialize($data, Zend_Tool_Project_Profile $profile)
    {
        if ($data == null) {
            throw new Exception('contents not available to unserialize.');
        }

        $this->_profile = $profile;

        $xmlDataIterator = new SimpleXMLIterator($data);

        if ($xmlDataIterator->getName() != 'themeProfile') {
            throw new Exception('Theme profile must start with a themeProfile node');
        }
        
        if (isset($xmlDataIterator['type'])) {
            $this->_profile->setAttribute('type', (string) $xmlDataIterator['type']);
        }
        
        if (isset($xmlDataIterator['version'])) {
            $this->_profile->setAttribute('version', (string) $xmlDataIterator['version']);
        }
        
        // start un-serialization of the xml doc
        $this->_unserializeRecurser($xmlDataIterator);

        // contexts should be initialized after the unwinding of the profile structure
        $this->_lazyLoadContexts();

        return $this->_profile;

    }
}