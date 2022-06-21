<?php

class StreamWrapper {
    /**
     * Helpful utilities for using one Stream Wrapper for all our custom paths.
     */
    protected static $virtualHosts = [];
    protected static $hooks = [];

    public static function addVirtualHost($host, $path)
    {
        self::$virtualHosts[$host] = $path;
    }

    public static function addHook($host, \Closure $hook)
    {
        if (!in_array($host, self::$hooks))
        {
            self::$hooks[$host] = [];
        }

        self::$hooks[$host][] = $hook;
    }

    /**
     * Wrapper.
     */
    protected $stream;
    protected $realPath;

    protected function fireHooks($hostProtocol, &$path, $relativePath)
    {
        if (!array_key_exists($hostProtocol, self::$hooks))
        {
            return;
        }

        foreach (self::$hooks[$hostProtocol] as $hookClosure)
        {
            $hookClosure($path, $relativePath);
        }
    }

    public function stream_open($path, $mode, $options, &$opened_path) {
        $opened_path = $this->resolveToRealPath($path);
        $this->stream = fopen($opened_path, $mode, true);

        return true;
    }

    public function stream_read($count) {
        return fread($this->stream, $count);
    }

    public function stream_write($data){
        return fwrite($this->stream, $data);
    }

    public function stream_tell() {
        return ftell($this->stream);
    }

    public function stream_eof() {
        return feof($this->stream);
    }

    public function stream_seek($offset, $whence) {
        return fseek($this->stream, $offset, $whence);
    }

    public function url_stat($path, $flags)
    {
        return stat($this->resolveToRealPath($path));
    }
    
    protected function resolveToRealPath($path)
    {
        $url = parse_url($path);
        if (!array_key_exists($url['host'], self::$virtualHosts))
        {
            throw new \RuntimeException('Virtual host ' . $url['host'] . ' is not registered');
            return false;
        }
    
        $path = self::$virtualHosts[$url['host']] . $url['path'];
        $this->fireHooks($url['host'], $path, $url['path']);
        
        return $path;
    }
}