<?php





class View {
    
    private $path = "";
    private $files = [];
    
    /*--------------------------------------------------*/
    
    
    public function __construct(
            $path
    ){
        if (!file_exists($_SERVER['DOCUMENT_ROOT']. $path)){
            throw new Exception("Dir not found ". $_SERVER['DOCUMENT_ROOT']. $path);
        }
        $this->path = $_SERVER['DOCUMENT_ROOT']. $path;
        $buf = scandir($this->path,  SCANDIR_SORT_DESCENDING);
        foreach($buf as $value){
            if (!is_dir($this->path.$value)){
                $key = preg_replace("/\..+$/", "", $value);
                $this->files[$key] = file_get_contents($this->path. $value);
                if ($key == "script"){
                    $this->files[$key] = "<script>\n". $this->files[$key]. "</script>";
                }
            }
        }
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function show(
            $_fileName_,
            $data = []
    ){
        extract($data);
//        if (!file_exists($this->path. $_fileName_. ".php")){
//            throw new Exception("File not found ". $this->path. $_fileName_. ".php");
//        }
//        require $this->path. $_fileName_. ".php";
        echo $this->files[$_fileName_];
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    
    public function showFiles(){
        foreach($this->files as $key => $file){
            echo $key. "\n\n". $file. "\n\n\n\n";
        }
    }
    
    /*--------------------------------------------------*/
    
    
    
    
    
    
    
    
    
}
