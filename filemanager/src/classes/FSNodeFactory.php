<?php

class FSNodeFactory
{
    /**
     * Creates File or Dir instance
     * 
     * @param string $name Name of file system node
     * @param string $path Path to node
     */
    public function createNode(string $name, string $path)
    {
        $filepath = $path . '/' . $name;
        if(!file_exists($filepath))
            return new FMError("Element $filepath doesn't exists");

        return is_dir($filepath) ? $this->createDir($name, $path) : $this->createFile($name, $path);
    }
    
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
        $extension = pathinfo($filepath)['extension'] ?? "";
        if ($extension == 'txt') { 
            return new TextFile($file, $path);
        } else if(in_array($extension, ['jpg', 'png', 'svg', 'webp'])) {
            return new ImageFile($file, $path);
        } else {
            return new File($file, $path);
        }
    }

    /**
     * Creates Dir instance
     * 
     * @param string $file Name of file
     * @param string $path Path to file
     * 
     * @return File|FMError File instance or FMError instance
     */
    public function createDir(string $name, string $path)
    {
        return new Dir($name, $path);
    }
}