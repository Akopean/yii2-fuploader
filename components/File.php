<?php

namespace jones\fuploader\components;

use Yii;
use yii\base\ErrorException;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Class File
 * @package jones\fuploader\components
 */
class File
{
    /**
     * Default file extension
     */
    const DEFAULT_EXT = 'doc';
    /**
     * @var array list of available file mime types
     */
    public static $mimeTypes = [
		'image/png', //png
		'image/jpeg', //jpeg
		'application/msword', //doc
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document', //docx
		'application/pdf', //pdf
		'text/plain', //txt
	];
    /**
     * @var array list of file errors
     */
    public static $errors = [
        UPLOAD_ERR_INI_SIZE => 'Exceeded the maximum file size',
        UPLOAD_ERR_FORM_SIZE => 'Exceeded the maximum file size',
        UPLOAD_ERR_PARTIAL => 'File not uploaded',
        UPLOAD_ERR_NO_FILE => 'File not uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Temp folder not exists',
        UPLOAD_ERR_CANT_WRITE => 'Cannot to write file on disk',
        UPLOAD_ERR_EXTENSION => 'File not uploaded'
    ];

    /**
     * Upload file
     *
     * @access public
     * @param $attr
     * @param $name
     * @param $ext
     * @param $path
     * @param array $allowed list of allowed file types
     * @return boolean
     * @throws \yii\base\ErrorException
     */
    public function upload($attr, $name, $ext, $path, array $allowed = [])
    {
        $allowed = array_filter($allowed);
        $file = UploadedFile::getInstanceByName($attr);
        if (!$file) {
            throw new ErrorException(Yii::t('app', 'Select at least one file'));
        }
        if ($file->getHasError()) {
            throw new ErrorException(Yii::t('app', static::$errors[$file->error]));
        }
        if (!in_array($file->type, static::$mimeTypes)) {
            throw new ErrorException(Yii::t('app', 'Invalid file type'));
        }
        if ($allowed && !in_array($file->extension, $allowed)) {
            throw new ErrorException(Yii::t('app', 'Invalid file type'));
        }
        FileHelper::createDirectory($path);
        return $file->saveAs($path.'/'.$name.'.'.$ext);
    }
}
 