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
        return _FILES_PATH_ . $this->name;
    }
}