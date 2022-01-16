<?php

/**
 * File with txt extension
 */

class TextFile extends File 
{
    /**
     * {@inheritdoc}
     */
    public function __construct(string $file, string $path)
    {
        parent::__construct($file, $path);
        
        $this->template = "text_file_content";
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
     * {@inheritdoc}
     */

    protected function getTemplateVars(): array
    {
        $content = $this->getContent();
        if($content instanceof FMError)
            return array('error' => $content->message);

        return array(
            'content' => $content,
            'file' => $this
        );
    }

    /**
     * Saves content in file
     * 
     * @param string $content New file content
     * 
     * @return bool|FMError True if saving was success full or FMError instance on failure
     */
    public function save(string $content)
    {
        $handler = fopen($this->path, 'w');

        if($handler === false) {
            return new FMError("Error occured while opening file: $this->path");
        }

        $content = trim($content);
        if(fwrite($handler, $content) === false) {
            return new FMError("Error occured while saving content in file: $this->path");
        }

        fclose($handler);

        return true;
    }
}
