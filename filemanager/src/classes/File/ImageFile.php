<?php

/**
 * File containing image
 */

class ImageFile extends File 
{
    /**
     * {@inheritdoc}
     */
    public function __construct(string $file, string $path)
    {
        parent::__construct($file, $path);
        
        $this->edit_template = "image_file_content";
    }

    /**
     * Returns thumbnail depends on image content
     * 
     * @return string Thumbnail src
     */
    protected function getThumbnail() : string
    {
        return $this->getPath();
    }

    /**
     * Returns path to image file
     * 
     * @return string Path to image
     */
    protected function getPath() : string
    {
        return _FILES_PATH_ . $this->name;
    }

    /**
     * Returns whether the file is editable
     * 
     * @return bool Is file editable
     */
    protected function isEditable() : bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */

    protected function getEditTemplateVars(): array
    {
        return array(
            'url' => $this->getPath(),
            'file' => $this
        );
    }
}