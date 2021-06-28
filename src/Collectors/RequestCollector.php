<?php

namespace LarDebug\Collectors;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
class RequestCollector implements ICollector{
    protected $request;
    protected $data = [];
    public function __construct(Request $request){
        $this->request = $request;
    }
    public function collect(){
        $data = [
            'id'=>$this->generateRequestId(),
            'method'=>$this->request->method(),
            'url'=>$this->request->url(),
            'auth'=>$this->isAuthorized(),
            'ajax'=>$this->isAjax()
        ];
        if(!empty($this->request->input('*'))){
           $data =  array_merge($data,['inputs'=>$this->request->toArray()]);
        }
        if($this->request->hasFile('*')){
            $data =  array_merge($data,[ 'files'=>$this->collectUploadedFilesInformation()]);
        }
        if(!empty($this->request->route()->gatherMiddleware())){
            $data =  array_merge($data,[ 'middleware'=>$this->request->route()->gatherMiddleware()]);
        }
        return $data;
        
    }

    protected function isAjax(){
        $isXMLRequest = $this->request->isXmlHttpRequest() || $this->request->headers->get('X-Livewire');
        $acceptable = $this->request->getAcceptableContentTypes();
        return (isset($acceptable[0]) && $acceptable[0] == 'application/json');
    }
    protected function isAuthorized(){
        return isset($this->request->user)?true:false;
    }
    protected function generateRequestId(){
        return round(\microtime(true)* 1000)."_".Str::random(10);
    }
    public function collectUploadedFilesInformation(){
        $files = [];
        foreach($this->request->files as $file){
            array_push($files,[
                'name'=>$file->getClientOriginalName(),
                
            ]);
        }
        return $files;
    }
}