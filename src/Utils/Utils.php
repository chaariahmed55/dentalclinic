<?php

namespace App\Utils;

class Utils
{
    public static function jresponce(string $status, string $message, $data): string
    {
        return '{ 
            "STATUS" : "'.$status.'", 
            "MESSAGE": "'.preg_replace('/[^a-zA-Z0-9_.]/', ' ', $message).'",
            "DATA": '.$data.'
        }';
    }

    public static function uploadimage($projectDir, $image, $name): string
    {
        //$projectDir = $this->getParameter('kernel.project_dir');
        $path =  $projectDir. '/public/images/equipement/';
        
        $image_parts = explode(";base64,", $image);
        
        $image_type_aux = explode("image/", $image_parts[0]);
        
        $image_type = $image_type_aux[1];
            
        $image_base64 = base64_decode($image_parts[1]);
    
        $iamagename = $name. uniqid() . '.'.$image_type;
    
        $file = $path . $iamagename;

        file_put_contents($file, $image_base64);

        return $iamagename;
    }
}

?>