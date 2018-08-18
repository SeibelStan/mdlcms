<?php

class Files {

    public $title = 'Файлы';

    public $uploadRoot = 'data/files/';
    public $uploadPath = '';

    function __construct($dir = '') {
        $this->uploadPath = $dir ?: $this->uploadRoot;
    }

    public function upload($data) {
        $files = $data['files'];
        $uploaded = [];
        for ($i = 0; $i < count($files['name']); $i++) {
            $fileName = $files['name'][$i];
            
            $fileExt = mb_strtolower(pathinfo($fileName)['extension']);
            // Загружаемые расширения
            if (!in_array($fileExt, ['jpg', 'png', 'pdf'])) {
                continue;
            }
            
            $filePath = $this->uploadPath . $fileName;
            if (move_uploaded_file($files['tmp_name'][$i], $filePath)) {
                $uploaded[] = $filePath;
            }
            @chmod($filePath, 0777);

            $postfix = false;
            $matches = [];
            if (preg_match('/-(\d+)px\..+/', $filePath, $matches)) {
                Files::resize($filePath, $matches[1]);
            }
        }
        return $uploaded;
    }

    public function uploadSingles($files, $names = []) {
        $uploaded = [];
        foreach ($files as $k => $file) {
            echo $names[$k];

            $fileName = @$names[$k] ?: $file['name'];
            $filePath = $this->uploadPath . $fileName;
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                $uploaded[] = $filePath;
            }
            @chmod($filePath, 0777);

            $postfix = false;
            $matches = [];
            if (preg_match('/-(\d+)px\..+/', $filePath, $matches)) {
                Files::resize($filePath, $matches[1]);
            }
        }
        return $uploaded;
    }

    public function get() {
        $files = delDots(scandir($this->uploadPath));
        $iconBase = ROOT . 'assets/img/';

        $returnFiles = [];
        foreach ($files as $file) {
            $fullName = $this->uploadPath . $file;
            $fileName = $file;
            $icon = $iconBase . 'inode_file.png';
            $type = 'file';
            $ext = '';

            if (is_dir($fullName)) {
                $type = 'dir';
                $icon = $iconBase . 'inode_directory.png';
                $fullName .= '/';
            }
            else {
                $pathInfo = pathinfo($fullName);
                if (isset($pathInfo['extension'])) {
                    $ext = $pathInfo['extension'];
                }
            }

            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'svg', 'png', 'gif'])) {
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

        return $returnFiles;
    }

    public function delete($files, $inUploadPath = true) {
        $uploadRootPrepared = preg_replace('/\//', '\/', $this->uploadRoot);
        foreach ($files as &$path) {
            if ($inUploadPath) {
                $path = $this->uploadPath . preg_replace('/' . $uploadRootPrepared . '/', '', $path);
            }
            $this->deleteDirectory($path);
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

    public function deleteDirectory($path) {
        if (!is_dir($path)) {
            return unlink($path) ? 1 : 0;
        }
        $files = delDots(scandir($path));
        foreach ($files as $file) {
            is_dir($path . '/' . $file) ? deleteDirectory($path . '/' . $file) : unlink($path . '/' . $file);
        }
        return rmdir($path) ? 1 : 0;
    }

    public static function resizeEngine($src, $dest, $width, $height, $rgb = 0xFFFFFF, $quality = 95) {
        if (!file_exists($src)) {
            return 0;
        }  
        $imgSizes = getimagesize($src);
        $imgFormat = explode('/', $imgSizes['mime'])[1];
        $icFunc = "imagecreatefrom" . $imgFormat;
        $image = $icFunc($src);
        $imgDesc = imagecreatetruecolor($width, $height);
        imagefill($imgDesc, 0, 0, $rgb);
        imagecopyresampled($imgDesc, $image, 0, 0, 0, 0, $width, $height, $imgSizes[0], $imgSizes[1]);
        imagejpeg($imgDesc, $dest, $quality);
        imagedestroy($image);
        imagedestroy($imgDesc);
        return 1; 
    }

    public static function resize($src, $size = 800) {
        $imgSizes = getimagesize($src);
        $result = 0;
        if ($imgSizes[0] > $size) {
            $height = $imgSizes[1] * $size / $imgSizes[0];
            $result = Files::resizeEngine($src, $src, $size, $height) ? 'width' : 0;
        }

        $imgSizes = getimagesize($src);
        if ($imgSizes[1] > $size) {
            $width = $imgSizes[0] * $size / $imgSizes[1];
            $result = Files::resizeEngine($src, $src, $width, $size) ? 'height' : 0;
        }
        return $result;
    }

    public static function mark($src, $mark, $pos = 3) {
        if (!file_exists($src) || !file_exists($mark)) {
            return 0;
        }

        $imgSizes = getimagesize($src);
        $wmSizes  = getimagesize($mark);

        $imgFormat = explode('/', $imgSizes['mime'])[1];
        $icFunc = "imagecreatefrom" . $imgFormat;
        $image = $icFunc($src);
        $wm = imagecreatefrompng($mark);

        switch ($pos) {
            case 1: {
                $wmPosX = 0;
                $wmPosY = 0;
                break;
            }
            case 2: {
                $wmPosX = $imgSizes[0] - $wmSizes[0];
                $wmPosY = 0;
                break;
            }
            case 3: {
                $wmPosX = $imgSizes[0] - $wmSizes[0];
                $wmPosY = $imgSizes[1] - $wmSizes[1];
                break;
            }
            case 4: {
                $wmPosX = 0;
                $wmPosY = $imgSizes[1] - $wmSizes[1];
                break;
            }
            default: {
                $wmPosX = ($imgSizes[0] - $wmSizes[0]) / 2;
                $wmPosY = ($imgSizes[1] - $wmSizes[1]) / 2;
                break;
            }
        }

        imagecopy($image, $wm, $wmPosX, $wmPosY, 0, 0, $wmSizes[0], $wmSizes[1]);
        imagejpeg($image, $src, 95);
        imagedestroy($wm);
        return 1;
    }

    public static function download($file, $name) {
        if (file_exists($file)) {
            if (ob_get_level()) {
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

            if ($fd = fopen($file, 'rb')) {
                while (!feof($fd)) {
                    print fread($fd, 1024);
                }
                fclose($fd);
            }
            exit;
        }
    }

}