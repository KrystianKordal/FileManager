<?php

/**
 * Handles error message
 */

class FMError 
{
    /** @var string Error message */
    public $message;

    /**
     * Saves error message
     * 
     * @param string $message Error message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }
}