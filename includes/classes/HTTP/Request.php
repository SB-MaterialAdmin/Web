<?php
namespace HTTP;

class Request {
  private $_url;
  private $_data;
  private $_method;
  private $_headers;

  public function __construct($url, $method = 'GET') {
    if (!in_array($method, ['GET', 'POST'], true))
      throw new \LogicException("Unknown HTTP method $method");

    $this->_method = $method;
    $this->_url    = $url;
  }

  public function addHeader($name, $value = NULL) {
    if ($value == NULL) {
      if (isset($this->_headers[$name]))
        unset($this->_headers[$name]);
      return $this;
    }

    $this->_headers[$name] = $value;
    return $this;
  }

  public function setData($data, $type = 'query') {
    switch ($type) {
      case 'query': {
        $this->_data = http_build_query($data);
        $type = 'application/x-www-form-urlencoded';
        break;
      }

      case 'json':  {
        $this->_data = json_encode($data);
        $type = 'application/json';
      }

      default:
        throw new \LogicException("Unknown data type $type");
    }

    $this->addHeader('Content-Type',    $type);
    $this->addHeader('Content-Length',  strlen($this->_data));

    return $this;
  }

  public function run($url = '') {
    $requestUrl = $this->_url . '/' . $url;

    return \HTTP::client()->setUrl($requestUrl)
      ->setMethod($this->_method)->setBody($this->_data)
      ->setHeaders($this->_headers)->send();
  }
}