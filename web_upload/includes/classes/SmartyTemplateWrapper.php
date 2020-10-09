<?php

class SmartyTemplateWrapper extends Smarty_Resource_Custom
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    protected function fetch($name, &$content, &$mtime)
    {
        $resultPath = sprintf('%s/%s', $this->path, $name);
        if (!file_exists($resultPath))
        {
            return false;
        }

        $mtime = filemtime($resultPath);
        $content = file_get_contents($resultPath);
        return true;
    }
}
