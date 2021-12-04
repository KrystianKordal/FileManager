<?php
/**
 * Template functions
 */

/**
 * Returns content of template file with assigned params
 * @param string $file Template file name without extension
 * @param array $args Template args
 * 
 * @return string Template content
 * 
*/
function get_template(string $file, array $args = array()) {
    $filepath = _TPL_DIR_ . $file . ".php";

    if(file_exists($filepath)) {
        extract($args);

        ob_start();
        include($filepath);
        $content = ob_get_contents();
        ob_end_clean();
        
        return $content;
    } else {
        return "";
    }

}