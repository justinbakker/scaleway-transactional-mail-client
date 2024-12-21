<?php

namespace TransactionalMail;

/**
 * Represents an email attachment that can be serialized to JSON.
 */
class EmailAttachment implements \JsonSerializable
{
    private string $name;
    private string $type;
    private string $content;

    /**
     * Reads the contents of a file specified by the provided path, extracts file information, and returns an instance of the class.
     *
     * @param string $path The path to the file to be processed.
     *
     * @return this An instance of the class populated with file information extracted from the specified path.
     */
    public static function FromFilePath($path): this
    {
        $data = file_get_contents($path);
        if($data === false){
            throw new \Exception("Unable to read file " . $path);
        }
        $mime = mime_content_type($path);

        $name = basename($path);

        return self($name, $mime, base64_encode($data));

    }

    /**
     * Creates a new instance of the class by reading data from a stream.
     *
     * @param mixed $stream The stream resource to read data from.
     * @param string $fileName The name of the file associated with the stream.
     *
     * @return self Returns a new instance of the class populated with data from the stream.
     *
     * @throws \Exception If unable to read from the provided stream.
     */
    public static function FromStream(mixed $stream, string $fileName): this
    {
        rewind($stream);

        $data = stream_get_contents($stream);
        if($data === false){
            throw new \Exception("Unable to read stream ");
        }
        $mime = mime_content_type($stream);

        $name = $fileName;

        return self($name, $mime, base64_encode($data));

    }

    /**
     * Constructs a new instance with the provided name, type, and content.
     *
     * @param string $name The name of the object.
     * @param string $type The type of the object.
     * @param string $content The content of the object.
     */
    private function __construct(string $name, string $type, string $content)
    {
        $this->name = $name;
        $this->type = $type;
        $this->content = $content;
    }

    /**
     * Returns the name of the object.
     *
     * @return string The name of the object.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the type property of the object.
     *
     * @return string The type property of the object.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Gets the content of the object.
     *
     * @return string The content of the object.
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Returns the data of the object in array form for JSON serialization.
     *
     * @return array Returns an array containing the public properties of the object.
     */
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}