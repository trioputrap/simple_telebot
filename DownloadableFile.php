<?php 
    abstract class DownloadableFile {
        protected $dir = "download";
        protected $filename;
        protected $ext;
        
        protected function __construct($filename="", $ext){
            if($this->filename==""){
                $this->createFilename();
            } else {
                $this->filename = $filename;
            }
            $this->ext = $ext;
        }
        
        public abstract function create($data);

        public function createFilename($length = 8, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
        {
            $pieces = [];
            $max = mb_strlen($keyspace, '8bit') - 1;
            for ($i = 0; $i < $length; ++$i) {
                $pieces []= $keyspace[random_int(0, $max)];
            }
            $this->filename = implode('', $pieces);
        }

        public function getDirFile($ext=""){
            $ext = ($ext=="")?$this->ext:$ext;
            return $this->dir."/".$this->filename.".".$ext;
        }

        public function getFilename(){
            return $this->filename;
        }
        
        public function removeFile(){
            unlink($this->getDirFile());
        }

        public function send($url, $fields){
            //open connection
            $ch = curl_init();
    
            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_POST, count($fields));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);
            
            //So that curl_exec returns the contents of the cURL; rather than echoing it
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
            
            //execute post
            $result = curl_exec($ch);
            //$this->removeFile();
            return $result;
        }
    }