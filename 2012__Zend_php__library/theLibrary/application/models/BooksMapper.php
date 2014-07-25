<?php

include_once("DbTable/Books.php");

class Application_Model_BooksMapper
{
	protected $_dbTable;
 
	public function checkUnique($fieldName,$fieldValue)
    {
        $select = $this->getDbTable()->select()
                       ->from($this->getDbTable(),array($fieldName))
                       ->where("$fieldName=?",$fieldValue);
        $users  = new Application_Model_DbTable_Books();
        $result = $users->getAdapter()->fetchOne($select);
        if($result){
            return true;
        }
        return false;
    }
	
	public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Books');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Books $books)
    {
        $data = array(
            'asin'   => $books->getAsin(),
            'title' => $books->getTitle(),
			'publicationDate' => $books->getPublicationDate(),
			'numberOfPages' => $books->getNumberOfPages(),
			'ean' => $books->getEan(),
			'edition' => $books->getEdition(),
			'publisher' => $books->getPublisher(),
			'formattedPrice' => $books->getFormattedPrice(),
			'detailPageUrl' => $books->getDetailPageUrl(),
			'imageUrl' => $books->getImageUrl(),
            'content' => $books->getContent(),
        );
 
        if (null === ($id = $books->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
    
    public function find($id, Application_Model_Books $books)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $books->setId($row->id)
                  ->setAsin($row->asin)
                  ->setEan($row->ean)
                  ->setTitle($row->title)
                  ->setContent($row->content)
                  ->setEdition($row->edition)
                  ->setPublisher($row->publisher)
                  ->setPublicationDate($row->publicationDate)
                  ->setNumberOfPages($row->numberOfPages)
                  ->setFormattedPrice($row->formattedPrice)
                  ->setDetailPageUrl($row->detailPageUrl)
                  ->setImageUrl($row->imageUrl);
    }
    
	public function getBook($asinCode)
    {
    	$table = new Application_Model_DbTable_Books(); 	
    	$row = $table->fetchRow(
    		'asin = \''.$asinCode.'\''
   		);
   		if ($row) 
   			return $row;
   		else 
   			return '';
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Books();
            $entry->setId($row->id)
                  ->setAsin($row->asin)
                  ->setEan($row->ean)
                  ->setTitle($row->title)
                  ->setContent($row->content)
                  ->setEdition($row->edition)
                  ->setPublisher($row->publisher)
                  ->setPublicationDate($row->publicationDate)
                  ->setNumberOfPages($row->numberOfPages)
                  ->setFormattedPrice($row->formattedPrice)
                  ->setDetailPageUrl($row->detailPageUrl)
                  ->setImageUrl($row->imageUrl);
            $entries[] = $entry;
        }
        return $entries;
    }

}

