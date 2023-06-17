<?php
/*
@author: maimai 
@date created: 06-2-2014
*/

$root_path = '../../';
include($root_path.'include/inc_environment_global.php'); /* get radresult_img path as image directory*/     
$img_path = $root_path.radresult_img;
$thumb_path = $root_path.radresult_img_thumb;

    class RadioUploadImage {
        /*var $thumb_path;*/
        var $img_path;
        var $image; 
        var $image_type;
        var $filename;
        var $name;
        var $extension;

        function check($file){
            global $img_path;/* global $thumb_path;*/
            $this->img_path = $img_path;
            /*  $this->thumb_path = $thumb_path;*/

            $this->image_type = $file["type"];
            $allowedExts = array("gif", "jpeg", "jpg", "png");
            $this->filename = $file["name"];
            $temp = explode(".", $this->filename);
            $this->name = $temp[0];
            $this->extension = end($temp);
            
            if ((($this->image_type == "image/gif") || ($this->image_type == "image/jpeg") || ($this->image_type == "image/jpg") || ($this->image_type == "image/png")) && in_array($this->extension, $allowedExts)) {
                if ($file["error"] > 0) {
                    return $file["error"];
                } 

                return "";
            }

            else{
                return "File format is invalid";
            }
        }

        function createdir($directory){
            if(!is_dir($directory)){
                mkdir($directory);
            }
        }

        function filename(){ 
            if (file_exists($this->img_path.$this->filename)) {
                $count = 1;
                $orig_filename = $this->name.'_'.$count.'.'.$this->extension;
                
                while(file_exists($this->img_path.$this->name.'_'.$count.'.'.$this->extension)){
                    $count++;
                    $orig_filename = $this->name.'_'.$count.'.'.$this->extension;
                }

                $this->filename =  $orig_filename;
            }

            return $this->filename;
        }

        function load() {   
            $file_name = $this->img_path.$this->filename;
            $image_info = getimagesize($file_name); 
            $this->image_type = $image_info[2]; 
            if( $this->image_type == IMAGETYPE_JPEG ){   
                $this->image = imagecreatefromjpeg($file_name); 
            } 
            
            elseif( $this->image_type == IMAGETYPE_GIF ){   
                $this->image = imagecreatefromgif($file_name); 
            } 
            
            elseif( $this->image_type == IMAGETYPE_PNG ){   
                $this->image = imagecreatefrompng($file_name); 
            } 
        } 

        /*function save($image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null){   
            $this->createdir($this->thumb_path);
            $file_name = $this->thumb_path.$this->filename;

            if( $image_type == IMAGETYPE_JPEG ){ 
                imagejpeg($this->image,$file_name,$compression); 
            }

            elseif( $image_type == IMAGETYPE_GIF ){   
                imagegif($this->image,$file_name); 
            } 
            
            elseif( $image_type == IMAGETYPE_PNG ){   
                imagepng($this->image,$file_name); 
            } 
            
            if( $permissions != null) {  
             chmod($file_name,$permissions); 
            } 
         } */

        function resize($width,$height) { 
            $new_image = imagecreatetruecolor($width, $height); 
            imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight()); 
            $this->image = $new_image;
        }   

        function getWidth() {   
            return imagesx($this->image); 
        } 

        function getHeight() {   
            return imagesy($this->image); 
        }    

        function compress($source, $destination, $quality) { //compress image
            $info = getimagesize($source); 
            if ($info['mime'] == 'image/jpeg') {
                $image = imagecreatefromjpeg($source); 
                imagejpeg($image, $destination, $quality); 
            }
            elseif ($info['mime'] == 'image/gif') {
                $image = imagecreatefromgif($source); 
                imagegif($image, $destination, $quality); 
            }
            elseif ($info['mime'] == 'image/png') {
                $image = imagecreatefrompng($source); 
                imagepng($image, $destination, $quality); 
            }

            return $destination; 
        }

        function upload($img, $size){ //upload image
            $one_MB = 1048576;

            if($size > $one_MB){
                $img = $this->compress($img, $this->img_path.$this->filename, 80);
            }
            
            $this->createdir($this->img_path);
            move_uploaded_file($img, $this->img_path.$this->filename);
        }
    }

?>