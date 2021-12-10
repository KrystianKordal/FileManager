<?php

/**
 * File containing image
 */

class ImageFile extends File 
{
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
     * Renders file view from template
     * 
     * @return array Generated content
     */
    public function renderView()
    {
        return array(
            'content' => get_template('image_file_content', array(
                'url' => $this->getPath(),
                'file' => $this
            ))
        );
    }
}