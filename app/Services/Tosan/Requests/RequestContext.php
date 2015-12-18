<?php namespace Tajrish\Services\Tosan\Requests;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class RequestContext implements \ArrayAccess, Jsonable, Arrayable {

    /**
     * Holder of our context
     *
     * @var array
     */
    protected $hashTable = [];

    /**
     * Session id key
     */
    const SESSION_ID = 'SESSIONID';

    /**
     * Push a value to the given key
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function push($key, $value)
    {
        $this->hashTable[(string) $key] = (string) $value;
        return $this;
    }

    /**
     * Get a value by given key
     *
     * @param $key
     * @param bool|false $hard
     * @param null $def
     * @return mixed
     */
    public function get($key, $hard = false, $def = null)
    {
        if (!isset($this->hashTable[$key]) && $hard) {
            throw new \LogicException("There is not any key named : {$key}");
        }

        return array_get($this->hashTable, $this, $def);
    }

    /**
     * Set context session id
     *
     * @param $sessionId
     * @return $this
     */
    public function setSessionId($sessionId)
    {
        $this->push(static::SESSION_ID, $sessionId);
        return $this;
    }

    public function offsetExists($offset)
    {
        return isset($this->hashTable[$offset]);
    }

    public function offsetGet($offset)
    {
        return array_get($this->hashTable, $offset, null);
    }

    public function offsetSet($offset, $value)
    {
        $this->push($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($this->hashTable[$offset]);
    }

    public function __get($what)
    {
        return $this->get($what);
    }

    public function __set($key, $value)
    {
        $this->push($key, $value);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->hashTable;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * String representation
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}