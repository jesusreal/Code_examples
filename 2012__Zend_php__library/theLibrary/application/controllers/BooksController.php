<?php

class BooksController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	$this->view->edition = array('First', 'Second','Third','Fourth','Fifth','Sixth','Seventh','
    	Eigth','Ninth','Tenth');
    }

    public function indexAction()
    {
    	$books = new Application_Model_BooksMapper();
       	$this->view->entries = $books->fetchAll();
       	foreach ($this->view->entries as $entry):
       		// get loan data
       		$asinCode = $entry->asin;
       		$loansMapper = new Application_Model_LoansMapper();
       		$loan = $loansMapper->getLoan($asinCode);
       		$overdueDate = $loan->overdue;
       		// get user data
       		$storage = new Zend_Auth_Storage_Session();
			$data = $storage->read();
			if ($loan->email == $data->email) {
				$leantByLoggedUser[$asinCode] = true;
			}
			// check conditions
			if (!empty($overdueDate)) {
				$overdueDates[$asinCode] = date('l, jS F Y',strtotime($overdueDate));
			}
       	endforeach;
       	
       	$this->view->overdueDates = $overdueDates;
       	$this->view->leantByLoggedUser = $leantByLoggedUser;
    }

    public function addAction()
    {
        $form = new Application_Form_AddBook();
        $this->view->form = $form; 
        $request = $this->getRequest();
        
                	
        if($request->isPost() ) {
        	$values = $request->getPost();
        	
           if($form->isValid($values)) {
       		//$values = $form->getValues(); or $values = $_POST
                // process data
           		$bookCode = $values['bookcode'];
            	$mapper  = new Application_Model_BooksMapper();
           		if (!$mapper->checkUnique('asin',$bookCode)) {
	            	$amazon = new Zend_Service_Amazon_Query(AMAZON_API_KEY, 'DE', AMAZON_SECRET_KEY);
			  		try {
		               	$item = $amazon->itemLookup($bookCode,array(
			    			'Keywords' => "php",
			    			'AssociateTag' => "php",
				  		   	'IdType'        => 'ASIN',
			   				'ResponseGroup' => 'Small,ItemAttributes,Images,SalesRank,Reviews,' .
		                    'EditorialReview,Similarities'
	  					));
	               } catch (Zend_Exception $e) {
			            $this->view->Message = $e->getMessage() . "<br>";
			            return;
			       }
				       
			  		$item->imageUrl = $item->SmallImage->Url->getHost() . $item->SmallImage->Url->getPath();
					$data = array('asin' => $item->ASIN, 
						'title' => $item->Title,
						'publicationDate' => $item->PublicationDate,
						'numberOfPages' => $item->NumberOfPages,
						'ean' => $item->EAN,
						'edition' => $item->Edition,
						'publisher' => $item->Publisher,
						'formattedPrice' => $item->FormattedPrice,
						'content' => $item->EditorialReviews[0]->Content,
						'detailPageUrl' => $item->DetailPageURL,					
						'imageUrl' => $item->imageUrl,
					);
	                $data = new Application_Model_Books($data);
	                $mapper->save($data);
	                $this->view->entry = $item; 
	                return $this->redirect('/books');
           		} else {
           			$this->view->Message = "This book already exists in the library" ;
           			
           		}
           	                
            }
        }
    }
}

?>