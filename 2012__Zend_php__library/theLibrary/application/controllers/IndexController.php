<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	// action body
        $storage = new Zend_Auth_Storage_Session();
		$data = $storage->read();	
	    if(!$data){    
			$this->view->disableLogin = true;
		}
    	
    }


}

