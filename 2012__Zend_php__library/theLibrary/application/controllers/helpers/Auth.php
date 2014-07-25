<?php

class Application_Controller_Helper_Auth extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * Contains the object Zend_Auth
     *
     * @var Zend_Auth
     */
    private $_auth;
 
    /**
     * The object of the singleton class
     *
     * @var Application_Controller_Helper_Auth
     */
    static private $instance = NULL;
 
    /**
     * Constructor
     */
    public function __construct()
    {
       	$this->_auth =  Zend_Auth::getInstance();
        $this->_acl =   new My_Permission_Acl(APPLICATION_PATH."/configs/permissions.ini");
    }
 
    /**
     * Returns the object of the singleton class
     *
     * @return Application_Controller_Helper_Auth
     */
    static public function getInstance() {
       if (self::$instance == NULL) {
          self::$instance = new Application_Controller_Helper_Auth ();
       }
       return self::$instance;
    }
 
  	/**
     * Returns the role of the current user
     * 
     * @return string
     */
    private function getRole()
    {
        return ($this->_auth->hasIdentity()) 
               ? $this->_auth->getIdentity()->role 
               : 'guest';
    }
 
 
    /**
     * preDispatch
     *
     * Function that is executed before the FrontController
     *
     */
    public function preDispatch()
    {
    	$request = $this->getRequest();
    	$controllerName = $request->getControllerName();
        $actionName     = $request->getActionName();
 		
        $form = new Application_Form_Login();       
        $view = $this->getActionController()->view;
        
        // If the user is authenticated
        if ($this->_auth->hasIdentity()) {
            // If hat authorization for the controller
            if (!$this->isAllowed($controllerName, $actionName) ) {
                // Show the error 'denied permission'
                $request->setControllerName("error");
                $request->setActionName("deniedpermission");
            }
        } else {	
	    	//$request = $this->getActionController()->getRequest();
	        //$request = $this->getRequest();
	
	        if($request->isPost() && $request->getPost('save')) {
	            if($form->isValid($request->getPost())) {
	
	                $data = new Application_Model_Users($form->getValues());
	            	//$data= $form->getValues();
					
	                // process data
	               $users  = new Application_Model_DbTable_Users();
	               $auth = Zend_Auth::getInstance();
	               
	               $authAdapter = new Zend_Auth_Adapter_DbTable($users->getAdapter());
	       		   $authAdapter->setTableName('users')
	               				->setIdentityColumn('username')
	             				->setCredentialColumn('password');
	               $authAdapter->setIdentity($data->username)
	               				->setCredential($data->password);
	               				
	               $authAdapter2 = new Zend_Auth_Adapter_DbTable($users->getAdapter());
	       		   $authAdapter2->setTableName('users')
	               				->setIdentityColumn('email')
	             				->setCredentialColumn('password');
	               $authAdapter2->setIdentity($data->username)
	               				->setCredential($data->password);
	               		       
	               /*$result = $auth->authenticate($authAdapter);
	       		   $result2 = $auth->authenticate($authAdapter2);
	       		  */
	               				
	       		  try {
	               		$result = $auth->authenticate($authAdapter);
	       		 		$result2 = $auth->authenticate($authAdapter2);
	               } catch (Zend_Exception $e) {
			            $this->view->errorMessage = $e->getMessage() . "<br>";
			       }
			       
	              
		           if ($result->isValid() || $result2->isValid()) {
		               	if ($result->isValid()) {
		               		$data = $authAdapter->getResultRowObject();
		               	} else if ($result2->isValid()) {
		            		$data = $authAdapter2->getResultRowObject();
		               	}
	               		$storage = new Zend_Auth_Storage_Session();
		               	if ($data->active==true) {
			               	$storage->write($data);
		               	}
		               	else {
		               		$storage->clear();
		               		$view->errorMessage = "<p>User not active, check your email for the email verification or ";
		               		$view->errorMessage .= "<a href=\"".$view->url(array('controller'=>'users','action'=>'sendemail','username'=>$data->username, 
	               			'email'=>$data->email, 'password'=>$data->password, 'activationCode'=>$data->activationCode))
		               			."\">click here</a> to receive again the activation email</p>";
		               	}
					} else {
						$view->errorMessage =  "Invalid username or email and/or password. Please try again.";
					}
	
	            }
	
	        } else {
	        	if (($controllerName!="index" && $controllerName!="users") || ($controllerName=="users"&&$actionName=="index")){
		        	$request->setControllerName("error");
	             	$request->setActionName("deniedpermission");
	        	}
	        }
	
	   }
	   $view->loginForm = $form;
	        
    }
    
	
    public function isAllowed ($resource, $permission = null)
    {
        // By default, no permissions
        $allow = false;
 
        // If it is only asking for the resource
        if (is_null($permission)) {
            $allow = $this->_acl->isAllowed($this->getRole(), $resource);
        }
        // If it is asking for the resource and the permission (permission=subresource in this case)
        else {
            $allow = $this->_acl->isAllowed($this->getRole(), $resource, $permission);
        }
 
        return $allow;
    }
    
/*if (Application_Controller_Helper_Auth::getInstance()->isAllowed('contents','active')) {
    ...
}*/
    	

}