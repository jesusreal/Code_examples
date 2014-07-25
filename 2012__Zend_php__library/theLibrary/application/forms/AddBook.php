<?php

class Application_Form_AddBook extends Zend_Form
{
  	
    public function init()
    {
       
       $this->setName('adbook');
       $this->setMethod('post');
      
       // Add the bookcode element
        $this->addElement('text', 'bookcode', array(
            'label'      => 'Please insert a valid ASIN/ISBN code: *',
	        'required'   => true,
        	'validators' => array(
                array('validator' => 'alnum'),
        		array('validator' => 'StringLength', 'options' => 10)
                )
        ));
            
       	// Add the submit button
        $this->addElement('submit', 'savebook', array(
            'ignore'   => true,
            'label'    => 'Add book',
        	'required' => false
        ));
       
   }    
   
}

