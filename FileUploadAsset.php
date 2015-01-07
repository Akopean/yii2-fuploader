<?php
namespace jones\fuploader;

use yii\web\AssetBundle;

/**
 * Class FileUploadAsset
 * @package jones\fuploader
 */
class FileUploadAsset extends AssetBundle
{
    public $js = [
        '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js',
        '//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload.min.js',
    ];

    public $css = [
        '//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/css/jquery.fileupload.min.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
 