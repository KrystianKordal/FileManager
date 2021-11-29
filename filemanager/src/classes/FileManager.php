<?php

/**
 * File Manager
 * Manage files i selected dir
 */

class FileManager 
{
    /**
     * Class constructor
     */
    public function __construct() {}

    /**
     * Returns content of specific directory
     * 
     * @param string $path The path of the directory whose contents are to be displayed
     * 
     * @return array An associative array with rendered files list or error
     */
    public function getDirContent(string $path) : array
    {
        $dirContent = $this->scanDir($path);
        if($dirContent === false)
            return array('error' => 'Scanning directory failed');

        $nodes = $this->createFileNodes($dirContent, $path);
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
     * @param string $path Path to the directory whese these files are located
     * 
     * @return array|string Array with File instances or name of the file that failed to instantiate
     */
    private function createFileNodes(array $dirContent, $path)
    {
        $factory = new FileFactory();

        $nodes = array();
        foreach($dirContent as $filename) {
            $node = $factory->createFile($filename, $path);

            if($node === false)
                return $filename;
            
            $nodes[] = $node;
        }

        return $nodes;
    }

    /**
     * Gets the content of a directory
     * 
     * @param string $path Path to directory
     * 
     * @return array|bool Array with names of file system nodes inside of directory or false if scanning dir failed
     */
    private function scanDir(string $path)
    {
        set_error_handler(function() {});

        $content = scandir($path);

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
     * @param string $filesPath Path to all files directory
     * @param string $filename Name of the file whose content is to be returned
     * 
     * @return string File content
     */
    public function getFileContent(string $filesPath, string $filename) : string 
    {
        $factory = new FileFactory();
        $file = $factory->createFile($filename, $filesPath);

        return $file->getContent();
    }
}