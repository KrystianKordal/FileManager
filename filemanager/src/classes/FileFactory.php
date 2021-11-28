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
     * @return File File instance
     */
    public function createFile(string $file, string $path) : File
    {
        $extension = pathinfo($path . '/' . $file)['extension'];

        if ($extension == 'txt') { 
            return new TextFile($file, $path);
        } else {
            return new File($file, $path);
        }
    }
}
