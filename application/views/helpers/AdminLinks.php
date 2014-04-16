<?php
/**
 *
 * @author jsmith
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * modLinksHelper
 *
 * @uses 
 */
class Default_View_Helper_AdminLinks extends Zend_View_Helper_Abstract {
	
	public $user;
	public $url;
	public $id;
	public $resource;
	public $priv;
	/**
	 *
	 * @var Zend_View_Interface
	 */
	public $view;
	
	/**
	 * 
	 * @param array associative $urls = array('edit' => '/url/to/edit', 'delete' => '/url/to/delete/)
	 * @param item to be modified $id
	 * @param $resource the ACL resource to be checked for access
	 * @param $priv the acl privilege to check for access level
	 */
	public function adminLinks(array $urls, $id, $resource, $priv) {
        $user = new User_Model_User();
        if (! $user instanceof User_Model_User) {
            return;
        }
        if ($user->isLogged) {
            if ($user->isAllowed($resource, $priv)) {
                $output = '
                		   <div class="adminlink-dd">
                            	<dl>
	    		    				<dd>
	    		    					<a href="' . $urls['edit'] . '/' . $id . '" class="edit">&nbsp;</a>
	    		    					<a href="' . $urls['delete'] . '/' . $id . '" class="delete">&nbsp;</a>
	    		    				</dd>
	    		    	   		</dl>
                           </div>';
                return $output;
            }
        } else {
            return;
        }
	    	
	}
	
	/**
	 * Sets the view field
	 * 
	 * @param $view Zend_View_Interface        	
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}
