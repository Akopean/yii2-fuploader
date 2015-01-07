<?php
namespace jones\fuploader\actions;

use Yii;
use yii\base\Action;
use yii\base\ErrorException;
use yii\web\Response;
use jones\fuploader\components\File;

/**
 * Class UploadAction
 * @package jones\fuploader
 */
class UploadAction extends Action
{

	/** @var string $path path to file uploading **/
    public $path = '';
	/** @var string $url url for uploaded file **/
	public $url = '';
	/**
	 * @var string $callback name of controller method,
	 * this method will be called after uploading
	 **/
	public $callback = '';

    /**
     * @inheritdoc
     */
    public function run()
    {
        $response = Yii::$app->response;
        $request = Yii::$app->request;
        try {
            $path = $request->post('path', $this->path);
            if (!$path) {
                throw new ErrorException(Yii::t('app', 'File upload path should not be empty'));
            }
            $ext = $request->post('ext');
            if (!$ext) {
                throw new ErrorException(Yii::t('app', 'File extension is required parameter'));
            }
            $attr = $request->post('attribute');
            if (!$attr) {
                throw new ErrorException(Yii::t('app', 'File input name is required parameter'));
            }
            $file = new File();
			$fName = $request->post('fileName', '') ?: Yii::$app->getSecurity()->generateRandomString();
            if (!$file->upload($attr, $fName, $ext, $path)) {
                throw new ErrorException(Yii::t('app', 'File not uploaded'));
            }
            $name = $fName.'.'.$ext;
            $response->setStatusCode(200);
            $response->data = [
                'message' => Yii::t('app', 'File has been uploaded successfully'),
                'name' => $name,
				'url' => $this->url.'/'.$name
            ];
			if (is_callable($this->callback)) {
				call_user_func_array($this->callback, [
					'request' => $request->post(),
					'file_name' => $fName,
					'file_path' => $path,
				]);
			}
        } catch (ErrorException $e) {
            $response->setStatusCode(500);
            $response->data = ['reason' => $e->getMessage()];
        }
        $response->format = Response::FORMAT_JSON;
        return $response;
    }
}
 