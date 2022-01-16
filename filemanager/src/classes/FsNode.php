<?php

/**
 * File system node
 */
class FSNode
{
    /** @var string Path to node */
    public $path;

    /** @var string Node name */
    public $name;

    /** @var string|false Contains the name of the file with the template */
    public $template = false;

    /** @var string|false Full path to node */
    public $fullPath = false;

    /**
     * Class constructor
     * 
     * @param string $name Name of file system node
     * @param string $path Path to node
     */
    public function __construct(string $name, string $path)
    {
        $this->fullPath = implode("/", [$path, $name]);
        $this->path = $path;
        $this->name = $name;
        $this->thumbnail = $this->getThumbnail();
        $this->template = 'unavailable_file_content';
    }

    /**
     * Returns thumbnail of node
     * 
     * @return string Thumbnail src
     */
    protected function getThumbnail() : string
    {
        return _IMG_PATH_ . 'document.svg';
    }

    /**
     * Renders view from template
     * 
     * @return array Generated content
     */
    public function renderView()
    {
        $params = $this->getTemplateVars();

        if ($params instanceof FMError) {
            return $params;
        }

        return array(
            'rendered_content' => get_template($this->template, $params)
        );
    }

    /**
     * Returns variables needed to render template
     * 
     * @return array Array with template variables
     */
    protected function getTemplateVars() : array
    {
        return array();
    }
}