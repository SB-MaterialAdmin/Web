<?php
require(INCLUDES_PATH . '/smarty/Smarty.class.php');

class CSmarty extends Smarty {
  public $template_user_dir;
  public function fetch($resource_name, $cache_id = null, $compile_id = null, $display = false) {
    $file = BuildPath(false, $this->template_user_dir, $resource_name);
    $old_dir = $this->template_dir;

    if (@file_exists($file) && @is_file($file)) {
      $this->template_dir = $this->template_user_dir;

      if ($compile_id !== null)
        $compile_id .= "_mod";
      else
        $compile_id = $this->compile_id . "_mod";
    }

    parent::fetch($resource_name, $cache_id, $compile_id, $display);
    $this->template_dir = $old_dir;
  }
}