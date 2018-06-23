<?php
namespace HTTP\Client;

abstract class AbstractClient {
  protected $_url;
  protected $_body = '';
  protected $_method;
  protected $_headers;

  private $_response = NULL;

  protected $_supportedMethods = ['GET', 'POST'];

  abstract protected function runRequest();
  abstract public static function isSupported();

  public function isSupportedMethod($method) {
    return in_array($method, $this->_supportedMethods);
  }

  public function setUrl($url) {
    $this->_url = $url;
    return $this;
  }

  public function setHeaders($headers) {
    $this->_headers = $headers;
    return $this;
  }

  public function setBody($body) {
    $this->_body = $body;
    return $this;
  }

  public function setMethod($method) {
    if (!$this->isSupportedMethod($method))
      throw new \LogicException('Unsupported HTTP method.');

    $this->_method = $method;
    return $this;
  }

  public function send() {
    if (!isset($this->_headers['User-Agent']))
      $this->_headers['User-Agent'] = 'PHP/' . phpversion();

    return $this->runRequest();
  }

  protected function response() {
    if ($this->_response === NULL)
      $this->_response = \HTTP::response();
    return $this->_response;
  }
}