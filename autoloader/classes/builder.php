<?php

namespace Autoloader {

    class Builder {
        const REGEXP_NAMESPACE = '/(namespace)\s+([_A-Za-z0-9\\\]+)/';
        const REGEXP_CLASS     = '/(class|interface|trait)\s+([_A-Za-z0-9]+)/';

        private $map = array();
        private $path = './';

        public function __construct($path){
            $this->path = realpath($path);
        }

        public function run(){
            $iterator = new DirectoryIterator($this->path);
            while(($filename = $iterator->next()) !== false){
                $fileContent = file_get_contents($filename);
                $classes = $this->extractClasses($fileContent);
                foreach($classes as $v){
                    $this->map[$v] = $filename;
                }
            }
            $this->saveMap();
        }

        private function extractClasses($fileContent){
            $result = array();
            $namespace = '';
            $matches = array();
            preg_match_all(self::REGEXP_NAMESPACE, $fileContent, $matches);
            if(isset($matches[2], $matches[2][0])){
                $namespace = $matches[2][0];
            }
            $matches = array();
            preg_match_all(self::REGEXP_CLASS, $fileContent, $matches);
            if(isset($matches[2]) && is_array($matches[2]) && count($matches[2]) > 0){
                foreach($matches[2] as $className){
                    $result[] = empty($namespace) ? $className : $namespace.'\\'.$className;
                }
            }
            return $result;
        }

        private function saveMap(){
            $mapFilename = realpath(__DIR__.'/../map.php');
            $f = fopen($mapFilename, 'wb');
            if($f === false){
                echo 'Can not write to "'.$mapFilename.'". Aborted.', "\n";
            }
            else{
                fwrite($f, '<?php'."\n".'return '.var_export($this->map, true).';'."\n");
                fclose($f);
            }
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
