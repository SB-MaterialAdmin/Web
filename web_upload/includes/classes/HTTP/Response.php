<?php
namespace HTTP;

class Response {
  public $ContentType;
  public $Content = '';
  public $Status;

  public function JSON($Associative = false, $Depth = 512, $Options = 0) {
    return @json_decode($this->Content, $Associative, $Depth, $Options);
  }
}
