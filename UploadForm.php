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
    public $name;

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
     * @return boolean
     * @throws \yii\base\ErrorException
     */
    public function upload()
    {
        $this->allowedFileTypes = explode(',', $this->allowedFileTypes);
        $file = new File();
        $this->fileName = $this->fileName ?: Yii::$app->security->generateRandomString();
        $this->name = $this->fileName.'.'.$this->ext;
        return $file->upload($this->attribute, $this->fileName, $this->ext, $this->path, $this->allowedFileTypes);
    }
}
 