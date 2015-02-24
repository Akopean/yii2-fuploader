<?php
namespace jones\fuploader\actions;

use Yii;
use yii\base\Action;
use yii\base\ErrorException;
use yii\web\Response;
use jones\fuploader\UploadForm;

/**
 * Class UploadAction
 * @package jones\fuploader
 */
class UploadAction extends Action
{
    const STATUS_SUCCESS = 200;
    const STATUS_APPLICATION_ERROR = 500;

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
            $form = new UploadForm();
            $form->attributes = $request->post();
            $form->path = $form->path ?: $this->path;
            if (!$form->validate() || !$uploaded = $form->upload()) {
                throw new ErrorException($this->prepareErrors($form->getErrors()));
            }
			$callbackData['request'] = $request->post();
            $callbackData['files'] = $uploaded;
            $response->setStatusCode(self::STATUS_SUCCESS);
            $response->data = [
                'message' => Yii::t('app', '{count, plural, =1{File} other{Files}} has been uploaded',
                    ['count' => sizeof($uploaded)]
                ),
                'url' => $this->url.'/',
                'files' => $uploaded
            ];
			if (is_callable($this->callback)) {
				call_user_func_array($this->callback, [
                    'request' => $request->post(),
                    'files' => $uploaded
                ]);
			}
        } catch (ErrorException $e) {
            $response->setStatusCode(self::STATUS_APPLICATION_ERROR);
            $response->data = ['reason' => $e->getMessage()];
        }
        $response->format = Response::FORMAT_JSON;
        return $response;
    }

    /**
     * Convert array of errors to string
     *
     * @access protected
     * @param array $errors
     * @param string $delimiter
     * @return string
     */
    protected function prepareErrors(array $errors, $delimiter = '<br/>')
    {
        $msg = [];
        foreach ($errors as $error) {
            if (is_array($error)) {
                $msg[] = $this->prepareErrors($error, $delimiter);
            } else {
                $msg[] = $error;
            }
        }
        return implode($delimiter, $msg);
    }
}
 