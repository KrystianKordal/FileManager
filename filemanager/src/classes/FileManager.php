<?php

/**
 * File Manager
 * Manage files i selected dir
 */

class FileManager 
{
    /** @var string Path to main directory */
    private $dirPath;

    /**
     * Class constructor
     * 
     * @param string $dirPath Path to main directory
     */
    public function __construct($dirPath) 
    {
        $this->dirPath = $dirPath;
    }

    /**
     * Returns content of specific directory
     * 
     * @return array An associative array with rendered files list or error
     */
    public function getDirContent() : array
    {
        $dirContent = $this->scanDir($this->dirPath);
        if($dirContent === false)
            return array('error' => 'Scanning directory failed');

        $nodes = $this->createFileNodes($dirContent, $this->dirPath);
        if(!is_array($nodes)) {
            return array('error' => "File $nodes could not be instantiated");
        }

        return array(
            'rendered_content' => get_template('files', array('files' => $nodes))
        );
    }

    /**
     * Creates instances of File object
     * 
     * @param array $dirContent Array with names of file system nodes
     * 
     * @return array|string Array with File instances or name of the file that failed to instantiate
     */
    private function createFileNodes(array $dirContent)
    {
        $factory = new FileFactory();

        $nodes = array();
        foreach($dirContent as $filename) {
            $node = $factory->createFile($filename, $this->dirPath);

            if($node === false)
                return $filename;
            
            $nodes[] = $node;
        }

        return $nodes;
    }

    /**
     * Gets the content of a directory
     * 
     * @return array|bool Array with names of file system nodes inside of directory or false if scanning dir failed
     */
    private function scanDir()
    {
        set_error_handler(function() {});

        $content = scandir($this->dirPath);

        restore_error_handler();

        return $content !== false? $this->removeDotsDirs($content) : false;
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
     * Returns content of specific file
     * 
     * @param string $filename Name of the file whose content is to be returned
     * 
     * @return array Array with file content error
     */
    public function getFileContent(string $filename) : array
    {
        $file = $this->createFileInstance($filename);
        if(is_string($file)) 
            return array('error' => $file);

        $content = $file->getContent();

        if($content === false)
            return array('error' => "Cannot read content of $filename");

        return array(
            'content' => get_template('text_file_content', array(
                'content' => $content,
                'file' => $file
            ))
        );
    }

    /**
     * Creates instance of file
     */
    private function createFileInstance($filename) {
        $factory = new FileFactory();
        $file = $factory->createFile($filename, $this->dirPath);

        if($file === false) 
            return "File $file could not be instantiated";
        
        return $file;
    }

    /**
     * Saves content to file
     * 
     * @param string $filename Name of the file in which the content is to be written
     * @param string $content Content to be saved to the file
     * 
     * @return bool true or false depending on the success of saving the content
     */
    public function saveFile(string $filename, string $content) : bool
    {
        $file = $this->createFileInstance($filename);
        if(is_string($file)) 
            return array('error' => $file);

        return true;
    }
}