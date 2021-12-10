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

        return $file->renderView();
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
     * @return array Array with success or error on failure
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

    /**
     * Saves file in specific directory
     * 
     * @param array $file File details
     * 
     * @return array Array with success or error on failure
     */
    public function uploadFile(array $file)
    {
        if($file['size'] ?? 0 > 0) {
            $filename = $this->getAvailableFilename($file['name'], _FILES_DIR_);

            $success = move_uploaded_file($file['tmp_name'], _FILES_DIR_ . $filename);

            if($success && file_exists(_FILES_DIR_ . $filename)) {
                return array('success' => true);
            }

            return array('error' => "Error occured while uploading file");
        }

        return array('error' => "File is incorrect");
    }

    /**
     * Finds available filename in specific directory
     * 
     * @param string $filename Filename with extension
     * @param string $dir Directory in which the file is to be saved
     * 
     * @return string New available filename
     */
    private function getAvailableFilename($filename, $dir) {
        $ext = pathinfo($filename, PATHINFO_EXTENSION) ?? "";
        $name = pathinfo($filename, PATHINFO_FILENAME);
        
        $iterator = 1;
        while(file_exists($dir . $filename)) {
            $new_name = $name . " ($iterator)";

            if($ext != "") {
                $filename = $new_name . "." . $ext;
            } else {
                $filename = $new_name;
            }

            $iterator ++;
        }

        return $filename;
    }
}