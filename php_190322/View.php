<?php





class View {
    
    private $path = "";
    
    
    /*--------------------------------------------------*/
    
    
    public function __construct(
            $path
    ){
        if (!file_exists($_SERVER['DOCUMENT_ROOT']. $path)){
            throw new Exception("Dir not found ". $_SERVER['DOCUMENT_ROOT']. $path);
        }
        $this->path = $_SERVER['DOCUMENT_ROOT']. $path;
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function show(
            $_fileName_,
            $data = []
    ){
        extract($data);
        if (!file_exists($this->path. $_fileName_. ".php")){
            throw new Exception("File not found ". $this->path. $_fileName_. ".php");
        }
        require $this->path. $_fileName_. ".php";
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    
    
    
    /*--------------------------------------------------*/
    
    
    
    
    
    
    
    
    
}
