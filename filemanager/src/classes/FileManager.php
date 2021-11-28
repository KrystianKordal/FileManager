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
     * @return array Array with all files and dirs within specific directory
     */
    public function getDirContent(string $path) : array
    {
        $dirContent = $this->removeDotsDirs(scandir($path));

        $factory = new FileFactory();
        $nodes = array_map( function($filename) use ($path, $factory) {
            return $factory->createFile($filename, $path);
        }, $dirContent );

        return array(
            'rendered_content' => get_template('files', array('files' => $nodes))
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
    public function getFileContent(string $filesPath, string $filename) : string {
        $factory = new FileFactory();
        $file = $factory->createFile($filename, $filesPath);

        return $file->getContent();
    }

    /**
     * Removes current and parent dir from content array
     * 
     * @param array $content Array with dir content
     * 
     * @return array Array without dots dirs
     */
    private function removeDotsDirs(array $content) 
    {
        return array_values(
            array_diff($content , ['..', '.'])
        );
    }
}