<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';





class Log {
    
    
    static public function write(
            $log
    ){
        $path = $_SERVER['DOCUMENT_ROOT']."/_FDB/temp/log.txt";
        
        $str = "\n[ ". time(). "] : ". $log;
        echo $str;
        file_put_contents($path, $str);
    }
    
}
