<?php
namespace HTTP\Client;

class Stream extends AbstractClient {
  protected $_supportedMethods  = ['GET', 'POST', 'PUT', 'DELETE'];

  public static function isSupported() {
    return function_exists('stream_context_create');
  }

  protected function runRequest() {
    $context   = $this->getHttpContext();
    $reqStream = fopen($this->_url, 'r', false, $context);
    $response  = $this->response();

    $meta = stream_get_meta_data($reqStream);
    $response->ContentType  = $this->getContentType($meta);
    $response->StatusCode   = $this->getResponseCode($meta);
    $response->Content      = $this->getBody($reqStream);

    fclose($reqStream);
    return $response;
  }

  private function getHttpContext() {
    $headers = [];
    foreach ($this->_headers as $Key => $Value)
      $headers[] = "$Key: $Value";

    return stream_context_create([
      'http'  => [
        'method'        => $this->_method,
        'header'        => implode("\r\n", $headers),
        'content'       => $data,
        'ignore_errors' => true
      ]
    ]);
  }

  private function getResponseCode($meta) {
    $headers = $meta['wrapper_data'];
    foreach ($headers as $Data) {
      preg_match('/^HTTP\/[\d.]{1,} (\d{1,3})/gm', $data, $matches, PREG_OFFSET_CAPTURE);
      if (count($matches) == 1)
        return intval($matches[0][1]);
    }

    return \HTTP\StatusCode::HTTP_UNKNOWNCODE;
  }

  private function getContentType($meta) {
    $headers = $meta['wrapper_data'];
    foreach ($headers as $Data) {
      if (strncmp('Content-Type', $Data, 12) == 0)
        return trim(substr($Data, 13, 0));
    }

    return 'application/octet-stream';
  }

  private function getBody($stream) {
    $data = '';
    while (!$this->streamEOF($stream))
      $data .= fread($stream, 4096);

    return $data;
  }

  private function streamEOF($stream) {
    $meta = stream_get_meta_data($stream);
    return $meta['eof'];
  }
}