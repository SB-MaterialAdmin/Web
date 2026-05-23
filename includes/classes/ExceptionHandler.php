<?php

class ExceptionHandler
{
    public static function handle($e)
    {
        while (ob_get_level() > 0)
            ob_end_clean();

        http_response_code(500);

        $name    = get_class($e);
        $message = $e->getMessage();
        $path    = clearSystemPath($e->getFile());
        $line    = $e->getLine();

        error_log("Exception: {$name}: {$message} in {$path}:{$line}");
        error_log(self::getStackTraceText($e->getTrace()));

        echo("An exception occured: [<b>{$name}</b>] <b>{$message}</b> in <i>{$path}</i> on line {$line} <br />");
        self::printStackTrace($e->getTrace());
        exit();
    }

    private static function printStackTrace($trace = [])
    {
        echo('<ol>');
        foreach ($trace as $id => $data) {
            $function = $data['function'] ?? 'unknown function';
            $line = $data['line'] ?? '?';
            $file = isset($data['file']) ? clearSystemPath($data['file']) : 'unknown';

            $class = $data['class'] ?? '';
            $type = $data['type'] ?? '';

            $argsList = isset($data['args']) && is_array($data['args']) ? $data['args'] : [];
            $args     = [];
            foreach ($argsList as $arg) {
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

    private static function getStackTraceText($trace = [])
    {
        $text = '';
        foreach ($trace as $i => $data) {
            $file = isset($data['file']) ? clearSystemPath($data['file']) : 'unknown';
            $line = $data['line'] ?? '?';
            $func = $data['function'] ?? 'unknown';
            $class = $data['class'] ?? '';
            $type = $data['type'] ?? '';
            $text .= sprintf("  #%d %s%s%s() called at %s:%s\n", $i, $class, $type, $func, $file, $line);
        }
        return $text;
    }
}
