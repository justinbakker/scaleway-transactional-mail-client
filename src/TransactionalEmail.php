<?php

namespace TransactionalMail;

class TransactionalEmail implements \JsonSerializable
{
    private EmailRecipient|null $from = null;

    /** @var array|EmailRecipient[] array containing the email recipients */
    private array $to = [];

    /** @var string Subject of the email */
    private string $subject;

    /** @var string ProjectId to link the e-mail to */
    private string $project_id;

    /** @var string String containing the plain text of the e-mail. */
    private string $text;

    /** @var string String containing the HTML text of the e-mail */
    private string $html;

    /** @var array Array containing all the attachments */
    private array $attachments = [];

    /** @var array Array containing all the additional headers */
    private array $additional_headers = [];

    /**
     * Constructor for instantiating a new object with a project ID.
     *
     * @param string $project_id The unique identifier for the project.
     * @return void
     */
    public function __construct(string $project_id)
    {
        $this->project_id = $project_id;
    }

    /**
     * Sets the specified EmailRecipient as the "from" recipient for this email.
     *
     * @param EmailRecipient $from The EmailRecipient object to set as the "from" recipient.
     *
     * @return void
     */
    public function setFromRecipient(EmailRecipient $from): void
    {
        $this->from = $from;
    }

    /**
     * Set the sender email and name for the message.
     *
     * @param string $email The email address of the sender.
     * @param string $name The name of the sender.
     * @return void
     * @throws \Exception
     */
    public function setFrom(string $email, string $name): void
    {
        $this->from = new EmailRecipient($email, $name);
    }

    /**
     * Retrieves the email recipient object representing the sender of the email.
     *
     * @return EmailRecipient The EmailRecipient object that represents the sender of the email.
     */
    public function getFromRecipient(): EmailRecipient
    {
        return $this->from;
    }

    /**
     * Adds an EmailRecipient to the list of recipients. If the recipient's email already exists in the list, it updates the existing entry.
     *
     * @param EmailRecipient $recipient The recipient to be added or updated in the list.
     * @return void
     */
    public function addToRecipient(EmailRecipient $recipient): void
    {
        /** @var EmailRecipient $to */
        foreach ($this->to as $index => $to) {
            if ($recipient->getEmail() === $to->getEmail()) {
                $this->to[$index] = $recipient;
                return;
            }
        }

        $this->to[] = $recipient;
    }

    /**
     * Add a new email recipient with the given email and name.
     *
     * @param string $email The email address of the recipient.
     * @param string $name The name of the recipient.
     * @return void
     */
    public function addTo(string $email, string $name): void
    {
        $this->addToRecipient(new EmailRecipient($email, $name));
    }

    /**
     * Returns an array of the recipients of the message.
     *
     * @return array|EmailRecipient[] The array containing the recipients of the message.
     */
    public function getToRecipients(): array
    {
        return $this->to;
    }

    /**
     * Returns the project ID associated with the current object.
     *
     * @return string The project ID as a string.
     */
    public function getProjectId(): string
    {
        return $this->project_id;
    }

    /**
     * Sets the subject of the object.
     *
     * @param string $subject The subject to be set. It must be less than 255 characters.
     * @return void
     * @throws \Exception If the subject exceeds 255 characters.
     */
    public function setSubject(string $subject): void
    {
        if(strlen($subject) > 255) {
            throw new \Exception("Subject must be less than 255 characters");
        }
        $this->subject = $subject;
    }

    /**
     * Returns the subject of the object.
     *
     * @return string The subject string value of the object.
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * Sets the text content for the object.
     *
     * @param string $text The text content to be set.
     * @return void
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * Returns the text value of the object.
     *
     * @return string The text value of the object.
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Sets the HTML content for the object.
     *
     * @param string $html The HTML content to be set.
     * @return void
     */
    public function setHtml(string $html): void
    {
        $this->html = $html;
    }

    /**
     * Retrieves the HTML content from the object.
     *
     * @return string The HTML content stored in the object.
     */
    public function getHtml(): string
    {
        return $this->html;
    }

    /**
     * Adds an email attachment to the list of attachments.
     *
     * @param EmailAttachment $attachment The email attachment to be added.
     * @return void
     */
    public function addEmailAttachment(EmailAttachment $attachment): void
    {
        foreach ($this->attachments as $index => $emailAttachment) {
            if($attachment->getName() == $emailAttachment->getName()) {
                $this->attachments[$index] = $attachment;
                return;
            }
        }

        $this->attachments[] = $attachment;
    }

    /**
     * Retrieves the attachments for the object.
     *
     * @return array|EmailAttachment[] An array containing the attachments.
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * Adds a header to the additional headers list.
     *
     * @param string $name The name of the header to be added.
     * @param string $value The value of the header to be added.
     * @return void
     */
    public function addHeader(string $name, string $value): void
    {
        /** @var EmailHeader $header */
        for($i = 0; $i < count($this->additional_headers); $i++) {
            if ($this->additional_headers[$i]->getKey() === $name) {
                $this->additional_headers[$i]->setValue($value);
                return;
            }
        }

        $this->additional_headers[] = new EmailHeader($name, $value);
    }

    /**
     * Returns the additional headers set for the object.
     *
     * @return array|EmailHeader[] The additional headers set for the object.
     */
    public function getHeaders(): array
    {
        return $this->additional_headers;
    }


    /**
     * Returns the associative array representation of the object for JSON serialization.
     *
     * @return array The associative array containing the public properties of the object.
     */
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}