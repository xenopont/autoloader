<?php

namespace Autoloader {

    class Builder {
        const REGEXP_NAMESPACE = '/(namespace)\s+([_A-Za-z0-9\\]+)/';
        const REGEXP_CLASS     = '/(class|interface|trait)\s+([_A-Za-z0-9]+)/';

        private $map = array();
        private $path = './';

        public function __construct($path){
            $this->path = realpath($path);
            echo $this->path, " content:\n\n";
        }

        public function run(){
            $iterator = new DirectoryIterator($this->path);
            while(($next = $iterator->next()) !== false){
                echo 'FILE: ', $next, "\n";
                $fileContent = file_get_contents($next);
                var_dump($this->extractClasses($fileContent));
            }
        }

        private function extractClasses($fileContent){
            $result = array();
            //
            return $result;
        }
    }

    class DirectoryIterator {
        private static $autoloaderDir = '';
        private $dir = null;
        private $path = '';
        /** @var DirectoryIterator */
        private $subdirectoryIterator = null;

        public function __construct($path){
            $this->path = $path;
            $this->dir  = dir($this->path);
            if(empty(self::$autoloaderDir)){
                self::$autoloaderDir = realpath(__DIR__.'/..');
            }
        }

        public function next(){
            if(empty($this->dir)){
                return false;
            }
            else{
                do{
                    $next = is_null($this->subdirectoryIterator) ? false : $this->subdirectoryIterator->next();
                    if($next === false){
                        $this->subdirectoryIterator = null;
                        while(($next = $this->dir->read()) !== false){
                            if($next == '.' || $next == '..'){
                                continue;
                            }
                            $next = $this->path.'/'.$next;
                            if($next == self::$autoloaderDir){
                                continue;
                            }
                            break;
                        }
                        if(is_dir($next)){
                            $this->subdirectoryIterator = new DirectoryIterator($next);
                            continue;
                        }
                    }
                    break;
                }
                while($next !== false);
                return $next;
            }
        }

        public function __destruct(){
            $this->subdirectoryIterator = null;
            $this->dir->close();
        }
    }

}
