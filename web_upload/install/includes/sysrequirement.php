<?php
class SB_SysRequirement {
  private $required;
  private $recommended;
  private $how_to_check;

  private $display;

  public function __construct() {
    return $this;
  }

  public function SetRequired($value) {
    $this->required = $value;
    return $this;
  }

  public function SetRecommended($value) {
    $this->recommended = $value;
    return $this;
  }
}