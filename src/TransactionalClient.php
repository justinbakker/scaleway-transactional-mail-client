<?php

namespace TransactionalMail;

class TransactionalClient
{
    private string $project_id;

    private string $access_key;

    private string $access_secret;

    private string $region;

    private array $allowed_regions = ['fr-par', 'nl-ams'];

    private string $domain_id;

    private string $url = "https://api.scaleway.com/transactional-email/v1alpha1/regions/";

    /**
     * Construct a new instance with the provided project ID, access key, access secret, and region.
     *
     * @param string $project_id The project ID to be set.
     * @param string $access_key The access key to be set.
     * @param string $access_secret The access secret to be set.
     * @param string $region The region to be set (default is "nl-ams").
     * @return void
     * @throws \Exception
     */
    public function __construct(string $project_id, string $access_key, string $access_secret, string $region = "nl-ams")
    {
        $this->project_id = $project_id;
        $this->access_key = $access_key;
        $this->access_secret = $access_secret;

        $this->setRegion($region);
    }

    /**
     * Sets the region for the object.
     *
     * @param string $region The region to be set for the object.
     *
     * @return void
     * @throws \Exception
     */
    public function setRegion(string $region): void
    {
        if(!in_array($region, $this->allowed_regions)){
            throw new \Exception("Invalid region. Allowed values: " . implode(', ', $this->allowed_regions));
        }
        $this->region = $region;
    }

    /**
     * Sets the URL for the object.
     *
     * @param string $url The URL to be set for the object.
     *
     * @return void
     * @throws \Exception
     */
    public function setUrl(string $url): void
    {
        if(!filter_var($url, FILTER_VALIDATE_URL)){
            throw new \Exception("Invalid URL.");
        }

        if(!str_ends_with($url, "/")) {
            $url .= '/';
        }
        $this->url = $url;
    }

    /**
     * Set the domain ID for the object.
     *
     * @param string $domain_id The domain ID to be set.
     * @return void
     */
    public function setDomainId(string $domain_id): void
    {
        $this->domain_id = $domain_id;
    }

    /**
     * Create a new TransactionalEmail instance, using the domain ID stored in the current object.
     *
     * @return TransactionalEmail A new TransactionalEmail instance initialized with the domain ID.
     * @throws \Exception If there are any issues during the creation process.
     */
    public function createTransactionalEmail(): TransactionalEmail
    {
        return new TransactionalEmail($this->project_id);
    }

    /**
     * Sends a transactional email.
     *
     * @param TransactionalEmail $email The transactional email object to be sent.
     *
     * @return TransactionalResult The result of the transactional email sending process.
     *
     * @throws \Exception If required email elements are missing in the provided TransactionalEmail object.
     * @throws \RuntimeException If there is an error during the email sending process.
     */
    public function send(TransactionalEmail $email): TransactionalResult|\stdClass
    {

        if(empty($email->getFromRecipient())) throw new \Exception("From recipient required.");
        if(count($email->getToRecipients()) == 0) throw new \Exception("To recipient required.");
        if(empty($email->getSubject())) throw new \Exception("Subject required.");
        if(empty($email->getText()) && empty($email->getHtml())) throw new \Exception("Text required.");
        if(empty($email->getProjectId())) throw new \Exception("Project id required.");

        $json = json_encode($email);

        $ch = \curl_init($this->url . $this->region . '/emails');

        \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, "POST");
        \curl_setopt($ch, \CURLOPT_HTTPHEADER, [
            'X-Auth-Token: ' . $this->access_secret,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json)
        ]);

        curl_setopt($ch, \CURLOPT_POSTFIELDS, $json);

        $response = \curl_exec($ch);
        $status = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = \curl_error($ch);
        $errno    = \curl_errno($ch);

        \curl_close($ch);


        if (0 !== $errno) {
            throw new \RuntimeException($error, $errno);
        }

        if($status == 200 || $status == 201) {
            return TransactionalResult::fromObject(json_decode($response, false));
        }

        $error = new \stdClass();
        $error->code = $status;
        $error->message = $error;
        $error->errno = $errno;
        $error->detail = $response;

        return $error;
    }
}