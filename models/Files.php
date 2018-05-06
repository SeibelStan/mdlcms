<?php

class Files extends A_BaseModel {

    public $title = 'Файлы';

    public $uploadRoot = 'data/files/';
    public $uploadPath = '';

    function __construct($dir = '') {
        $this->uploadPath = $dir ?: $this->uploadRoot;
    }

    public function upload($data) {
        $files = $data['files'];
        $uploaded = [];
        for($i = 0; $i < count($files['name']); $i++) {
            $fileName = $files['name'][$i];
            $filePath = $this->uploadPath . $fileName;
            if(move_uploaded_file($files['tmp_name'][$i], $filePath)) {
                $uploaded[] = $filePath;
            }
            @chmod($filePath, 0777);

            $postfix = false;
            $matches = [];
            if(preg_match('/-(\d+)px\..+/', $filePath, $matches)) {
                Files::resize($filePath, $matches[1]);
            }
        }
        return $uploaded;
    }

    public function uploadSingles($files, $names = []) {
        $uploaded = [];
        foreach($files as $k => $file) {
            echo $names[$k];

            $fileName = @$names[$k] ?: $file['name'];
            $filePath = $this->uploadPath . $fileName;
            if(move_uploaded_file($file['tmp_name'], $filePath)) {
                $uploaded[] = $filePath;
            }
            @chmod($filePath, 0777);

            $postfix = false;
            $matches = [];
            if(preg_match('/-(\d+)px\..+/', $filePath, $matches)) {
                Files::resize($filePath, $matches[1]);
            }
        }
        return $uploaded;
    }

    public function get() {
        $files = delDots(scandir($this->uploadPath));
        $iconBase = ROOT . 'assets/img/';

        $returnFiles = [];
        foreach($files as $file) {
            $fullName = $this->uploadPath . $file;
            $fileName = $file;
            $icon = $iconBase . 'inode_file.png';
            $type = 'file';
            $ext = '';

            if(is_dir($fullName)) {
                $type = 'dir';
                $icon = $iconBase . 'inode_directory.png';
                $fullName .= '/';
            }
            else {
                $pathInfo = pathinfo($fullName);
                if(isset($pathInfo['extension'])) {
                    $ext = $pathInfo['extension'];
                }
            }

            if(in_array(strtolower($ext), ['jpg', 'jpeg', 'svg', 'png', 'gif'])) {
                $icon = ROOT . $fullName;
            }

            array_push($returnFiles, [
                'type' => $type,
                'name' => $fileName,
                'fullname' => $fullName,
                'icon' => $icon,
                'ext' => $ext
            ]);
        }

        usort($returnFiles, function ($item1, $item2) {
            return $item1['type'] <=> $item2['type'];
        });

        return json_encode($returnFiles);
    }

    public function remove($files, $inUploadPath = true) {
        $uploadRootPrepared = preg_replace('/\//', '\/', $this->uploadRoot);
        foreach($files as &$path) {
            if($inUploadPath) {
                $path = $this->uploadPath . preg_replace('/' . $uploadRootPrepared . '/', '', $path);
            }
            $this->removeDirectory($path);
        }
        return 1;
    }

    public function rename($oldName, $newName) {
        print_r(scandir($oldName));
        print_r(scandir($newName));
        return rename($oldName, $newName) ? 1 : 0;
    }

    public function createDir($name = '') {
        $path = $this->uploadPath . ($name ?: 'new_' . uniqid());
        mkdir($path);
        chmod($path, 0777);
        return 1;
    }

    public function removeDirectory($path) {
        if(!is_dir($path)) {
            return unlink($path) ? 1 : 0;
        }
        $files = delDots(scandir($path));
        foreach ($files as $file) {
            is_dir($path . '/' . $file) ? removeDirectory($path . '/' . $file) : unlink($path . '/' . $file);
        }
        return rmdir($path) ? 1 : 0;
    }

    public static function resizeEngine($src, $dest, $width, $height, $rgb = 0xFFFFFF, $quality = 95) {
        if(!file_exists($src)) {
            return false;
        }
        $size = getimagesize($src);
        if($size === false) {
            return false;
        }
        $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
        $icfunc = "imagecreatefrom" . $format;
        if(!function_exists($icfunc)) {
            return false;
        }
        $isrc = $icfunc($src);
        $idest = imagecreatetruecolor($width, $height);
        imagefill($idest, 0, 0, $rgb);
        imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
        imagejpeg($idest, $dest, $quality);
        imagedestroy($isrc);
        imagedestroy($idest);
        return 1;
    }

    public static function resize($src, $width = 800) {
        $size = getimagesize($src);
        if($size[0] > $width or $size[1] > $width) {
            $height = $size[1] * $width / $size[0];
            Files::resizeEngine($src, $src, $width, $height);
        }
        return $width;
    }

    public static function download($file, $name) {
        if(file_exists($file)) {
            if(ob_get_level()) {
                ob_end_clean();
            }

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($name));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));

            if($fd = fopen($file, 'rb')) {
                while(!feof($fd)) {
                    print fread($fd, 1024);
                }
                fclose($fd);
            }
            exit;
        }
    }

}