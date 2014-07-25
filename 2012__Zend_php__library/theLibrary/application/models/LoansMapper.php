<?php

include_once("DbTable/Loans.php");

class Application_Model_LoansMapper
{
    protected $_dbTable;
 
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
            $this->setDbTable('Application_Model_DbTable_Loans');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Loans $loans)
    {
    	$data = array(
            'email'   => $loans->getEmail(),
            'asin' => $loans->getAsin(),
            'overdue' => $loans->getOverdue(),
            'mailSent' => $loans->getMailSent(),
    	);
 
        if (null === ($id = $loans->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
 
    public function find($id, Application_Model_Loans $loans)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $loans->setId($row->id)
                  ->setEmail($row->email)
                  ->setAsin($row->asin)
                  ->setOverdue($row->overdue)
                  ->setMailSent($row->mailSent);
    }
 	
	public function getLoan($asinCode)
    {
    	$table = new Application_Model_DbTable_Loans(); 	
    	$row = $table->fetchRow(
    		'asin = \''.$asinCode.'\''
   		);
   		if ($row) 
   			return $row;
   		else 
   			return '';
    }
    
	public function deleteLoan($asinCode)
    {
    	$table = new Application_Model_DbTable_Loans(); 	
    	$row = $table->delete(
    		'asin = \''.$asinCode.'\''
    	);
    }
    
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Loans();
            $entry->setId($row->id)
                  ->setEmail($row->email)
                  ->setAsin($row->asin)
                  ->setOverdue($row->overdue)
                  ->setMailSent($row->mailSent);
            $entries[] = $entry;
        }
        return $entries;
    }
}

?>
