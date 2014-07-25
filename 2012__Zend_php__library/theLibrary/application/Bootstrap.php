<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }
    
 	protected function _initMyActionHelpers()
    {

        $this->bootstrap('frontController');

        $auth = Zend_Controller_Action_HelperBroker::getStaticHelper('Auth');
        Zend_Controller_Action_HelperBroker::addHelper($auth);
        
        $loanOverdue = Zend_Controller_Action_HelperBroker::getStaticHelper('LoanOverdue');
        Zend_Controller_Action_HelperBroker::addHelper($loanOverdue);

    }
    
    
    protected function _initDefaultTimezoneSet()
    {
	  date_default_timezone_set('Europe/Berlin');
    }
    
}
?>
