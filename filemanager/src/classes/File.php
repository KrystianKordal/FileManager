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
    }

    /**
     * Returns thumbnail of file
     * 
     * @return string Thumbnail src
     */
    protected function getThumbnail() {
        $thumbnails = array(
            'jpg' => _IMG_PATH_ . 'image.png',
            'png' => _IMG_PATH_ . 'image.png',
            'webp' => _IMG_PATH_ . 'image.png',
            'txt' => _IMG_PATH_ . 'document.png',
        );

        return $thumbnails[$this->ext];
    }

}