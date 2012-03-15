<?php

/**
 * an element represents an html element
 * it can be embedded with other renderable elements
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
 * like with real html, the validity of the tag is left
 * to discretion - this class does no validity checking
 * 
 */
class element {
  /**
   * string containing rendered embedded content (content is rendered
   * as soon as it is embedded)
   * 
   * @var string
   */
  protected $content;
  
  /**
   * string containing the element's name
   * 
   * @var string
   */
  protected $name;
  
  /**
   * == true - element is self-closing (e.g. <br />)
   * == false - element is not self-closing
   * 
   * @var mixed
   */
  protected $self_closing;
  
  /**
   * array of non-class attributes for the element (e.g. id, etc)
   * 
   * @var array
   */
  protected $attributes;
  
  /**
   * array of classes for the element
   * 
   * @var array
   */
  protected $class;
  
  public function __construct($name, $self_closing=0) {
  	if (!is_string($name))
  		throw error::expecting_string();
  
  	$this->content = '';
  	$this->name = $name;
  	$this->self_closing = $self_closing;
  	$this->attributes = array();
  	$this->class = array();
  }
  
  /**
   * embed renderable content
   *
   * enter each piece of content as
   * as a separate arg
   *
   * e.g. $container->e($content, $more_content, ...)
   *
   * returns $this for chaining
   *
   * renders content in the order it was embedded,
   * see ::__toString method below
   */
  public function e() {
  	$contents = func_get_args();
  
  	foreach ($contents as $content) {
  		if (is_array($content)) {
  		  foreach ($content as $c)
  		    $this->e($c);
  		} elseif ($this->is_renderable($content)) {
  		  $this->content .= ''.$content;
  		}
  	}
  
  	return $this;
  }
  
  protected function is_renderable($content) {
    if (is_scalar($content)) {
      return 1;
    } elseif (is_array($content)) {
      return 1;
    } elseif (is_object($content) && method_exists($content, '__toString')) {
      return 1;
    }
  
  	throw error::expecting_renderable();
  }

  /**
   * set the specified html $attribute.
   * 
   * returns $this for chaining
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
   * returns $this for chaining
   * 
   */
  public function i($id) {
    return $this->_('id', $id);
  }
  
  /**
   * shortcut for setting html attribute 'href'
   * 
   * returns $this for chaining
   * 
   */
  public function h($href) {
    return $this->_('href', $href);
  }
  
  /**
   * shortcut for setting html attribute 'for'
   *
   * returns $this for chaining
   *
   */
  public function f($for) {
    return $this->_('for', $for);
  }
  
  /**
   * shortcut for setting html attribute 'action'
   *
   * returns $this for chaining
   *
   */
  public function a($action) {
    return $this->_('action', $action);
  }
  
  /**
   * shortcut for setting html attribute 'method'
   *
   * returns $this for chaining
   *
   */
  public function m($method) {
    return $this->_('method', $method);
  }
  
  /**
   * shortcut for setting html attribute 'name'
   *
   * returns $this for chaining
   *
   */
  public function n($name) {
    return $this->_('name', $name);
  }
  
  /**
   * shortcut for setting 'id' and 'name' simultaneously to the same value
   * 
   * returns $this for chaining
   */
  public function in($value) {
    return $this->_('id', $value)->_('name', $value);
  }
  
  /**
   * shortcut for setting html attribute 'type'
   *
   * returns $this for chaining
   *
   */
  public function t($type) {
    return $this->_('type', $type);
  }
  
  /**
   * shortcut for setting html attribute 'value'
   *
   * returns $this for chaining
   *
   */
  public function v($value) {
    return $this->_('value', $value);
  }
  
  /**
   * shortcut for setting html attribute 'style'
   *
   * returns $this for chaining
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
      if ($this->content)
        $html .= $this->content;
      	
      // close the tag
      $html .= '</'.$this->name.'>';
    }

    return $html;
  }
}

?>