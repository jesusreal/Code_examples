<?php

class Application_Form_Registration extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');

        // Add a first name element
        $this->addElement('text', 'firstName', array(
            'label'      => 'First Name:',
            'required'   => false,
        ));
        
        // Add a last name element
        $this->addElement('text', 'lastName', array(
            'label'      => 'Last Name:',
            'required'   => false,
        ));
                    
        // Add an email element
        $this->addElement('text', 'email', array(
            'label'      => 'Email: *',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
            )
        ));
                
	     // Add the username element
        $this->addElement('text', 'username', array(
            'label'      => 'username: *',
	        'required'   => true,
        	'filters'	=> (array('StringToLower')),
            'validators' => array(
                array('validator' => 'alnum'),
                array('validator' => 'regex', options => array('/^[a-z]+/')),
        		array('validator' => 'StringLength', 'options' => array(5, 50))
                )
        ));
                
       // Add the password element
		$this->addElement('password', 'password', array(
            'label'      => 'Password: *',
	        'required'   => true,
            'validators' => array(
        		array('validator' => 'StringLength', 'options' => array(6))
             )
        ));
                
        // Add the password element
		$this->addElement('password', 'confirmPassword', array(
            'label'      => 'Confirm Password: *',
	        'required'   => true,
            'validators' => array(
        		array('validator' => 'StringLength', 'options' => array(6))
             )
        )); 

        // Add a captcha
        /*$this->addElement('captcha', 'captcha', array(
            'label'      => 'Please enter the 5 letters displayed below:',
            'required'   => true,
            //'captcha'    => 'Dumb'
            'captcha'    => array(
                'captcha' => 'Figlet',
                'wordLen' => 5,
                'timeout' => 300
            )
        ));
		*/
         // Add the submit button
        $this->addElement('submit', 'register', array(
            'ignore'   => true,
            'label'    => 'Sign up',
        ));  

         // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
	}


}

