<?php

namespace TransactionalMail;

class TransactionalResult
{
    public array $emails;

    /**
     * Converts the properties of the provided object into the current object instance.
     *
     * @param mixed $object The object whose properties will be copied to the current object instance.
     *
     * @return self Returns a new instance of the current object with properties copied from the provided object.
     *    Returns false if the provided object is not valid or if properties cannot be copied.
     */
    public static function fromObject(mixed $object): self
    {
        $newObject = new TransactionalResult();

        $vars = get_object_vars($object);
        foreach ($vars as $key => $value) {
            if(property_exists($newObject, $key)) {
                $newObject->{$key} = $value;
            }
        }

        return $newObject;
    }
}