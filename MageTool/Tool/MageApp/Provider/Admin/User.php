<?php

/**
 * @see MageTool_Tool_Core_Provider_Abstract
 */
require_once 'MageTool/Tool/MageApp/Provider/Abstract.php';

/**
 * MageTool_Tool_MageApp_Provider_Admin_User provides commands to show and create admin users for Magento
 *
 * @package MageTool_MageApp_Providor_Admin
 * @author Alistair Stead
 **/
class MageTool_Tool_MageApp_Provider_Admin_User extends MageTool_Tool_MageApp_Provider_Abstract
    implements Zend_Tool_Framework_Provider_Pretendable
{
    /**
     * Define the name of the provider
     *
     * @return string
     * @author Alistair Stead
     **/
    public function getName()
    {
        return 'MageAdminUser';
    }

    /**
     * Retrive a list of installed resources
     *
     * @return void
     * @author Alistair Stead
     **/
    public function show()
    {
        $this->_bootstrap();
        
        $userCollection = $configs = Mage::getModel('admin/user')->getCollection();
        $userCollection->load();

        foreach($userCollection as $key => $user) {
            $this->response->appendContent(
                "{$user->getUsername()} <{$user->getEmail()}>",
                array('color' => array('white'))
                );
        }
    }

    /**
     * Create a new admin user
     *
     * @return void
     * @author Alistair Stead
     **/
    public function create($username, $email, $password, $firstname = 'Admin', $lastname = 'User')
    {
        $this->_bootstrap();
        $this->response->appendContent(
            "Creating Magento Admin User:",
            array('color' => array('yellow'))
            );

        //create new user
        $user = Mage::getModel('admin/user')
            ->setData(array(
                'username'  => $username,
                'firstname' => $firstname,
                'lastname'  => $lastname,
                'email'     => $email,
                'password'  => $password,
                'is_active' => 1
            ))->save();

        //create new role
        $role = Mage::getModel("admin/roles")
                ->setName('Development')
                ->setRoleType('G')
                ->save();

        //give "all" privileges to role
        Mage::getModel("admin/rules")
                ->setRoleId($role->getId())
                ->setResources(array("all"))
                ->saveRel();

        $user->setRoleIds(array($role->getId()))
            ->setRoleUserId($user->getUserId())
            ->saveRelations();
    }
}