<?php
class HTTP {
  public static function client() {
    if (\HTTP\Client\cURL::isSupported())
      return new \HTTP\Client\cURL();
    else if (\HTTP\Client\Stream::isSupported())
      return new \HTTP\Client\Stream();
    else 
      throw new \Exception('No available supported HTTP client.');
  }

  public static function request($url, $method = 'GET') {
    return new \HTTP\Request($url, $method);
  }

  public static function response() {
    return new \HTTP\Response();
  }
}
