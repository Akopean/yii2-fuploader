<?php
namespace jones\fuploader;

use yii\web\AssetBundle;

/**
 * Class FileUploadAsset
 * @package jones\fuploader
 */
class FileUploadAsset extends AssetBundle
{
    public $sourcePath = '@bower';
    public $js = [
        'jquery.ui/ui/widget.js',
        'jquery-file-upload/js/query.fileupload.js',
    ];

    public $css = [
        'jquery-file-upload/css/jquery.fileupload.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
 