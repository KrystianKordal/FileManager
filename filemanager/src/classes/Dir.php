<?php

/**
 * Class representing directory
 */
class Dir extends FSNode
{
    /** @var string Relative path used in src to files */
    static $relativePath;

    /** @var string Relative path used in src to files */
    public $dirName;

    /**
     * Stores directory path
     * 
     * @param string $name Name of directory
     * @param string $path Path to directory
     */
    public function __construct(string $name, string $path) 
    {
        parent::__construct($name, $path);
        $this->template = "files";
        $this->dirName = $this->getDirName();
    }

    public function getDirName()
    {
        $path = str_replace(_FILES_DIR_, "", $this->fullPath . "/");
        return $path;
    }

    /**
     * Returns relative path to file
     * 
     * @param string $fullPath Absolute path to file
     * 
     * @return string Relative path to file from files directory
     */
    public static function getRelativePath(string $fullPath) : string
    {

        return str_replace(_FILES_DIR_, "", $fullPath . is_dir($fullPath)? "/" : "");
    }

    /**
     * Returns relative path to parent directory of object in variable
     * 
     * @param FSNode $obj Object in needed directory
     * 
     * @return string Relative path to parent directory
     */
    public static function getParentDirRelativePath(FSNode $obj)
    {
        $relativePath = self::getRelativePath($obj->fullPath);
        return str_replace("/" . $obj->name, "", $relativePath);
    }

    /**
     * Initializes a static variable with relative path to this directory
     * 
     * @param string $fullPath Path to current directory
     */
    public static function setRelativePath(string $fullPath)
    {
        $relativePath = str_replace(_FILES_DIR_, "", $fullPath . "/");
        if ($relativePath == "/") {
            $relativePath = "";
        }
        $relativePath = _FILES_PATH_ . $relativePath;

        self::$relativePath = $relativePath;
    }

    public static function getRelativeUrl()
    {
        return self::$relativePath;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTemplateVars() : array
    {
        $dirContent = $this->getContent();
        if($dirContent instanceof FMError)
            return $dirContent;

        $nodes = $this->createNodes($dirContent);
        if($nodes instanceof FMError) {
            return $nodes;
        }

        return array('files' => $nodes);
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
            $node = $factory->createNode($filename, $this->fullPath);

            if($node instanceof FMError)
                return $node;

            if($node instanceof Dir) {
                $dirs[] = $node;
            } else {
                $files[] = $node;
            }
        }

        $path_to_compare = $this->fullPath;
        if($path_to_compare[strlen($path_to_compare) - 1] != "/") {
            $path_to_compare .= "/";
        } 
        
        if ($path_to_compare != _FILES_DIR_) {
            array_unshift($dirs, $this->createBackNode());
        }

        $nodes = array_merge($dirs, $files);
        return $nodes;
    }

    /**
     * Gets the content of a directory
     * 
     * @return array|FMError Array with names of file system nodes inside of directory or FMError instance if scanning dir failed
     */
    public function getContent()
    {
        $content = scandir($this->fullPath);

        if ($content === false) {
            return new FMError("Cannot scan dir $this->fullPath");
        }

        $content = $this->removeDotsDirs($content);

        return $content;
    }

    /**
     * Creates a directory that is the parent of the current directory
     * 
     * @return Dir Parent dir
     */
    private function createBackNode()
    {
        $factory = new FSNodeFactory();
        $fullPath = $this->path;
        $name = basename($fullPath);
        $path = dirname($fullPath);

        $dir = $factory->createDir($name, $path);
        if ($fullPath . "/" == _FILES_DIR_) {
            $dir->dirName = "/";
        } else {
            $dir->dirName = $dir->name;
        }

        $dir->name = "..";
        
        return $dir;
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
     * Returns thumbnail of file
     * 
     * @return string Thumbnail src
     */
    protected function getThumbnail() : string
    {
        return _IMG_PATH_ . 'folder.svg';
    }

    /**
     * Finds available filename in specific directory
     * 
     * @param string $filename Filename with extension
     * @param string $dir Directory in which the file is to be saved
     * 
     * @return string New available filename
     */
    public function getAvailableFilename($filename) {
        $ext = pathinfo($filename, PATHINFO_EXTENSION) ?? "";
        $name = pathinfo($filename, PATHINFO_FILENAME);
        
        $iterator = 1;
        while(file_exists($this->path . $filename)) {
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