<?php
class CJob {
  private $pre  = [];
  private $task;
  private $post = [];

  public function __construct() {}
  public function AddPreCall($call, $args = [])   { $this->pre[]  = ['call' => $call, 'args' => $args]; return $this; }
  public function SetTask($call, $args = [])      { $this->task   = ['call' => $call, 'args' => $args]; return $this; }
  public function AddPostCall($call, $args = [])  { $this->post[] = ['call' => $call, 'args' => $args]; return $this; }

  public function __destruct() {
    foreach ($this->pre as $hook)
      $this->Execute($hook);

    $this->Execute($this->task);

    foreach ($this->post as $hook)
      $this->Execute($hook);
  }

  private function Execute($data) {
    if (!is_callable($data['call']))
      return;

    call_user_func_array($data['call'], $data['args']);
  }

  public function JustReturn($data) {
    return $data;
  }

  public static function factory() {
    return new self();
  }
}