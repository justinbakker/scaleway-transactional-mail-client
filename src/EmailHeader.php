<?php

namespace TransactionalMail;

/**
 * Represents an email header with key and value pairs.
 */
class EmailHeader implements \JsonSerializable
{
    private string $key;

    private string $value;

    /**
     * Constructor method for initializing a new instance.
     *
     * @param string $key The key to be initialized with.
     * @param string $value The value to be initialized with.
     *
     * @return void
     */
    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Retrieves the key associated with this instance.
     *
     * @return string The key value stored in this instance.
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Retrieves the stored value.
     *
     * @return string The stored value that this method returns.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Returns a string representation of the object in the format 'key: value'.
     *
     * @return string The string representation of the object in the format 'key: value'.
     */
    public function toString()
    {
        return $this->key . ': ' . $this->value;
    }


    /**
     * Sets the value of the object.
     *
     * @param string $value The new value to be set.
     * @return void
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * Returns an array of the object's public and non-static properties for JSON serialization.
     *
     * @return array An associative array containing the object's public and non-static properties.
     */
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}