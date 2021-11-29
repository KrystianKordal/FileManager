<?php

/**
 * Creates instance of File class
 */

class FileFactory
{
    /**
     * Creates File instance
     * 
     * @param string $file Name of file
     * @param string $path Path to file
     * 
     * @return File|bool File instance or false if file not exists
     */
    public function createFile(string $file, string $path) : File
    {
        $filepath = $path . '/' . $file;
        if(!file_exists($filepath))
            return false;

        $extension = pathinfo($filepath)['extension'];
        if ($extension == 'txt') { 
            return new TextFile($file, $path);
        } else {
            return new File($file, $path);
        }
    }
}
