<?php

/**
 * a container contains other renderable objects (anything with a __toString() method)
 * used to group multiple renderable objects together into a single object to be passed around
 */
class container {
  protected $content;

  public function __construct() {
    $this->content = array();
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
        $this->content[] = $content;
      }
    }

    return $this;
  }

  protected function is_renderable($content) {
    if (is_scalar($content))
      return 1;
    elseif (is_array($content))
      return 1;
    elseif (is_object($content) && method_exists($content, '__toString'))
      return 1;

    throw error::expecting_renderable();
  }

  public function __toString() {
    if (empty($this->content))
      return '';

    return implode('', $this->content);
  }
}