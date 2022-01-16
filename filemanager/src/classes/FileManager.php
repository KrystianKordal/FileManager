<?php

/**
 * File Manager
 * Manage files i selected dir
 */

class FileManager 
{
    /** @var string Path to directory with files */
    private $dirFullPath;

    /** @var string Path to parent directory of directory with files */
    private $dirPath;
    
    /** @var string Directory name */
    private $dirName;

    /**
     * Class constructor
     * 
     * @param string $dirPath Path to main directory
     */
    public function __construct($path) 
    {
        $this->dirFullPath = $path;
        $this->dirPath = dirname($path);
        $this->dirName = basename($path);
    }

    /**
     * Returns content of specific directory
     * 
     * @return array An associative array with rendered files list or error
     */
    public function getDirContent() : array
    {
        $dir = Dir::open($this->dirName, $this->dirPath);
        if($dir instanceof FMError)
            return array('error' => $dir->message);

        $dirContent = $dir->getContent();
        if($dirContent instanceof FMError)
            return array('error' => $dirContent->message);

        $nodes = $this->createNodes($dirContent, $this->dirFullPath);
        if($nodes instanceof FMError) {
            return array('error' => $nodes->message);
        }

        $rendered_content = get_template('files', array('files' => $nodes));
        return array(
            'rendered_content' => $rendered_content
        );
    }

    /**
     * Creates instances of File or Dir objects
     * 
     * @param array $dirContent Array with names of file system nodes
     * 
     * @return array|FMError Array with File and Dir instances or FMError instance if instantiate node object failed
     */
    private function createNodes(array $dirContent)
    {
        $factory = new FSNodeFactory();

        $files = array();
        $dirs = array();
        foreach($dirContent as $filename) {
            $node = $factory->createNode($filename, $this->dirFullPath);

            if($node instanceof FMError)
                return $node;

            if($node instanceof Dir) {
                $dirs[] = $node;
            } else {
                $files[] = $node;
            }
        }

        $nodes = array_merge($dirs, $files);
        return $nodes;
    }

    /**
     * Returns content of specific file
     * 
     * @param string $filename Name of the file whose content is to be returned
     * 
     * @return array|FMError Array with file content or FMError instance
     */
    public function getContent(string $filename) : array
    {
        if (is_dir(implode('/', [$this->dirFullPath, $filename])) ) {
            $this->dirPath = $this->dirFullPath;
            $this->dirName = $filename;
            $this->dirFullPath = implode('/', [$this->dirPath, $filename]);
            return $this->getDirContent();
        }

        $factory = new FSNodeFactory();
        $file = $factory->createNode($filename, $this->dirFullPath);
        if($file instanceof FMError) 
            return $file;

        return $file->renderView();
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
        $factory = new FSNodeFactory();
        $file = $factory->createNode($filename, $this->dirFullPath);
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
            $dir = Dir::open($this->dirName, $this->dirPath);
            $filename = $dir->getAvailableFilename($file['name'], _FILES_DIR_);

            $success = move_uploaded_file($file['tmp_name'], _FILES_DIR_ . $filename);

            if($success && file_exists(_FILES_DIR_ . $filename)) {
                return array('success' => true);
            }

            return array('error' => "Error occured while uploading file");
        }

        return array('error' => "File is incorrect");
    }
}