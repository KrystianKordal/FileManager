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
    public function __construct()
    {
        
    }

    /**
     * Returns content of specific directory
     * 
     * @param string $path The path of the directory whose contents are to be displayed
     * 
     * @return array Array with all files and dirs within specific directory
     */
    public function getDirContent(string $path) 
    {
        $dirContent = $this->removeDotsDirs(scandir($path));

        $nodes = array_map( function($filename) use ($path) {
            return new File($filename, $path);
        }, $dirContent );

        
        return array(
            'rendered_content' => get_template('files', array('files' => $nodes))
        );
    }

    /**
     * Removes current and parent dir from content array
     * 
     * @param array $content Array with dir content
     * 
     * @return array Array without dots dirs
     */
    private function removeDotsDirs(array $content) {
        return array_values(
            array_diff($content , ['..', '.'])
        );
    }
}