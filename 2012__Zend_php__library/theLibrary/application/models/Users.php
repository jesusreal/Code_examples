<?php

class Application_Model_Users
{
	protected $_firstName;
	protected $_lastName;
	protected $_username;
	protected $_email;
	protected $_password;
	protected $_confirmPassword;
	protected $_role;
	protected $_activationCode;
	protected $_active;
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
            throw new Exception('Invalid users property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid users property');
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
 
    public function setFirstName($text)
    {
        $this->_firstName = (string) $text;
        return $this;
    }
 
    public function getFirstName()
    {
        return $this->_firstName;
    }
    
	public function setLastName($text)
    {
        $this->_lastName = (string) $text;
        return $this;
    }
 
    public function getLastName()
    {
        return $this->_lastName;
    }

	public function setUsername($text)
    {
        $this->_username = (string) $text;
        return $this;
    }
 
    public function getUsername()
    {
        return $this->_username;
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
 
    public function setPassword($ts)
    {
        $this->_password = $ts;
        return $this;
    }
 
    public function getPassword()
    {
        return $this->_password;
    }
    
	public function setConfirmPassword($ts)
    {
        $this->_confirmPassword = $ts;
        return $this;
    }
    
    public function getConfirmPassword()
    {
        return $this->_confirmPassword;
    }
	
    public function unsetConfirmPassword()
    {
        unset($this->_confirmPassword);
        return $this;
    }
    
	public function setRole($role)
    {
        $this->_role = (string) $role;
        return $this;
    }
 
    public function getRole()
    {
        return $this->_role;
    }
 	
 	public function setActive($active)
    {
        $this->_active = (int) $active;
        return $this;
    }
 
    public function getActive()
    {
        return $this->_active;
    }
    
	public function setActivationCode($activationCode)
    {
        $this->_activationCode = (string) $activationCode;
        return $this;
    }
 
    public function getActivationCode()
    {
        return $this->_activationCode;
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

