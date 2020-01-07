<?php

class Files {

    public $title = 'Файлы';

    public $uploadRoot = 'data/files/';
    public $uploadPath = '';

    function __construct($dir = '') {
        $this->uploadPath = $dir ?: $this->uploadRoot;
    }

    public function upload($files) {
        $uploaded = [];
        for ($i = 0; $i < count($files['name']); $i++) {
            $fileName = $files['name'][$i];
            
            $fileExt = mb_strtolower(pathinfo($fileName)['extension']);
            // Загружаемые расширения
            if (!in_array($fileExt, ['jpeg', 'jpg', 'png', 'pdf'])) {
                continue;
            }
            
            if (!file_exists($this->uploadPath)) {
                mkdir($this->uploadPath, 0777, true);
            }

            $filePath = $this->uploadPath . $fileName;
            if (move_uploaded_file($files['tmp_name'][$i], $filePath)) {
                $uploaded[] = $filePath;
            }

            if (preg_match('/-(\d+)px\..+/', $filePath, $matches)) {
                Files::resize($filePath, $matches[1]);
            }
        }
        return $uploaded;
    }

    public function get() {
        $files = scandir($this->uploadPath);
        $files = array_splice($files, 2);
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

    public function rename($oldName, $newName) {
        return rename($oldName, $newName) ? 1 : 0;
    }

    public function delete($files, $inUploadPath = false) {
        foreach ($files as $file) {
            $ssUploadPath = preg_replace('/\//', '\/', $this->uploadPath);
            if ($inUploadPath && !preg_match("/$ssUploadPath/", $file)) {
                continue;
            }

            if (!@unlink($file)) {
                $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($file));
                foreach ($it as $path) {
                    $currPath = preg_replace('/\.+$/', '', $path);
                    @unlink($currPath);
                    @rmdir($currPath);
                }
            }
        }

        return 1;
    }

    public function dirCreate($name = '') {
        $path = $this->uploadPath . ($name ?: 'new_' . uniqid());
        mkdir($path, 0777, true);
        return 1;
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