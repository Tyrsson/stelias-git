<?php
class Dxcore_Controller_Plugin_Loaduser extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        $registry = Zend_Registry::getInstance();

//        $show = NULL;
//        $userinfo = array();
//        $params = $this->getRequest()->getParams();
//
//        $auth = Zend_Auth::getInstance();
//        if ($auth->hasIdentity()) {
//            $this->userInfo = $auth->getIdentity();
//            $registry->set('username', $this->userInfo->username);
//            $request->setParam('username', $this->userInfo->username);
//            $username = $this->userInfo->username;
//            $userinfo['allowed_resources'] = explode(',', 
//            $this->userInfo->allowed_resources);
//            if (is_array($userinfo['allowed_resources'])) {
//                foreach ($userinfo['allowed_resources'] as $key => $value) {
//                    if (in_array($value, $userinfo['allowed_resources'])) {
//                        $show[$value] = true;
//                    } elseif (! in_array($value, 
//                    $userinfo['allowed_resources'])) {
//                        $show[$value] = false;
//                    }
//                }
//
//            }
//
//            if ($this->userInfo->group_id == 1 &&
//             $this->userInfo->userId == 1) 
//             {
//
//            } 
//            elseif ($this->userInfo->group_id == 1) {
//                $user = 'admin';
//                $registry->set('user_group', $user);
//                $request->setParam('user_group', $user);
//            } 
//            elseif ($this->userInfo->group_id == 2) {
//                $user = 'mod';
//                $registry->set('user_group', $user);
//                $request->setParam('user_group', $user);
//            } 
//            elseif ($this->userInfo->group_id == 3 ||
//             $this->userInfo->group_id === NULL) {
//                $user = 'user';
//                $registry->set('user_group', $user);
//                $request->setParam('user_group', $user);
//            }
//        } else {
//            $user = 'guest';
//            $registry->set('user_group', $user);
//            $request->setParam('user_group', $user);
//        }
//
//            $id = $this->getRequest()->getParam('id');
    
    }
}
