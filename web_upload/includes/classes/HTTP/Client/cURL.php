<?php
namespace HTTP\Client;

class cURL extends AbstractClient {
  public static function isSupported() {
    return function_exists('curl_init');
  }

  protected function runRequest() {
    $curl = curl_init();
    if (!is_resource($curl))
      throw new \Exception('Cannot initialize cURL client.');

    if ($this->_method == 'GET') {
      curl_setopt($curl, CURLOPT_URL, $this->_url . '?' . $this->_body);
    } else if ($this->_method == 'POST') {
      curl_setopt($curl, CURLOPT_URL, $this->_url);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $this->_body);
    }

    curl_setopt($curl, CURLOPT_HTTPHEADER, $this->_headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = $this->response();
    $response->Content      = curl_exec($curl);
    $response->Status       = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $response->ContentType  = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);

    curl_close($curl);
    return $response;
  }
}
