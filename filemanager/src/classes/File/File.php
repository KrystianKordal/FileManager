<?php

/**
 * Single file
 */

class File extends FSNode
{
    /** @var string File extension */
    public $ext;

    /** @var int File size in bytes */
    public $size;

    /** @var string File thumbnail */
    public $thumbnail;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $name, string $path)
    {
        parent::__construct($name, $path);
        $pathinfo = pathinfo($this->fullPath);
        $this->ext = $pathinfo['extension'] ?? "";

        $this->size = filesize($path);
    }

    /**
     * Saves content in file
     * 
     * @param string $content New file content
     * 
     * @return bool|FMError True if saving was success full or FMError instance on failure
     */
    public function save(string $content)
    {
        return true;
    }
}
