<?php

/**
 * Single file
 */

class File
{
    /** @var string Path to file */
    public $path;

    /** @var string File extension */
    public $ext;

    /** @var string File name without extension */
    public $name;

    /** @var int File size in bytes */
    public $size;

    /**
     * Class constructor
     * 
     * Initializes file basic data
     * 
     * @param string $filename Name of file
     * @param string $path Path to file
     */
    public function __construct(string $file, string $path)
    {
        $this->path = $path . $file;

        $pathinfo = pathinfo($this->path);
        $this->ext = $pathinfo['extension'];
        $this->name = $pathinfo['filename'];

        $this->size = filesize($path);
    }
}