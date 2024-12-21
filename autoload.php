<?php
/**
 * @param $dir
 * @return void
 */
function requireFilesRecursive($dir): void{
    ///
    /// Remove trailig slash
    ///
    if(str_ends_with($dir, '/') || str_ends_with($dir, "\\")) $dir = substr($dir, 0, -1);

    $files = scandir($dir);

    foreach ($files as $file){
        if(str_ends_with($file, '.php') && $file != 'autoload.php'){
            require $dir . '/' . $file;
        } elseif (is_dir($dir. '/' . $file)  && $file != '.' && $file != '..'){
            requireFilesRecursive($dir . '/' . $file);
        }
    }
}

///
/// Simple autoloader of files
///

requireFilesRecursive(__DIR__);