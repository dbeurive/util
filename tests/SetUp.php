<?php

namespace dbeurive\UtilTest;

trait SetUp
{
    private $__dirData = null;
    private $__dirReferences = null;
    private $__dirInputs = null;

    private function __setUp() {
        $this->__dirData = __DIR__ . DIRECTORY_SEPARATOR . "data";
        $this->__dirReferences = __DIR__ . DIRECTORY_SEPARATOR . "references";
        $this->__dirInputs = __DIR__ . DIRECTORY_SEPARATOR . "inputs";

        $files = glob($this->__dirData . DIRECTORY_SEPARATOR . '*');
        foreach($files as $_file){
            if(is_file($_file)) {
                if (false === unlink($_file)) {
                    throw new \Exception("Could not remove files in directory " . $this->__dirData);
                }
            }
        }
    }
}