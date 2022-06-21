<?php
class CronManager {
  private static $_crontasks = [];
  private static $_init = false;

  public static function newTask($name) {
    if (self::$_init == false)
      self::loadTasks();

    if (self::isCreated($name))
      throw new \LogicException("Task `$name` already registered!");

    $CronTask = new \CronTask($name);
    $CronTask->setData([]);
    self::$_crontasks[$name];
    return $CronTask;
  }

  public static function run($TaskPerRun, $StepsPerRun) {
    if (self::$_init == false)
      self::loadTasks();

    $TaskExecuted = 0;
    foreach (self::$_crontasks as $Task) {
      if (!$Task->requiredToRun())
        continue;

      $Task->run($StepsPerRun);
      $TaskExecuted++;

      if ($TaskPerRun != 0 && $TaskExecuted >= $TaskPerRun)
        break;
    }
  }

  public static function requiredToRun() {
    if (self::$_init == false)
      self::loadTasks();

    foreach (self::$_crontasks as $Task)
      if ($Task->requiredToRun())
        return true;

    return false;
  }

  public static function isCreated($name) {
    if (self::$_init == false)
      self::loadTasks();

    return isset(self::$_crontasks[$name]);
  }

  private static function loadTasks() {
    $DB = \DatabaseManager::GetConnection();
    $Data = $DB->Query('SELECT * FROM `{{prefix}}cron`');

    foreach ($Data->All() as $Row) {
      $Task = \CronTask::initFromData($Row);
      self::$_crontasks[$Row['name']] = $Task;
    }

    $Data->EndData();
    self::$_init = true;
  }
}