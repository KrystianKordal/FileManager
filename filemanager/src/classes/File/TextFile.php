<?php

/**
 * File with txt extension
 */

class TextFile extends File 
{
    /**
     * Returns whether the file is editable
     * 
     * @return bool Is file editable
     */
    protected function isEditable() : bool
    {
        return true;
    }

    /**
     * Returns content from file
     * 
     * @return string|FMError File content or FMError instance if file reading failed
     */
    public function getContent() : string
    {
        $file = fopen($this->path, 'r');

        if($file === false) 
            return new FMError("Cannot open file $this->path");
            
        $content = fread($file, $this->size);
        fclose($file);

        return $content;
    }

    /**
     * Saves content in file
     * 
     * @param string $content New file content
     * 
     * @return bool Wheter saving was successful
     */
    public function saveContent(string $content) : bool 
    {
        return true;
    }
}
