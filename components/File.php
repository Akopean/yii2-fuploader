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
    /** @var array list of upload errors */
    protected $uploadErrors = [];

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
        $this->uploadErrors = [];
        $allowed = array_filter($allowed);
		$attr = str_replace('[]', '', $attr);
        $files = UploadedFile::getInstancesByName($attr);
        $uploaded = [];
        if (!$files) {
            $this->uploadErrors[] = Yii::t('app', 'Select at least one file');
            return false;
        }
        $filesCount = sizeof($files);
        foreach ($files as $file) {
            if ($filesCount > 1) {
                $name = Yii::$app->getSecurity()->generateRandomString();
            }
            if ($file->getHasError()) {
                $this->uploadErrors[] = Yii::t('app', static::$errors[$file->error]);
                continue;
            }
            if (!in_array($file->type, static::$mimeTypes)) {
                $this->uploadErrors[] = Yii::t('app', '{file} has invalid file type', ['file' => $file->baseName]);
                continue;
            }
            if ($allowed && !in_array($file->extension, $allowed)) {
                $this->uploadErrors[] = Yii::t('app', '{file} has invalid file type', ['file' => $file->baseName]);
                continue;
            }
            FileHelper::createDirectory($path);
            if ($file->saveAs($path.'/'.$name.'.'.$ext)) {
                $uploaded[] = [
                    'path' => $path,
                    'name' => $name,
                    'ext' => $ext
                ];
            }
        }
        return $uploaded;
    }

    /**
     * Get list of uploading errors
     *
     * @access public
     * @return array
     */
    public function getErrors()
    {
        return $this->uploadErrors;
    }

    /**
     * Check if exists upload errors
     *
     * @access public
     * @return boolean
     */
    public function hasErrors()
    {
        return !empty($this->uploadErrors);
    }
}
