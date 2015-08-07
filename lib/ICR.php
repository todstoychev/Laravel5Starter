<?php

namespace Lib;

use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Gd\Imagine;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\Config;

class ICR
{

    /**
     * Uploads path
     *
     * @var String
     */
    private $uploadsPath;

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
     * @var UploadedFile
     */
    private $file;

    /**
     * Calculated image width
     *
     * @var int
     */
    private $calculatedWidth;

    /**
     * Calculated image height
     *
     * @var int
     */
    private $calculatedHeight;

    /**
     * Image manipulations
     */
    const CROP = 'crop';
    const RESIZE = 'resize';
    const RESIZE_CROP = 'resize-crop';

    public function __construct($context, UploadedFile $file)
    {
        $this->uploadsPath = Config::get('image_crop_resizer.uploadsPath');
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
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Processes the image
     *
     * @param string $context Context name
     */
    private function process($context)
    {
        $original = $this->saveOriginalFile($context);
        $config = $this->contexts[$context];

        foreach ($config as $size => $data) {
            $file = $original->copy();

            if ($data['operation'] == self::CROP) {
                $this->crop($file, $data, $context, $size);
            } elseif ($data['operation'] == self::RESIZE) {
                $this->resize($file, $data, $context, $size);
            } elseif ($data['operation'] == self::RESIZE_CROP) {
                $this->resizeCrop($file, $data, $context, $size);
            }
        }
    }

    /**
     * Crop image
     *
     * @param UploadedFile $file
     * @param array $data
     * @param string $context
     * @param string $size
     */
    private function crop($file, $data, $context, $size)
    {
        $file->crop($this->calcCropPoint($file, $data['width'], $data['height']), new Box($data['width'], $data['height']));
        $file->save(public_path($this->uploadsPath . '/' . $context . '/' . $size . '/' . $this->filename));
    }

    /**
     * Resize image
     *
     * @param UploadedFile $file
     * @param array $data
     * @param string $context
     * @param string $size
     */
    private function resize($file, $data, $context, $size)
    {
        $file->resize(new Box($data['width'], $data['height']));
        $file->save(public_path($this->uploadsPath . '/' . $context . '/' . $size . '/' . $this->filename));
    }

    /**
     * Resize and crops image
     *
     * @param UploadedFile $file
     * @param array $data
     * @param string $context
     * @param string $size
     */
    private function resizeCrop($file, $data, $context, $size)
    {
        $this->calcOutputSize($file, $data['width'], $data['height']);
        $file->resize(new Box($this->calculatedWidth, $this->calculatedHeight));
        $file->crop($this->calcCropPoint($file, $data['width'], $data['height']), new Box($data['width'], $data['height']));
        $file->save(public_path($this->uploadsPath . '/' . $context . '/' . $size . '/' . $this->filename));
    }

    /**
     * Checks and creates the necessary directory structure
     *
     * @param string $context Image context
     */
    private function checkDir($context)
    {
        $folders = explode('/', $this->uploadsPath);
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
    private function createDir($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0755);
        }
    }

    /**
     * Checks and generates the unique filename
     *
     * @param string $context Context name
     * @param string $ext File type extension
     * @return string
     */
    private function generateImageName($context, $ext)
    {
        $name = md5(microtime()) . '.' . $ext;
        $path = public_path($this->uploadsPath . '/' . $context . '/' . $name);

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
     * @return \Imagine\Imagick\Imagine
     */
    private function saveOriginalFile($context)
    {
        $path = public_path($this->uploadsPath . '/' . $context);
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
    private function determineFilesizeLimit()
    {
        $phpIni = ini_get('upload_max_filesize');
        $mb = str_replace('M', '', $phpIni);
        $bytes = $mb * 1048576;

        if ($this->file->getSize() > $bytes) {
            throw new \Exception('File too large');
        }
    }

    /**
     * Calculates the image resize width and height based on the desired measures
     *
     * @param UploadedFile $file
     * @param int $outputWidth
     * @param int $outputHeight
     */
    private function calcOutputSize($file, $outputWidth, $outputHeight)
    {
        $width_ratio = $file->getSize()->getWidth() / $outputWidth;
        $height_ratio = $file->getSize()->getHeight() / $outputHeight;
        $ratio = min($width_ratio, $height_ratio);
        $width = round($file->getSize()->getWidth() / $ratio);
        $height = round($file->getSize()->getHeight() / $ratio);

        $this->calculatedWidth = (int)$width;
        $this->calculatedHeight = (int)$height;
    }

    /**
     * Calculates the position of the crop point and returns Imagine Point object
     *
     * @param UploadedFile $file
     * @param integer $outputWidth Output width
     * @param integer $outputHeight Output height
     * @return Point Imagine Point interface instance
     */
    private function calcCropPoint($file, $outputWidth, $outputHeight)
    {
        if ($file->getSize()->getHeight() > $outputHeight) {
            $y = ($file->getSize()->getHeight() - $outputHeight) / 2;
            $x = 0;
        } else if ($file->getSize()->getWidth() > $outputWidth) {
            $x = ($file->getSize()->getWidth() - $outputWidth) / 2;
            $y = 0;
        } else {
            $x = ($file->getSize()->getWidth() - $outputWidth) / 2;
            $y = ($file->getSize()->getHeight() - $outputHeight) / 2;
        }
        $x = round($x);
        $y = round($y);

        return new Point((int)$x, (int)$y);
    }

}
