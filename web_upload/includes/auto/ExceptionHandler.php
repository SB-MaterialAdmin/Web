<?php
class ExceptionHandler {
  public static function handle($e, $kill = true) {
    while (ob_get_level() > 0)
      ob_end_clean();

    $name    = get_class($e);
    $message = $e->getMessage();
    $path    = clearSystemPath($e->getFile());
    $line    = $e->getLine();

    echo("An exception occured: [<b>{$name}</b>] <b>{$message}</b> in <i>{$path}</i> on line {$line} <br />");
    self::printStackTrace($e->getTrace());

    if ($kill)
      exit();
  }

  private static function printStackTrace($trace = []) {
    echo('<ol>');
    foreach ($trace as $id => $data) {
      $function = $data['function'];
      $line     = $data['line'];
      $file     = clearSystemPath($data['file']);

      $class    = isset($data['class']) ? $data['class'] : '';
      $type     = isset($data['type'])  ? $data['type']  : '';

      $args     = [];
      foreach ($data['args'] as $arg) {
        if (is_object($arg))
          $args[] = get_class($arg);
        else
          $args[] = gettype($arg);
      }
      $args = implode(', ', $args);

      echo("<li><b>{$class}{$type}{$function}</b>({$args}) in <i>{$file}</i> at line {$line}</li>");
    }
    echo('</ol>');
  }
}