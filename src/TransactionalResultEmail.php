<?php

namespace TransactionalMail;

class TransactionalResultEmail
{
    public string $id;
    public string $message_id;

    public string $project_id;

    public string $mail_from;

    public string $rcpt_to;

    public string $rcpt_type;

    public \DateTime $created_at;

    public \DateTime $updated_at;

    public string $status;

    public string $status_details;

    public string $try_count;

    public array $last_tries;
}