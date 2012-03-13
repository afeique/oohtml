<?php

require_once 'element.php';

/**
 * this file simply contains numerous helper methods
 * used to generate oohtml
 */

/**
 * blocks are chunks of content drawn from /blocks
 * they are passed through an output buffer, so it is 
 * okay to embed PHP within a block
 */
function b($block) {
  ob_start();
  require "blocks/$block.php";
  $content = ob_get_clean();
  
  return $content;
}

/**
 * 'l' stands for 'element'
 * as in 'html element'
 */
function l($name) {
  return new element($name);
}

/**
 * self-closing element
 * aka a self-blocking l
 * aka l-blocking-l
 * aka ll
 */
function ll($name) {
  return new element($name, $self_closing=1);
}

function html() {
  $args = func_get_args();
  return l('html')->e($args);
}

function head() {
  $args = func_get_args();
  return l('head')->e($args);
}

function body() {
  $args = func_get_args();
  return l('body')->e($args);
}

function h1() {
  $args = func_get_args();
  return l('h1')->e($args);
}

function h2() {
  $args = func_get_args();
  return l('h2')->e($args);
}

function h3() {
  $args = func_get_args();
  return l('h3')->e($args);
}

function ul() {
  $args = func_get_args();
  return l('ul')->e($args);
}

function ol() {
  $args = func_get_args();
  return l('ol')->e($args);
}

function li() {
  $args = func_get_args();
  return l('li')->e($args);
}

function span() {
  $args = func_get_args();
  return l('span')->e($args);
}

function p() {
  $args = func_get_args();
  return l('p')->e($args);
}

function em() {
  $args = func_get_args();
  return l('em')->e($args);
}

function strong() {
  $args = func_get_args();
  return l('strong')->e($args);
}

function sub() {
  $args = func_get_args();
  return l('sub')->e($args);
}

function sup() {
  $args = func_get_args();
  return l('sup')->e($args);
}

function script() {
  $args = func_get_args();
  return l('script')->t('text/javascript')->e($args);
}

/**
 * returns an HTML5 compatible doctype
 * 
 * @return string
 */
function html5_doctype() {
  return '<!DOCTYPE html>';
}

/**
 * casts specified content to string, applies htmlspecialchars(),
 * then returns a title element containing the content
 * 
 * @param mixed $text
 * @return element
 */
function title($content) {
  return l('title')->e(htmlspecialchars(''.$content));
}

/**
 * returns a link element potinting to a stylesheet at the given URL
 * 
 * @param string $url
 * @param string $media
 * @return element
 */
function css_link($url, $media='all') {
  if (!is_string($url))
    throw error::expecting_string();
  if (!is_string($media))
    throw error::expecting_string();
  
  return ll('link')->_('rel','stylesheet')->t('text/css')->h($url)->_('media', $media);
}

/**
 * returns an empty script element with src at the given URL
 * 
 * @param string $url
 * @return element
 */
function script_src($url) {
  if (!is_string($url))
    throw error::expecting_string();
  
  return l('script')->t('text/javascript')->_('src', $url);
}

/**
 * returns a meta charset element
 * 
 * @param string $charset
 * @return element
 */
function meta_charset($charset='utf-8') {
  if (!is_string($charset))
    throw error::expecting_string();
  
  return ll('meta')->_('http-equiv','Content-Type')->_('content',"text/html; charset=$charset");
}

/**
 * returns an "html if" string; useful for those IE fixes
 * 
 * @param string $condition
 * @param mixed $content
 * @return string
 */
function html_if($condition, $content) {
  if (!is_string($condition))
    throw error::expecting_string();
  
  return "<!--[if $condition]>$content<![endif]-->";
}

/**
 * "a" link; an "a"rbitrary link (aka your run-of-the-mill hyperlink)
 * 
 * @param string $href
 * @param mixed $content
 * @return element
 */
function a_link($href, $content) {
  if (!is_string($href))
    throw error::expecting_string();
  
  return l('a')->h($href)->e($content);
}

/**
 * a "b"lank link; target='_blank'; opens in a new window
 * 
 * @param string $href
 * @param mixed $content
 * @return element
 */
function b_link($href, $content) {
  return a_link($href, $content)->_('target','_blank');
}

/**
 * returns a checkbox object
 * 
 * @return element
 */
function checkbox() {
  return l('input')->t('checkbox');
}

/**
 * redirect to another page using PHP's header() function
 * 
 * @param string $url
 */
function header_redirect($url) {
  if (!is_string($url))
    throw error::expecting_string();
  header('Location: '.$url);
}

function code($text) {
  if (!is_string($text))
    throw error::expecting_string();
  
  return l('span')->c('code')->e(htmlentities($text));
}