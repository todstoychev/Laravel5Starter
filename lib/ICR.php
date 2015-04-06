<?php

namespace Lib;

use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Imagick\Imagine;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\Config;

class ICR {

    /**
     * Uploads path
     *
     * @var String
     */
    private $uploads_path;

    /**
     * Contexts configuration
     *
     * @var Array
     */
    private $contexts;

    /**
     * Generated unique filename
     *
     * @var string
     */
    private $filename;

    /**
     * File object
     *
     * @var Symfony\Component\HttpFoundation\File\UploadedFile
     */
    private $file;

    /**
     * Calculated image width
     *
     * @var int
     */
    private $calc_width;

    /**
     * Calculated image height
     *
     * @var int
     */
    private $calc_height;

    /**
     * Image manipulations
     */
    const CROP = 'crop';
    const RESIZE = 'resize';
    const RESIZE_CROP = 'resize-crop';

    public function __construct($context, UploadedFile $file) {
        $this->uploads_path = Config::get('image_crop_resizer.uploads_path');
        $this->contexts = Config::get('image_crop_resizer');
        $this->checkDir($context);
        $this->file = $file;
        $this->filename = $this->generateImageName($context, $file->getClientOriginalExtension());
        $this->determineFilesizeLimit();
        $this->process($context);
    }
    
    /**
     * Gets the filename for the uploaded image
     * 
     * @return string
     */
    public function getFilename() {
        return $this->filename;
    }

    /**
     * Processes the image
     * 
     * @param string $context Context name
     */
    private function process($context) {
        $original = $this->saveOriginalFile($context);
        $config = $this->contexts[$context];

        foreach ($config as $size => $data) {
            $file = $original->copy();
            
            if ($data['operation'] == self::CROP) {
                $file->crop($this->calcCropPoint($file, $data['width'], $data['height']), new Box($data['width'], $data['height']));
                $file->save(public_path($this->uploads_path . '/' . $context . '/' . $size . '/' . $this->filename));
            } elseif ($data['operation'] == self::RESIZE) {
                $file->resize(new Box($data['width'], $data['height']));
                $file->save(public_path($this->uploads_path . '/' . $context . '/' . $size . '/' . $this->filename));
            } elseif ($data['operation'] == self::RESIZE_CROP) {
                $this->calcOutputSize($file, $data['width'], $data['height']);
                $file->resize(new Box($this->calc_width, $this->calc_height));
                $file->crop($this->calcCropPoint($file, $data['width'], $data['height']), new Box($data['width'], $data['height']));
                $file->save(public_path($this->uploads_path . '/' . $context . '/' . $size . '/' . $this->filename));
            }
        }
    }

    /**
     * Checks and creates the necessary directory structure
     * 
     * @param string $context Image context
     */
    private function checkDir($context) {
        $folders = explode('/', $this->uploads_path);
        $path = public_path();

        // Create base dir structure
        foreach ($folders as $folder) {
            if (strlen($folder) > 0) {
                $path .= '/' . $folder;
                $this->createDir($path);
            }
        }

        // Create context directory structure
        $this->createDir($path . '/' . $context);

        foreach ($this->contexts[$context] as $size => $data) {
            $this->createDir($path . '/' . $context . '/' . $size);
        }
    }

    /**
     * Creates directory if not exists
     * 
     * @param string $path Path to directory
     */
    private function createDir($path) {
        if (!is_dir($path)) {
            mkdir($path, 0755);
        }
    }

    /**
     * Checks and generates the unique filename
     * 
     * @param string $context Context name
     * @param string $ext Fyletype extension
     * @return string
     */
    private function generateImageName($context, $ext) {
        $name = md5(microtime()) . '.' . $ext;
        $path = public_path($this->uploads_path . '/' . $context . '/' . $name);

        if (is_file($path)) {
            $this->generateImageName($context, $ext);
        } else {
            return $name;
        }
    }

    /**
     * Saves the original file and returns an Imagine instance of the file
     * 
     * @param string $context Context name
     * @param UploadedFile $file
     * @return \Imagine\Imagick\Imagine
     */
    private function saveOriginalFile($context) {
        $path = public_path($this->uploads_path . '/' . $context);
        $this->file->move($path, $this->filename);

        $imagine = new Imagine();
        $image = $imagine->open($path . '/' . $this->filename);

        return $image;
    }

    /**
     * Determines if the file is too large
     * 
     * @throws Exception
     */
    private function determineFilesizeLimit() {
        $php_ini = ini_get('upload_max_filesize');
        $mb = str_replace('M', '', $php_ini);
        $bytes = $mb * 1048576;

        if ($this->file->getSize() > $bytes) {
            throw new Exception('File too large');
        }
    }

    /**
     * Calculates the image resize width and height based on the desired measures
     * 
     * @param int $output_width
     * @param int $output_height
     */
    private function calcOutputSize($file, $output_width, $output_height) {
        $width_ratio = $file->getSize()->getWidth() / $output_width;
        $height_ratio = $file->getSize()->getHeight() / $output_height;
        $ratio = min($width_ratio, $height_ratio);
        $width = round($file->getSize()->getWidth() / $ratio);
        $height = round($file->getSize()->getHeight() / $ratio);

        $this->calc_width = (int) $width;
        $this->calc_height = (int) $height;
    }

    /**
     * Calculates the position of the crop point and returns Imagine Point object
     *
     * @param integer $output_width Output width
     * @param integer $output_height Output height
     * @return Point Imagine Point interface instance
     */
    private function calcCropPoint($file, $output_width, $output_height) {
        if ($file->getSize()->getHeight() > $output_height) {
            $y = ($file->getSize()->getHeight() - $output_height) / 2;
            $x = 0;
        } else if ($file->getSize()->getWidth() > $output_width) {
            $x = ($file->getSize()->getWidth() - $output_width) / 2;
            $y = 0;
        } else {
            $x = ($file->getSize()->getWidth() - $output_width) / 2;
            $y = ($file->getSize()->getHeight() - $output_height) / 2;
        }
        $x = round($x);
        $y = round($y);
        return new Point((int) $x, (int) $y);
    }

}
