<?php
class Application_Controller_Helper_LoanOverdue extends Zend_Controller_Action_Helper_Abstract {
	/**
     * preDispatch
     *
     * Function that is executed before the FrontController
     *
     */
    public function preDispatch() {
    	$loansMapper = new Application_Model_LoansMapper();
       	$entries = $loansMapper->fetchAll();
    	//$storage = new Zend_Auth_Storage_Session();
		//$data = $storage->read();
		$config = array('ssl' => 'tls', 'port' => 587, 'auth' => 'login', 'username' => 'fespucio@gmail.com', 'password' => 'zaqwsxza');
   		$smtpConnection = new Zend_Mail_Transport_Smtp('smtp.gmail.com',$config);
    	//Zend_Mail::setDefaultTransport($smtpConnection);
    	$mail = new Zend_Mail();
   		$currentTime = time();
   		foreach ($entries as $entry):
       		if ( !$entry->MailSent && ($currentTime>strtotime($entry->overdue)) ) { //&& $data->email==$entry->email
		    	$booksMapper = new Application_Model_BooksMapper();
       			$book = $booksMapper->getBook($entry->asin);
       			$mensaje = "Library loan. Overdue date expired.\n\n";
				$mensaje .= "Dear user,\n";
				$mensaje .= "The return date of the book with ASIN code: $entry->asin and title: '$book->title' has expired.
							Please give it back as soon as possible\n\n";
				$mensaje .= "Thank you very much for using our service. \n\nBest regards, \n The Library loan system team";
		   		$mail->setFrom('thelibrarynoreply@gmail.com', 'The Library loan service')
		        	->addTo($entry->email, 'The library user')
		        	->setSubject('Overdue date expired')
		        	->setBodyText($mensaje);
		        	//->send($smtpConnection);
					//->setBodyHtml("<h1>Body text with HTML tags</h1>");
				$mail->send($smtpConnection);
				$entry->setMailSent(true);
				$loansMapper->save($entry);       		
       		}
		      	
       	endforeach;
    }
	
}
