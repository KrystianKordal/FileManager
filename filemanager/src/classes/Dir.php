<?php

/**
 * Class representing directory
 */
class Dir extends FSNode
{
    /**
     * Stores directory path
     * 
     * @param string $name Name of directory
     * @param string $path Path to directory
     */
    public function __construct(string $name, string $path) 
    {
        parent::__construct($name, $path);
        $this->template = "files";
    }

    /**
     * {@inheritdoc}
     */
    protected function getTemplateVars() : array
    {
        $dirContent = $this->getContent();
        if($dirContent instanceof FMError)
            return $dirContent;

        $nodes = $this->createNodes($dirContent);
        if($nodes instanceof FMError) {
            return $nodes;
        }

        return array('files' => $nodes);
    }

    /**
     * Creates instances of File or Dir objects
     * 
     * @param array $dirContent Array with names of file system nodes
     * 
     * @return array|FMError Array with File and Dir instances or FMError instance if instantiate node object failed
     */
    private function createNodes(array $dirContent)
    {
        $factory = new FSNodeFactory();

        $files = array();
        $dirs = array();
        foreach($dirContent as $filename) {
            $node = $factory->createNode($filename, $this->fullPath);

            if($node instanceof FMError)
                return $node;

            if($node instanceof Dir) {
                $dirs[] = $node;
            } else {
                $files[] = $node;
            }
        }

        $nodes = array_merge($dirs, $files);
        return $nodes;
    }

    /**
     * Gets the content of a directory
     * 
     * @return array|FMError Array with names of file system nodes inside of directory or FMError instance if scanning dir failed
     */
    public function getContent()
    {
        $content = scandir($this->fullPath);

        return $content !== false? $this->removeDotsDirs($content) : new FMError("Cannot scan dir $this->fullPath");
    }

    /**
     * Returns thumbnail of file
     * 
     * @return string Thumbnail src
     */
    protected function getThumbnail() : string
    {
        return _IMG_PATH_ . 'folder.svg';
    }

    /**
     * Removes current and parent dir from content array
     * 
     * @param array $content Array with dir content
     * 
     * @return array Array without dots dirs
     */
    private function removeDotsDirs(array $content) : array
    {
        return array_values(
            array_diff($content , ['..', '.'])
        );
    }

    /**
     * Finds available filename in specific directory
     * 
     * @param string $filename Filename with extension
     * @param string $dir Directory in which the file is to be saved
     * 
     * @return string New available filename
     */
    public function getAvailableFilename($filename) {
        $ext = pathinfo($filename, PATHINFO_EXTENSION) ?? "";
        $name = pathinfo($filename, PATHINFO_FILENAME);
        
        $iterator = 1;
        while(file_exists($this->path . $filename)) {
            $new_name = $name . " ($iterator)";

            if($ext != "") {
                $filename = $new_name . "." . $ext;
            } else {
                $filename = $new_name;
            }

            $iterator ++;
        }

        return $filename;
    }
}