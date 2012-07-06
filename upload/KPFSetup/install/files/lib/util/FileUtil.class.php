<?php
namespace kpf\util;

/**
 * The file util class of the kpf contains util methods for file handling.
 * 
 * @author          Felix Jacobi <jacobi@keyway-xelonis.com>
 * @copyright       2011-2012 Keyway-Xelonis GmbH
 * @license         GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl.html>
 * @package         com.keyway-xelonis.kpf
 * @subpackage      util
 * @category        Portal Framework
 */

final class FileUtil {
    
    /**
     * This function put out the temp folder for example for unpack package files. 
     * 
     * @return          string
     */
    
    public static function getTempFolder() {
        
        // use the temp folder in the document root by default
        if (!empty($_SERVER['DOCUMENT_ROOT'])) :
            if (strpos($_SERVER['DOCUMENT_ROOT'], 'strato') !== false) :
                // strato bugfix
                // create the temp folder in the document root
                if (!@file_exist($_SERVER['DOCUMENT_ROOT'] . '/tmp/')) :
                    @mkdir($_SERVER['DOCUMENT_ROOT'] . '/tmp/', 0777);
                    @chmod($_SERVER['DOCUMENT_ROOT'] . '/tmp/', 0777);
                endif;
                // look for the temp folder
                if (@file_exist($_SERVER['DOCUMENT_ROOT'] . '/tmp')) :
                    return $_SERVER['DOCUMENT_ROOT'] . '/tmp/';
                endif;
            endif;
         endif;
         
         // try to use the environment temp folder
         if (isset($_ENV['TMP']) && @is_writable($_ENV['TMP'])) :
             return $_ENV['TMP'] . '/';
         elseif (isset($_ENV['TEMP']) && @is_writeable($_ENV['TEMP'])) :
             return $_ENV['TEMP'] . '/';
         elseif (isset($_ENV['TMPDIR']) && @is_writable($_ENV['TMPDIR'])) :
             return $_ENV['TMPDIR'] . '/';
         endif;
         
         // try to use the upload temp directory
         $uploadDirectory = ini_get('upload_tmp_dir');
         if (@is_writable($uploadDirectory)) :
             return $uploadDirectory;
         endif;
         
         // try to use the session save directory
         if (function_exists('session_save_path')) :
             $sessionSavePath = session_save_path();
             if (@is_writable($sessionSavePath)) :
                 return $sessionSavePath . '/';
             endif;
         endif;
         
         // try to use the standard tmp directory
         if (@file_exist('/tmp/') && @is_writable('/tmp/')) :
             return '/tmp/';
         endif;
         
         // the last alternate: use the own temp directory
         $kpfTmpDirectory = KPF_DIR . '/tmp/';
         if (@file_exist($kpfTmpDirectory) && @is_writable($kpfTmpDirectory)) :
             return $kpfTmpDirectory;
         else :
             if (ini_get('safe_mode')) :
                 $reason = 'due to php safe_mode restriction';
             else :
                 $reason = 'due an unkwown reason';
             endif;
             throw new kpf\system\exception\SystemException('There is no access to the system temporary folder ' . $reason . 'and no user specific temporary folder exist in <b>' . KPF_DIR . '! This is a misconfiguration of your webserver software! Please create a folder called <b>' . $kpfTmpDirectory . '</b>! Using your favourite ftp pogram, make it writable. Then your installation will be running.');
         endif;
    }
    
    /**
     * Removes a leading slash.
     * 
     * @param           string          $path
     * @return          string          $path 
     */
    
    public static function removeLeadingSlash($path) {
        return ltrim($path, '/');
    }
    
    /**
     * Removes a trailing slash.
     * 
     * @param           string          $path
     * @return          string          $path 
     */
    
    public static function removeTrailingSlash($path) {
        return rtrim($path, '/');
    }
    
    /**
     * Adds a trailing slash.
     * 
     * @param           string          $path
     * @return          string          $path 
     */
    
    public static function addTrailingSlash($path) {
        return rtrim($path, '/') . '/';
    }
    
    /**
     * Adds a leading slash.
     * 
     * @param           string          $path
     * @return          string          $path
     */
    
    public static function addLeadingSlash($path) {
        return '/' . ltrim($path, '/');
    }
    
    /**
     * Builds a relative path from two absolute paths.
     * 
     * @param           string          $currentDir
     * @param           string          $targetDir
     * @return          string          $relPath      
     */
    
    public static function getRelativePath($currentDir, $targetDir) {
        // remove trailing slahes
        $currentDir = self::removeTrailingSlash(self::unifyDirSeperator($currentDir));
        $targetDir = self::removeTrailingSlash(self::unifyDirSeperator($targetDir));
        
        if ($currentDir == $targetDir) :
            return './';
        endif;
        
        $current = explode('/', $currentDir);
        $target = explode('/', $targetDir);
        
        $relPath = '';
        
        for ($i = 0, $max = max(count($current), count($target)); $i < $max; $i++) :
            if (isset($current[$i]) && isset($target[$i])) :
                if ($current[$i] != $target[$i]) :
                    for ($j = 0; $j < $i; $j++) :
                        unset($target[$j]);
                    endfor;
                    $relPath .= str_repeat('../', count($current) - $i) . implode('/', $target) . '/';
                    for ($j = $i + 1; $j < count($current); $j++) :
                        unset($current[$j]);
                    endfor;
                    break;
                endif;
            // go up one level
            elseif (isset($current[$i]) && !isset($target[$i])) :
                $relPath .= '../';
            elseif (!isset($current[$i]) && isset($target[$i])) :
                $relPath .= $target[$i] . '/';
            endif;
        endfor;
        return $relPath;
    }
    
    
    
    /**
     * Unifies windows and unix directory seperators.
     * 
     * @param           string          $path
     * @return          string          $path 
     */
    
    public static function unifyDirSeperator($path) {
        $path = str_replace('\\\\', '/', $path);
        $path = str_replace('\\', '/', $path);
        return $path;
    }
}
?>