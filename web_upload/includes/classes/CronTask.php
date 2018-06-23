<?php
class CronTask {
  private $_name;
  private $_enabled = false;
  private $_lastexec = 0;
  private $_frequency;

  private $_data;
  private $_class;
  private $_function;

  private $_id;
  private $_init;

  public function __construct($name, $init = true) {
    $this->_init = $init;

    $this->checkTask($name);
    $this->_name = $name;
  }

  public static function initFromData($data) {
    $task = new self($data['name'], false);
    $task->setClass($data['class'])->setFunction($data['function'])
      ->setData(unserialize($data['data']))->setFrequency($data['frequency']);

    $task->_lastexec = $data['lastexec'];
    if ($data['enabled'])
      $task->enable();
    else
      $task->disable();

    return $task;
  }

  public function setClass($class) {
    $this->_class = $class;
    return $this;
  }

  public function setFunction($func) {
    $this->_function = $func;
    return $this;
  }

  public function setData($data) {
    $this->_data = $data;
    return $this;
  }

  public function setFrequency($freq) {
    $this->_frequency = $freq;
    return $this;
  }

  public function enable() {
    $this->_enabled = true;
    return $this;
  }

  public function disable() {
    $this->_enabled = false;
    return $this;
  }

  public function save() {
    $DB = \DatabaseManager::GetConnection();

    if ($this->_init) {
      $DB->Prepare('INSERT INTO `{{prefix}}cron` (`enabled`, `name`, `class`, `function`, `data`, `frequency`, `lastexec`) VALUES (:enabled, :name, :class, :func, :data, :freq, :lastexec);');
      $DB->BindData('name', $this->_name);
    } else {
      $DB->Prepare('UPDATE `{{prefix}}cron` SET `enabled` = :enabled, `class` = :class, `function` = :func, `data` = :data, `frequency` = :freq, `lastexec` = :lastexec WHERE `id` = :id');
      $DB->BindData('id',   $this->_id);
    }

    $data = serialize($this->_data);
    $DB->BindMultipleData([
      'enabled'   => $this->_enabled,
      'class'     => $this->_class,
      'func'      => $this->_function,
      'data'      => $data,
      'freq'      => $this->_frequency,
      'lastexec'  => $this->_lastexec
    ]);
    $DB->Finish();
  }

  // run worker
  public function run($StepsPerRun) {
    $data   = $this->_data;
    $result = false;

    $func = [$this->_class, $this->_function];
    $args = [&$data, &$result];

    $StepsRun = 0;
    while (!$result && $StepsPerRun > $StepsRun) {
      call_user_func_array($func, $args);
      $StepsRun++;
    }

    $this->_data = $data;
    if ($result)
      $this->_lastexec = time();

    $this->save();
  }

  public function requiredToRun() {
    if (!$this->_enabled)
      return false;

    $time = time() - $this->_lastexec;
    return ($time > $this->_frequency);
  }

  private function checkTask($name) {
    $DB = \DatabaseManager::GetConnection();
    $DB->Prepare('SELECT `id` FROM `{{prefix}}cron` WHERE `name` = :name');
    $DB->BindData('name', $name);

    $Result = $DB->Finish();
    if ($Result->RowCount() > 0 && $this->_init)
      throw new \LogicException("Task `$name` already registered!");

    $data = $Result->Single();
    $this->_id = $data['id'];

    $Result->EndData();
  }
  /*
  ->setClass('SBCronJob')->setFunction('UpdateChecker')
  ->setData([])->setFrequency(1800)
  ->enable()->save();
  */
}