<?php
namespace HTTP;

class Response {
  public $ContentType;
  public $Content = '';
  public $Status;

  public function JSON() {
    return @json_decode($this->Content);  
  }
}