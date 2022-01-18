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
        
        $this->template = "image_file_content";
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
        return Dir::getRelativeUrl() . $this->name;
    }

    /**
     * {@inheritdoc}
     */

    protected function getTemplateVars(): array
    {
        return array(
            'url' => $this->getPath(),
            'file' => $this,
            'back_path' => Dir::getParentDirRelativePath($this)
        );
    }
}