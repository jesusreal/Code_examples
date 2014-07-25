<?php

class Application_Model_Loans
{
	protected $_asin;
    protected $_email;
	protected $_overdue;
	protected $_mailSent;
    protected $_id;
 
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
 
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid loans property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid loans property');
        }
        return $this->$method();
    }
 
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
 
	public function setAsin($text)
    {
        $this->_asin = (string) $text;
        return $this;
    }
 
    public function getAsin()
    {
        return $this->_asin;
    }
 
    public function setEmail($email)
    {
        $this->_email = (string) $email;
        return $this;
    }
 
    public function getEmail()
    {
        return $this->_email;
    }
 
 	public function setOverdue($ts)
    {
        $this->_overdue = $ts;
        return $this;
    }
 
    public function getOverdue()
    {
        return $this->_overdue;
    }
 	
	public function setMailSent($mailSent)
    {
        $this->_mailSent = (int) $mailSent;
        return $this;
    }
 
    public function getMailSent()
    {
        return $this->_mailSent;
    }
    
    public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }
 
    public function getId()
    {
        return $this->_id;
    }
}
