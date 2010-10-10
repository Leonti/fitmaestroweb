<?php defined('SYSPATH') OR die('No direct access allowed.');

class files_Core{

    // create new entry in files and fill it with path to newly created file
    public static function copyFile($user_id, $file_id){
        $files = new File_Model($user_id);
        $old_file = $files->getItem($file_id);
        $old_filename = $old_file[0]->filename;
        $ext = substr($old_filename, strrpos($old_filename, '.') + 1);
        $frames = $old_file[0]->frames;
        $uploads_folder = Kohana::config('core.uploads_folder');
        $new_basename = md5(uniqid());
        $new_filename = $new_basename . '.' . $ext;

        // copy file itself
        copy($uploads_folder . $old_filename, $uploads_folder . $new_filename);

        //copy it's frames
        $old_basename = basename($uploads_folder . $old_filename, "." . $ext);

        if($frames > 1){
            for($i = 1; $i <= $frames; $i++){
                copy($uploads_folder . $old_basename . "-" . $i . "." . $ext, $uploads_folder . $new_basename . "-" . $i . "." . $ext);
            }
        }
        return $files->addItem(array('filename'=>$new_filename, 'frames' => $frames));
    }
}
