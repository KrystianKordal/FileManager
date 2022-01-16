<?php

/**
 * Class representing directory
 */
class Dir
{
    /** @var string Path to current directory */
    private $path;

    /**
     * Returns new Dir object if exists
     * 
     * @param string $path Directory location
     * 
     * @return Dir|FMError new Dir instance or FMError if not exists
     */
    public static function open($name, $path) 
    {
        $fullPath = implode("/", [$path, $name]);
        if(file_exists($fullPath) && is_dir($fullPath))
            return new self($name, $path);
        
        return new FMError("Directory doesn't exists");
    }

    /**
     * Stores directory path
     * 
     * @param string $name Name of directory
     * @param string $path Path to directory
     */
    public function __construct(string $name, string $path) 
    {
        $this->name = $name;
        $this->path = $path;
        $this->fullPath = implode("/", [$path, $name]);

        $this->thumbnail = $this->getThumbnail();
    }

    /**
     * Gets the content of a directory
     * 
     * @return array|FMError Array with names of file system nodes inside of directory or FMError instance if scanning dir failed
     */
    public function getContent()
    {
        $content = scandir($this->fullPath);

        return $content !== false? $this->removeDotsDirs($content) : new FMError("Cannot scan dir $this->fullPath");
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