<?php

namespace ZxingSPE;

use ZxingSPE\Common\HybridBinarizer;
use ZxingSPE\Exceptions\ChecksumException;
use ZxingSPE\Exceptions\FormatException;
use ZxingSPE\Exceptions\NotFoundException;
use ZxingSPE\Luminance\GDLuminanceSource;
use ZxingSPE\Luminance\IMagickLuminanceSource;
use ZxingSPE\Qrcode\QRCodeReader;

use Imagick;

final class QrReader
{
    const SOURCE_TYPE_FILE     = 'file';
    const SOURCE_TYPE_BLOB     = 'blob';
    const SOURCE_TYPE_RESOURCE = 'resource';

    private $bitmap;
    private $reader;
    private $result;

    public function __construct($imgSource, $sourceType = QrReader::SOURCE_TYPE_FILE)
    {
        if (!in_array($sourceType, [
            self::SOURCE_TYPE_FILE,
            self::SOURCE_TYPE_BLOB,
            self::SOURCE_TYPE_RESOURCE,
        ], true)) {
            throw new \InvalidArgumentException('Invalid image source.');
        }

        $imagick = extension_loaded('imagick');
        $gd = extension_loaded('gd');

        if ($imagick) {
            $im = $this->handleImagick($imgSource, $sourceType);
            $width  = $im->getImageWidth();
            $height = $im->getImageHeight();
            $source = new IMagickLuminanceSource($im, $width, $height);
        } elseif ($gd) {
            $im = $this->handleGD($imgSource, $sourceType);
            $width  = imagesx($im);
            $height = imagesy($im);
            $source = new GDLuminanceSource($im, $width, $height);
        } else {
            throw new \Exception('No image libarie available. MAke sure GD or Imagick is installed');
        }

        $histo        = new HybridBinarizer($source);
        $this->bitmap = new BinaryBitmap($histo);
        $this->reader = new QRCodeReader();
    }

    // public function decode()
    public function decode($hints = null)
    {
        try {
            // $this->result = $this->reader->decode($this->bitmap);
            $this->result = $this->reader->decode($this->bitmap, $hints);
        } catch (NotFoundException $er) {
            // var_dump("A");
            $this->result = false;
        } catch (FormatException $er) {
            // var_dump("AB");
            $this->result = false;
        } catch (ChecksumException $er) {
            // var_dump("ABC");
            $this->result = false;
        }
    }

    public function text($hints = null)
    {
        $this->decode($hints);

        if (method_exists($this->result, 'toString')) {
            return $this->result->toString();
        }

        return $this->result;
    }

    public function getResult()
    {
        return $this->result;
    }


    /**
     * @param $imgSource string. The image source
     * @param $sourceType string. What kind of image
     * @return bool|Imagick
     * @throws \Exception
     */
    private function handleImagick($imgSource, $sourceType)
    {
        $im = new Imagick();
        switch ($sourceType) {
            case QrReader::SOURCE_TYPE_FILE:
                return $im->readImage($imgSource);
                break;

            case QrReader::SOURCE_TYPE_BLOB:
                return $im->readImageBlob($imgSource);
                break;

            case QrReader::SOURCE_TYPE_RESOURCE:
                return $im = $imgSource;
                break;
        }
        throw new \Exception('Imagick is not able to handle the image.');
    }


    /**
     * @param $imgSource string. The image source
     * @param $sourceType string. What kind of image
     * @return resource|string
     * @throws \Exception
     */
    private function handleGD($imgSource, $sourceType)
    {
        switch ($sourceType) {
            case QrReader::SOURCE_TYPE_FILE:
                $image = file_get_contents($imgSource);
                return imagecreatefromstring($image);
                break;

            case QrReader::SOURCE_TYPE_BLOB:
                return imagecreatefromstring($imgSource);
                break;

            case QrReader::SOURCE_TYPE_RESOURCE:
                return $imgSource;
                break;
        }
        throw new \Exception('GD is not able to handle the image.');
    }
}
