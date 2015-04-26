<?php

namespace maldoinc\utils\shopping;

class FilePersistenceStrategy implements CartPersistentInterface
{
    protected $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    function save($data)
    {
        file_put_contents($this->filename, $data);
    }

    function load()
    {
        return is_readable($this->filename) ? file_get_contents($this->filename) : null;
    }

    /**
     * @return void
     */
    function clear()
    {
        unlink($this->filename);
    }
}