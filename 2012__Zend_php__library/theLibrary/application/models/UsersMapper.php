<?php

include_once("DbTable/Users.php");

class Application_Model_UsersMapper
{
	protected $_dbTable;
    
	public function checkUnique($fieldName,$fieldValue)
    {
        $select = $this->getDbTable()->select()
                            ->from($this->getDbTable(),array($fieldName))
                            ->where("$fieldName=?",$fieldValue);
        $users  = new Application_Model_DbTable_Users();
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
            $this->setDbTable('Application_Model_DbTable_Users');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Users $user)
    {
    	$data = array(
            'firstName' => $user->getFirstName(),
            'lastName'   => $user->getLastName(),
            'username'   => $user->getUsername(),
            'email'   => $user->getEmail(),
            'password' => $user->getPassword(),
    		'active' => $user->getActive(),
    		'activationCode' => $user->getActivationCode(),
        );
        if (null === ($id = $user->getId())) {
            unset($data['id']);
            unset($data['active']);
            $this->getDbTable()->insert($data);
        } else {
        	unset($data['ActivationCode']);
            $this->getDbTable()->update($data, array('id = ?' => $id));
            
        }
    }
    
	public function activate($activationCode)
    {
    	$this->getDbTable()->update(array('active' => true), array('activationCode = ?' => $activationCode));
    }
 
    public function find($id, Application_Model_Users $user)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $user->setId($row->id)
                  ->setFirstName($row->firstName)
                  ->setLastName($row->lastName)
                  ->setUsername($row->username)
                  ->setEmail($row->email)
                  ->setPassword($row->password)
                  ->setRole($row->role)
                  ->setActive($row->active);
    }
    
	public function findByField($fieldName, $fieldValue, Application_Model_Users $user)
    {
        $select = $this->getDbTable()->select()
                            ->from($this->getDbTable())
                            ->where("$fieldName=?",$fieldValue);
        $users  = new Application_Model_DbTable_Users();
        $row = $users->getAdapter()->$select;
    	if (0 == count($row)) {
            return;
        }

        //$result = $users->getAdapter()->fetchOne($select);
        //$row = $result->current();
        $user->setId($row->id)
                  ->setFirstName($row->firstName)
                  ->setLastName($row->lastName)
                  ->setUsername($row->username)
                  ->setEmail($row->email)
                  ->setPassword($row->password)
                  ->setRole($row->role)
                  ->setActive($row->active);
        return $user;
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Users();
            $entry->setId($row->id)
                  ->setFirstName($row->firstName)
                  ->setLastName($row->lastName)
                  ->setUsername($row->username)
                  ->setEmail($row->email)
                  ->setPassword($row->password)
                  ->setRole($row->role)
                  ->setActive($row->active)
                  ->setActivationCode($row->activationCode);
            $entries[] = $entry;
        }
        return $entries;
    }
}

