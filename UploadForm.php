<?php
namespace jones\fuploader;

use Yii;
use yii\base\Model;
use jones\fuploader\components\File;

/**
 * Class UploadForm
 * @package jones\fuploader
 */
class UploadForm extends Model
{
    public $path;
    public $ext;
    public $attribute;
    public $fileName;
    public $allowedFileTypes;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['path', 'attribute', 'ext'], 'required'],
            [['fileName', 'allowedFileTypes'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'path' => Yii::t('app', 'Path'),
            'ext' => Yii::t('app', 'Extension'),
            'attribute' => Yii::t('app', 'Attribute')
        ];
    }

    /**
     * Upload files
     *
     * @access public
     * @return boolean
     * @throws \yii\base\ErrorException
     */
    public function upload()
    {
        $this->allowedFileTypes = explode(',', $this->allowedFileTypes);
        $file = new File();
        $this->fileName = $this->fileName ?: Yii::$app->getSecurity()->generateRandomString();
        $uploaded = $file->upload($this->attribute, $this->fileName, $this->ext, $this->path, $this->allowedFileTypes);
        if ($file->hasErrors()) {
            $this->addErrors($file->getErrors());
        }
        return $uploaded;
    }
}
 