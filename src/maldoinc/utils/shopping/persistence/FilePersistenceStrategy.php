<?php

namespace maldoinc\utils\shopping\persistence;

class FilePersistenceStrategy implements CartPersistentInterface
{
    protected $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function save($data)
    {
        file_put_contents($this->filename, $data);
    }

    public function load()
    {
        return is_readable($this->filename) ? file_get_contents($this->filename) : null;
    }

    /**
     * @return void
     */
    public function clear()
    {
        if (is_writable($this->filename)) {
            unlink($this->filename);
        }
    }
}