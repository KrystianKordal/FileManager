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
        if($dirContent instanceof FMError)
            return array('error' => $dirContent->message);

        $nodes = $this->createFileNodes($dirContent, $this->dirPath);
        if($nodes instanceof FMError) {
            return array('error' => $nodes->message);
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
     * @return array|FMError Array with File instances or FMError instance if instantiate file object failed
     */
    private function createFileNodes(array $dirContent)
    {
        $factory = new FileFactory();

        $nodes = array();
        foreach($dirContent as $filename) {
            $node = $factory->createFile($filename, $this->dirPath);

            if($node instanceof FMError)
                return $node;
            
            $nodes[] = $node;
        }

        return $nodes;
    }

    /**
     * Gets the content of a directory
     * 
     * @return array|FMError Array with names of file system nodes inside of directory or FMError instance if scanning dir failed
     */
    private function scanDir()
    {
        $content = scandir($this->dirPath);

        return $content !== false? $this->removeDotsDirs($content) : new FMError("Cannot scan dir $this->dirPath");
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
     * @return array|FMError Array with file content or FMError instance
     */
    public function getFileContent(string $filename) : array
    {
        $file = $this->createFileInstance($filename);
        if($file instanceof FMError) 
            return $file;

        $content = $file->getContent();

        if($content instanceof FMError)
            return array('error' => $content->message);

        return array(
            'content' => get_template('text_file_content', array(
                'content' => $content,
                'file' => $file
            ))
        );
    }

    /**
     * Creates instance of file
     * 
     * @param string $filename File of name to instantiate
     * 
     * @return File|FMError Instance of File class or FMError instance
     */
    private function createFileInstance($filename) {
        $factory = new FileFactory();
        $file = $factory->createFile($filename, $this->dirPath);

        if($file instanceof FMError) 
            return $file->message;
        
        return $file;
    }

    /**
     * Saves content to file
     * 
     * @param string $filename Name of the file in which the content is to be written
     * @param string $content Content to be saved to the file
     * 
     * @return bool|FMError true or FMError instance depending on the success of saving the content
     */
    public function saveFile(string $filename, string $content)
    {
        $file = $this->createFileInstance($filename);
        if($file instanceof FMError) 
            return array('error' => $file->message);

        $result = $file->save($content);

        if($result instanceof FMError)
            return array('error' => $result->message);
        return array('success' => true);
    }
}