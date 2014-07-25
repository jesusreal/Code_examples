<?php

class Application_Form_Login extends Zend_Form
{
  	
    public function init()
    {
       
       $this->setName('login');
       $this->setMethod('post');
      
       // Add the username element
        $this->addElement('text', 'username', array(
            'label'      => 'username or Email: *',
	        'required'   => true,
        ));
       
		// Add the password element
		$this->addElement('password', 'password', array(
            'label'      => 'Password: *',
	        'required'   => true,
        ));
            
       	// Add the submit button
        $this->addElement('submit', 'save', array(
            'ignore'   => true,
            'label'    => 'Login',
        	'required' => false
        ));
       
   }    
   
}

