<?php

require 'container.php';

/**
 * an element represents an html element
 * it is a container so it can contain other tags as well
 * 
 * besides having shortcuts for setting common attributes,
 * the ::__toString method automatically renders opening/closing
 * tags as well as all attributes
 * 
 * in addition, the tag can be specified as self-closing
 * per valid XHTML practices, in which case contained content
 * will not be rendered
 * 
 * note that in the case of self-closing tags, the content
 * is still added to the inherited internal content array
 * as this class does not override the parent (container)
 * embed method ::__
 * 
 * using this class, it is possible to construct any html tag
 * 
 * like with real html however, the validity of the tag is left
 * to your discretion - for simplicity, this class does no validity
 * checking
 * 
 */
class element extends container {
  protected $name;
  protected $self_closing;
  protected $attributes;
  protected $class;

  /**
   *
   */
  public function __construct($name, $self_closing=0) {
    if (!is_string($name))
      throw error::expecting_string();
    
    // instantiates: $this->content = array();
    parent::__construct();

    // instantiates remaining attributes
    $this->name = $name;
    $this->self_closing = $self_closing ? 1 : 0;
    $this->attributes = array();
    $this->class = array();
  }

  /**
   * set the specified html $attribute.
   * 
   * returns $this for chaining.
   * 
   */
  public function _($attribute, $value=null) {
    if (!is_string($attribute))
      throw error::expecting_string();
    
    if (!isset($value))
      $value = $attribute;
    elseif (!is_string($value))
      throw error::expecting_string();
      
    
    $this->attributes[$attribute] = $value;
    
    return $this;
  }
  
  /**
   * shortcut for setting html attribute 'id'
   * 
   * returns $this for chaining.
   * 
   */
  public function i($id) {
    return $this->_('id', $id);
  }
  
  /**
   * shortcut for setting html attribute 'href'
   * 
   * returns $this for chaining.
   * 
   */
  public function h($href) {
    return $this->_('href', $href);
  }
  
  /**
   * shortcut for setting html attribute 'for'
   *
   * returns $this for chaining.
   *
   */
  public function f($for) {
    return $this->_('for', $for);
  }
  
  /**
   * shortcut for setting html attribute 'action'
   *
   * returns $this for chaining.
   *
   */
  public function a($action) {
    return $this->_('action', BASE_URL.$action);
  }
  
  /**
   * shortcut for setting html attribute 'method'
   *
   * returns $this for chaining.
   *
   */
  public function m($method) {
    return $this->_('method', $method);
  }
  
  /**
   * shortcut for setting html attribute 'name'
   *
   * returns $this for chaining.
   *
   */
  public function n($name) {
    return $this->_('name', $name);
  }
  
  /**
   * shortcut for setting html attribute 'type'
   *
   * returns $this for chaining.
   *
   */
  public function t($type) {
    return $this->_('type', $type);
  }
  
  /**
   * shortcut for setting html attribute 'value'
   *
   * returns $this for chaining.
   *
   */
  public function v($value) {
    return $this->_('value', $value);
  }
  
  /**
   * shortcut for setting html attribute 'style'
   *
   * returns $this for chaining.
   *
   */
  public function s($value) {
    return $this->_('style', $value);
  }

  /**
   * merges the given class(es) into the internal
   * array
   *
   * returns $this for chaining
   *
   */
  public function c($class) {
    if (!is_string($class))
      throw error::expecting_string();

    $class = preg_replace('/\s{2,}/',' ', $class);
    $tmp = explode(' ', $class);
    foreach ($tmp as $class) { 
      $this->class[$class] = 1;
    }

    return $this;
  }
  
  public function __toString() {
    // start opening tag
    $html = '<'. $this->name;

    // render attributes if applicable
    foreach ($this->attributes as $attribute => $value) {
      $html .= " $attribute=\"$value\"";
    }

    // if there is a class attribute, render that
    if (!empty($this->class))
      $html .= ' class="'.implode(' ', array_keys($this->class)).'"';

    // for a self-closing tag, close without rendering content
    if ($this->self_closing) {
      $html .= ' />';
    } else {
      // close the opening tag
      $html .= '>';
      	
      // render any content
      $html .= parent::__toString();
      	
      // close the tag
      $html .= '</'.$this->name.'>';
    }

    return $html;
  }
}

?>