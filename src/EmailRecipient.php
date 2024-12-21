<?php

namespace TransactionalMail;

/**
 * Class EmailReciepent
 */
class EmailRecipient
{
    private string $name;
    private string $email;

    /**
     * Constructor for initializing a new instance.
     *
     * @param string|null $email The email address to be set. Must be a valid email format.
     * @param string|null $name The name to be set.
     *
     * @return void
     */
    public function __construct(string $email = null, string|null $name = null){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new \Exception("Invalid email address");
        }

        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Returns the email address associated with this object.
     *
     * @return string The email address of the object.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Returns the name associated with this object.
     *
     * @return string The name of the object.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the email address for the instance.
     *
     * @param string $email The email address to be set. Must be a valid email format.
     *
     * @return void
     */
    public function setEmail(string $email): void
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new \Exception("Invalid email address");
        }

        $this->email = $email;
    }

    /**
     * Set the name of the object.
     *
     * @param string $name The name to be set.
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns a formatted string representation of this object.
     *
     * @return string Formatted string with optional name and email address in the format "name <email>" if a name is present, otherwise returns the email address alone.
     */
    public function toString(): string
    {
        if (!empty($this->name)) {
            return $this->name . " <" . $this->email . ">";
        } else {
            return $this->email;
        }
    }
}