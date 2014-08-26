<?php
class files{
    public function upload($file_name,$file_size,$file_tmp,$file_type){
        $errors= array(); 
        $file_ext=strtolower(end(explode('.',$file_name)));
        $extensions = array("jpeg","jpg","png"); 		
        if(in_array($file_ext,$extensions )=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
        }
        if($file_size > 2097152){
            $errors[]='File size must be excately 2 MB';
        }
        $imageLocation="images/".md5($file_name).'.'.$file_ext;
        if(empty($errors)==true){
            move_uploaded_file($file_tmp,$imageLocation);
            return $imageLocation;
        }else{
            print_r($errors);
            
        }
    }
    public function pull_image($url){
        $ext = end(explode(".",strtolower(basename($url))));
        $name = basename($url);
        $file = file_get_contents($url); 
        $final = md5($name).$ext;
        $location = "images/".$final;
        
        //check if the files are only image / document
        if($ext == "jpg" || $ext == "png" || $ext == "gif"){
             $upload = file_put_contents($location,$file);
        if($upload){
            
        }else{
            echo "Please upload only image/document files";
        }
        return $location;
    }
    }
        
}
?>