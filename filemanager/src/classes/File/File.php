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

    /** @var string|false If editable, it contains the name of the file with the editing template */
    public $edit_template = false;

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
        $this->ext = $pathinfo['extension'] ?? "";
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
        return _IMG_PATH_ . 'document.svg';
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
     * Renders file view from template
     * 
     * @return array Generated content
     */
    public function renderView()
    {
        $params = $this->getEditTemplateVars();

        if ($params instanceof FMError) {
            return $params;
        }

        if ($this->editable) {
            return array(
                'content' => get_template($this->edit_template, $params)
            );
        } 
        return array(
            'rendered_content' => get_template('unavailable_file_content', $params)
        );
    }

    /**
     * Returns variables needed to render template
     * 
     * @return array Array with template variables
     */
    protected function getEditTemplateVars() : array
    {
        return array();
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
