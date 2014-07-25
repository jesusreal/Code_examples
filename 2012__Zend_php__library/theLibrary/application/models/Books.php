<?php

class Application_Model_Books
{
	protected $_asin;
	protected $_ean;
    protected $_title;
    protected $_content;
    protected $_edition;
    protected $_publisher;
    protected $_publicationDate;
    protected $_numberOfPages;
    protected $_formattedPrice;
    protected $_detailPageUrl;
    protected $_imageUrl;
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
            throw new Exception('Invalid book property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid book property');
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
    
	public function setEan($text)
    {
        $this->_ean = (string) $text;
        return $this;
    }
 
    public function getEan()
    {
        return $this->_ean;
    }
 
    public function setTitle($title)
    {
        $this->_title = (string) $title;
        return $this;
    }
 
    public function getTitle()
    {
        return $this->_title;
    }
 
    public function setContent($content)
    {
        $this->_content = (string) $content;
        return $this;
    }
 
    public function getContent()
    {
        return $this->_content;
    }
    
	public function setEdition($edition)
    {
        $this->_edition = (string) $edition;
        return $this;
    }
 
    public function getEdition()
    {
        return $this->_edition;
    }
    
	public function setPublisher($publisher)
    {
        $this->_publisher = (string) $publisher;
        return $this;
    }
 
    public function getPublisher()
    {
        return $this->_publisher;
    }
    
	public function setPublicationDate($publicationDate)
    {
        $this->_publicationDate = (string) $publicationDate;
        return $this;
    }
 
    public function getPublicationDate()
    {
        return $this->_publicationDate;
    }
    
public function setNumberOfPages($numberOfPages)
    {
        $this->_numberOfPages = (string) $numberOfPages;
        return $this;
    }
 
    public function getNumberOfPages()
    {
        return $this->_numberOfPages;
    }
    
	public function setFormattedPrice($formattedPrice)
    {
        $this->_formattedPrice = (string) $formattedPrice;
        return $this;
    }
 
    public function getFormattedPrice()
    {
        return $this->_formattedPrice;
    }
    
	public function setDetailPageUrl($detailPageUrl)
    {
    	$this->_detailPageUrl = (string) $detailPageUrl;
        return $this;
    }
 
    public function getDetailPageUrl()
    {
        return $this->_detailPageUrl;
    }
    
	public function setImageUrl($imageUrl)
    {
    	$this->_imageUrl = (string) $imageUrl;
        return $this;
    }
 
    public function getImageUrl()
    {
        return $this->_imageUrl;
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

