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
     * @return File|FMError File instance or FMError instance
     */
    public function createFile(string $file, string $path)
    {
        $filepath = $path . '/' . $file;
        if(!file_exists($filepath))
            return new FMError("File $filepath doesn't exists");

        $extension = pathinfo($filepath)['extension'] ?? "";
        if ($extension == 'txt') { 
            return new TextFile($file, $path);
        } else if(in_array($extension, ['jpg', 'png', 'svg', 'webp'])) {
            return new ImageFile($file, $path);
        } else {
            return new File($file, $path);
        }
    }
}
