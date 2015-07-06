<?php
namespace jones\fuploader;

use Yii;
use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class FileUpload
 *
 * @package jones\fuploader
 */
class FileUpload extends Widget
{
    /**
     * @var string label for upload button
     */
    public $content = 'Upload';
    /**
     * @var string url to upload action
     */
    public $action = '';
    /**
     * @var boolean allow to load multiple files
     */
    public $multiple = false;
    /**
     * @var string name of file input
     */
    public $attribute = 'file';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        FileUploadAsset::register($this->getView());
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $options = [
            'data-url' => $this->action,
            'id' => $this->options['id']
        ];
        //allow to load multiple files
        if ($this->multiple) {
            $options['multiple'] = '';
            $this->attribute .= '[]';
        }
        $btnOptions = $this->options;
        unset($btnOptions['id'], $btnOptions['plugin']);
        Html::addCssClass($btnOptions, 'btn fileinput-button');
        $this->registerPlugin('file-upload');
        echo Html::beginTag('span', $btnOptions)."\n";
        echo $this->content."\n";
        echo Html::fileInput($this->attribute, '', $options)."\n";
        echo Html::endTag('span')."\n";
    }

    /**
     * Registers a js options for file upload plugin
     *
     * @access public
     * @param string $name
     */
    protected function registerPlugin($name)
    {
        $options = [
            'dataType' => 'json',
            'formData' => [
                'ext' => 'png',
                'attribute' => $this->attribute,
                'fileName' => '',
            ],
            'singleFileUploads' => false
        ];
        $request = Yii::$app->getRequest();
        if ($request->enableCsrfValidation) {
            $options['formData'][$request->csrfParam] = $request->getCsrfToken();
        }
        $options = Json::encode(ArrayHelper::merge(
            $options,
            !empty($this->options['plugin']) ? $this->options['plugin'] : [])
        );
        $id = $this->options['id'];
        $js = "jQuery('#$id').fileupload($options);";
        $this->getView()->registerJs($js);
        if (!empty($this->clientEvents) && is_array($this->clientEvents)) {
            $js = [];
            foreach ($this->clientEvents as $name => $callback) {
                $js[] = "jQuery('#$id').on('$name', $callback);";
            }
            $this->getView()->registerJs(implode("\n", $js));
        }
    }
}
