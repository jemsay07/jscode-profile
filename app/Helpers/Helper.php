<?php
namespace App\Helpers;

class Helper {

    /**
     * Descriptions: Add number if exist the file for media upload
     */
    public static function file_newname($path, $filename){
        if ($pos = strrpos($filename, '.')) {
            $name = substr($filename, 0, $pos);
            $ext = substr($filename, $pos);
        } else {
            $name = $filename;
        }

        $newpath = $path . DIRECTORY_SEPARATOR . $filename;
        $newname = $filename;
        $counter = 1;
        while (file_exists($newpath)) {
            $newname = $name . '(' . $counter . ')' . $ext;
            $newpath = $path . DIRECTORY_SEPARATOR . $newname;
            $counter++;
        }

        return $newname;
    }

}
