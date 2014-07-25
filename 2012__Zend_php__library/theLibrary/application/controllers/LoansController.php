<?php

class LoansController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	$this->view->edition = array('First', 'Second','Third','Fourth','Fifth','Sixth','Seventh','Sigth','Ninth','Tenth');
    }

    public function indexAction()
    {
    	$loans = new Application_Model_LoansMapper();
       	$this->view->entries = $loans->fetchAll();
       	$this->view->hola = $loan;
    }

    public function addloanAction()
    {
        // get book ASIN code
    	$asin = $this->_getParam('asin');
 		
    	// get user email
        $storage = new Zend_Auth_Storage_Session();
        $email = $storage->read()->email;
        
        // calculate overdue date
        $days = 14;
    	$overdue = strtotime ('+'.$days.'day', strtotime(date('Y-m-d H:i:s')));
		$overdue = date ( 'Y-m-d H:i:s' , $overdue );
        $this->view->data = $overdue;
        
        // save the loan data
        $data = array (
        		'email' => $email,
        		'asin' => $asin,
        		'overdue' => $overdue
        		);
        try {
			$loans = new Application_Model_Loans($data);
			$mapper  = new Application_Model_LoansMapper();
			$mapper->save($loans);
        } catch (Zend_Exception $e) {
			$this->view->Message = $e->getMessage() . "<br>";
			return;
		}
	
		$overdueFormatted = date('l, jS F Y',strtotime($overdue));
		$this->view->loanMessage = "Thank you for trusting The Library. You have lent this book until <b>". $overdueFormatted . "</b>.";

 		return $this->redirect('/books/');
    }


    public function deleteloanAction()
    {
        $asin = $this->_getParam('asin');
    	$mapper  = new Application_Model_LoansMapper();
        $mapper->deleteLoan($asin);
        $this->redirect('/books/');
    }


}





