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

    public function offsetExists($offset)       { return $this->exists($offset);    }
    public function __isset($offset)            { return $this->exists($offset);    }

    public function offsetUnset($offset)        { $this->remove($offset);           }
    public function __unset($offset)            { $this->remove($offset);           }

    public function offsetGet($offset)          { return $this->get($offset);       }
    public function __get($offset)              { return $this->get($offset);       }

    public function offsetSet($offset, $value)  { $this->set($offset, $value);      }
    public function __set($offset, $value)      { $this->set($offset, $value);      }
}