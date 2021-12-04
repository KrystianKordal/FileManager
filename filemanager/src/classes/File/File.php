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

    /** @var string File thumbnail */
    public $thumbnail;

    /** @var boolean Is file editable */
    public $editable;

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
        $this->name = $file;

        $this->size = filesize($path);

        $this->thumbnail = $this->getThumbnail();
        $this->editable = $this->isEditable();
    }

    /**
     * Returns thumbnail of file
     * 
     * @return string Thumbnail src
     */
    protected function getThumbnail() : string
    {
        return _IMG_PATH_ . 'document.png';
    }

    /**
     * Returns whether the file is editable
     * 
     * @return bool Is file editable
     */
    protected function isEditable() : bool
    {
        return false;
    }

    /**
     * Returns content from file
     * 
     * @return string File content
     */
    public function getContent() : string 
    {
        return "";
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
