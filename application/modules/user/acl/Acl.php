<?php
class User_Acl_Acl extends Zend_Acl
{
		public $user;
        public function __construct($auth = null)
        {
            $guest = new User_Acl_Role_Guest();
            $mod   = new User_Acl_Role_Mod();
            $admin = new User_Acl_Role_Admin();
            $temp = new User_Acl_Role_User();
            $defaultRole = $temp->getDefaultRole();
            $userRole = new User_Acl_Role_User();

            $this->addRole('guest');
            $this->addRole('user', 'guest'); //TODO: return this from db query through User_Acl_Role_User()
            $this->addRole($mod, $mod->_inheritsFrom);
            $this->addRole($admin, $admin->_inheritsFrom);
            // Create the Dirextion role
            $this->addRole('dxadmin', $admin);
            // These must be strings to reduce the number of includes when loading the acl for CkFinder

            $this->addResource(new Zend_Acl_Resource('login')); //<- Allows the hiding of the login navigation tab
            $this->addResource(new Zend_Acl_Resource('logout')); //<- Allows the hiding of the logout navigation tab
            $this->addResource(new Zend_Acl_Resource('admin:area')); //<- temp for testing

            $this->addResource(new Zend_Acl_Resource('news'));
            $this->addResource(new Zend_Acl_Resource('content'));
            $this->addResource(new Zend_Acl_Resource('pages'));
            $this->addResource(new Zend_Acl_Resource('register'));
            $this->addResource(new Zend_Acl_Resource('user'));
            $this->addResource(new Zend_Acl_Resource('usermodule'));
            $this->addResource(new Zend_Acl_Resource('useraccount'));
            $this->addResource(new Zend_Acl_Resource('guestbook'));
            $this->addResource(new Zend_Acl_Resource('products'));
            $this->addResource(new Zend_Acl_Resource('media.files'));
            $this->addResource(new Zend_Acl_Resource('media.albums', 'media.files'));
            $this->addResource(new Zend_Acl_Resource('media', 'media.albums'));
            $this->addResource(new Zend_Acl_Resource('dxadmin-tools'));
            //$this->deny();

            $this->addResource(new Zend_Acl_Resource('testimonials'));
            $this->addResource(new Zend_Acl_Resource('festival'));
            // $this->deny();



            $this->allow($guest, array('pages'), array('pages.guest.view'));
            $this->allow($guest, array('user', 'pages', 'testimonials'), array('user.login', 'user.register', 'pages.view.comment', 'pages.view.image', 'pages.view.icon', 'testimonials.view.all'));
            $this->allow('user', array('user', 'useraccount', 'pages'), array('user.view', 'user.logout',
            																  'user.account-editown', 'user.edit-account', //<-end user privs
            																  ));


            $this->allow('user', array('pages'), array('pages.user.view', 'pages.view.comment', 'pages.edit.comment.own', 'pages.post.comment', 'pages.view.file'));
            $this->allow('user', array('testimonials'), array('testimonials.post.new', 'testimonials.edit.own'));
            $this->allow('mod', array('pages'), array('pages.mod.view', 'pages.view.admin.menu', 'pages.edit.comment',
            										  'pages.edit.file', 'pages.edit.icon', 'pages.edit.image', 'pages.edit.content',
            										  'testimonials.edit.all'));

            $this->allow('mod', array('media.files', 'media.albums'), array('media.manage'));
            $this->allow('admin', array('media', 'media.albums', 'media.files'), array('admin.media', 'admin.media.delete'));

            $this->allow('admin', array('pages'), array('pages.manage', 'pages.admin.view'));
            $this->allow('admin', array('user','useraccount'), array('admin:view', 'admin:submit', 'admin:edit', 'admin:delete', 'admin:summary', 'user.admin-create-user', 'user.admin-edit-user', 'admin.edit.user'));
            $this->allow('admin', array('admin:area', 'news', 'pages'), array('admin.base', 'admin:view', 'admin:submit', 'admin:edit', 'admin:delete', 'admin:summary', 'admin-general-settings', 'manage-all'));

            $this->allow('admin', array('testimonials'), array('testimonials.manage'));
            $this->allow('admin', array('festival'), array('manage.orders'));

            $this->allow('dxadmin', array('dxadmin-tools', 'testimonials'), array('dxadmin.manage.all'));
            $this->allow('dxadmin');
            //$this->deny('admin', array('dxadmin-summary'));
            $this->deny('user', array('user'), array('user.login', 'user.register'));
            /**
             * The below prevents logged users from seeing the login/register tabs
             */
            //$this->deny(new Dxcore_Acl_Role_User(), null, array('guest:login', 'guest:register'));
        }
        protected function addRoles()
        {
        	$rModel = new User_Model_AclRoles();
        	$roles = $rModel->getRoles();
        	//Zend_Debug::dump($rModel->getRoles());
        	$roles = array_reverse($roles);
        	foreach($roles as $role)
        	{
        		if($role['inheritsFrom'] == 'none')
        		{
        			$this->addRole($role['role']);
        			continue;
        		}
        		if($role['inheritsFrom'] !== 'none')
        		{
        			$this->addRole($role['role'], $role['inheritsFrom']);
        			continue;
        		}
        	}
        }
        protected function addResources()
        {
        	$resources = $this->pModel->getModules();
        	$values = array_unique($resources);
        	foreach($values as $resource)
        	{
        		$this->addResource(new Zend_Acl_Resource($resource['module']));
        	}
        }
        protected function addPrivileges()
        {
        	$dataArray = $this->pModel->getPrivileges();
        	foreach($dataArray as $data) :

				$this->allow ( $data['role'], $data['module'], ($data['specialPrivilege'] !== 'none') ? $data['specialPrivilege'] : $data['action'] );

		    endforeach;

        }
}