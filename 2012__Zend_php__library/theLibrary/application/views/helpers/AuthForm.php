<?php

class Zend_View_Helper_authForm extends Zend_View_Helper_Abstract
{

    public function authForm(Application_Form_Login $form, $errorMessage)
    {
		$view = new Zend_View();
		$storage = new Zend_Auth_Storage_Session();
        $data = $storage->read();
        //print_r($data);
        $html = "<div id=\"header\" style=\"background-color: #F6FFF6;\">
    			<div id=\"header-logo\" style=\"padding:5px\">"; 
        if($data){       	
        	$html .= "Welcome ". $data->username."";
   		 	$html .= "</div>  <div id=\"header-navigation\" style=\"padding:5px\">";
        	$html .= "<a href=\"".$view->url(array('controller'=>'users','action'=>'logout'))."\">click here to logout</a>";

        } else {
           	$html .= $errorMessage;          
        	$html .= $form->render();          
   		 	$html .= "</div>  <div id=\"header-navigation\" style=\"padding:5px\">";
        	$html .=  "<a href=\"". $view->url(array('controller'=>'users','action'=>'signup')) . "\">New users click here</a>";
        }
		$html .=  "</div> </div>";
        return $html;
    	
    }

}