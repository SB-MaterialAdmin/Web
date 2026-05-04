<?php

abstract class ExtendedObject implements \ArrayAccess {
    /**
     * Returns the value from object.
     * 
     * @param $key      Key name.
     */
    abstract public function get($key);

    /**
     * Sets the value in object.
     * 
     * @param $key      Key name.
     * @param $value    New value.
     */
    abstract public function set($key, $value);

    /**
     * Checks, exists value in object or not.
     * 
     * @param $key      Key name.
     */
    abstract public function exists($key);

    /**
     * Deletes the value in object.
     * 
     * @param $key      Key name.
     */
    abstract public function remove($key);

    public function offsetExists(mixed $offset) : bool       { return $this->exists($offset);    }
    public function __isset(mixed $offset) : bool           { return $this->exists($offset);    }

    public function offsetUnset(mixed $offset) : void       { $this->remove($offset);           }
    public function __unset(mixed $offset) : void           { $this->remove($offset);           }

    public function offsetGet(mixed $offset) : mixed          { return $this->get($offset);       }
    public function __get(mixed $offset) : mixed             { return $this->get($offset);       }

    public function offsetSet(mixed $offset, mixed $value) : void  { $this->set($offset, $value);      }
    public function __set(mixed $offset, mixed $value) : void     { $this->set($offset, $value);      }
}