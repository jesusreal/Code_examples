<?php

class UsersController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	
    }

    public function indexAction()
    {
		$users = new Application_Model_UsersMapper();
    	$this->view->entries = $users->fetchAll();
    }

    public function loginAction() {
        $this->view->disableLogin = true;
    	$request = $this->getRequest();
    	$form = new Application_Form_Login();
  		$view->loginForm = $form;
    	
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
	               		       
	               $result = $auth->authenticate($authAdapter);
	       		   $result2 = $auth->authenticate($authAdapter2);
	       		  
	       		  //try {
	              // 		$result = $auth->authenticate($authAdapter);
	       		 //		$result2 = $auth->authenticate($authAdapter2);
	              // } catch (Zend_Exception $e) {
			       //     $this->view->errorMessage = $e->getMessage() . "<br>";
			       //}
			       
	              
		           if ($result->isValid() || $result2->isValid()) {
		               	if ($result->isValid()) {
		               		$data = $authAdapter->getResultRowObject();
		               	} else if ($result2->isValid()) {
		            		$data = $authAdapter2->getResultRowObject();
		               	}
	               		$storage = new Zend_Auth_Storage_Session();
		               	if ($data->active==true) {
			               	$storage->write($data);
			               	$this->redirect('/');
		               	}
		               	else {
		               		$storage->clear();
		               		$view->errorMessage = "<p>User not active, check your email for the email verification or ";
		               		$view->errorMessage .= "<a href=\"".$this->view->url(array('controller'=>'users','action'=>'sendemail','username'=>$data->username, 
	               			'email'=>$data->email, 'password'=>$data->password, 'activationCode'=>$data->activationCode))
		               			."\">click here</a> to receive again the activation email</p>";
		               	}
					} else {
						$view->errorMessage =  "Invalid username or email and/or password. Please try again.";
					}
	
	            }
	
	        }
    }
    
    public function signupAction()
    {
        $this->view->disableLogin = true;
        $request = $this->getRequest();
        $form = new Application_Form_Registration();
        $this->view->form = $form;
        
        if ($request->isPost() && $request->getPost('register')) {
            if ($form->isValid($request->getPost())) {
            	$mapper = new Application_Model_UsersMapper();
        	    $data = new Application_Model_Users($form->getValues());
            	if($data->password != $data->confirmPassword){
                    $this->view->errorMessage = "Password and confirm password don't match.";
                    return;
                }
                if($mapper->checkUnique('username',$data->username)){
                    $this->view->errorMessage = "Name already taken. Please choose another one.";
                    return;
                }
            	if($mapper->checkUnique('email',$data->email)){
                    $this->view->errorMessage = "Email already taken. Please choose another one.";
                    return;
                }
                $data->activationCode = uniqid();

                $mapper->save($data);//
                
               $this->redirect($this->view->url(array('controller'=>'users','action'=>'sendemail', 'username'=>$data->username, 
               			'email'=>$data->email, 'password'=>$data->password, 'activationCode'=>$data->activationCode)));
        	} 
        
        }   
    }

    public function logoutAction()
    {
    	$storage = new Zend_Auth_Storage_Session();
        $storage->clear();
        $this->_redirect('/');
    }

    public function sendemailAction()
    {
   		$this->view->disableLogin=true;
    	$username = $this->_getParam("username");
   		$email = $this->_getParam('email');
   		$password = $this->_getParam('password');
   		$activationCode = $this->_getParam('activationCode');
   		//$this->_setParam( "username", null );
   		
   		
   		$config = array('ssl' => 'tls', 'port' => 587, 'auth' => 'login', 'username' => 'fespucio@gmail.com', 'password' => 'zaqwsxza');
   		$smtpConnection = new Zend_Mail_Transport_Smtp('smtp.gmail.com',$config);
    	//Zend_Mail::setDefaultTransport($smtpConnection);
    	$mensaje = "Register in The Library. Account verification.\n\n'";
		$mensaje .= "This is your login data:\n";
		$mensaje .= "username: $username.\n";
		$mensaje .= "password: $password.\n\n";
		$mensaje .= "You have to activate your account by clicking on the following link: http://thelibrary.local/users/activation/activationCode/$activationCode\n\n";
		$mensaje .= "Thank you very much for using our service. \n\nBest regards, \n The Library registration team";
		$mail = new Zend_Mail();
   		$mail->setFrom('thelibrarynoreply@gmail.com', 'The Library registration service')
        	->addTo($email, 'New user registered on The Library')
        	->setSubject('The library: account verification email')
        	->setBodyText($mensaje);
        	//->send($smtpConnection);
			//->setBodyHtml("<h1>Body text with HTML tags</h1>");
        	
      	if($mail->send($smtpConnection)) {
		    $this->view->emailMessage =  "An email has been sent to your email account with de activation code."; 
		}else{
		    $this->view->emailMessage =  "There has been an error and the email could not been sent.";
		}
   	
    }

    public function activationAction()
    {
		$this->view->disableLogin=true;
    	$activationCode	 = $this->_getParam('activationCode');
		$mapper = new Application_Model_UsersMapper();
		$mapper->activate($activationCode);
		//return $this->redirect('/users');
    }


}











