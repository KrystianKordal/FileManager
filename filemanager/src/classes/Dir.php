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
    public static function open($path) 
    {
        if(file_exists($path) && is_dir($path))
            return new self($path);
        
        return new FMError("Directory doesn't exists");
    }

    /**
     * Stores directory path
     */
    public function __construct($path) 
    {
        $this->path = $path;
    }

    /**
     * Gets the content of a directory
     * 
     * @return array|FMError Array with names of file system nodes inside of directory or FMError instance if scanning dir failed
     */
    public function getContent()
    {
        $content = scandir($this->path);

        return $content !== false? $this->removeDotsDirs($content) : new FMError("Cannot scan dir $this->path");
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