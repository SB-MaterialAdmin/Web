<?php
class HTTP {
  public static function client() {
    if (extension_loaded('curl'))
      return new \HTTP\Client\cURL();
    else
      return new \HTTP\Client\Stream();
  }

  public static function request($url, $method = 'GET') {
    return new \HTTP\Request($url, $method);
  }

  public static function response() {
    return new \HTTP\Response();
  }
}