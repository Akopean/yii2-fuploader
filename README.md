File Uploader
=============

File uploader. Yii2 extension based on [jQuery File Upload Plugin](https://github.com/blueimp/jQuery-File-Upload).

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist joni-jones/yii2-fuploader "*"
```

or add

```
"joni-jones/yii2-fuploader": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \jones\fuploader\FileUpload::widget();?>
```

1. Specify `action` property for uploading url:

    ```php
    FileUpload::widget([
        'action' => Url::to(['some_action'])
    ]);
    ```
    
2. Specify any jquery file upload options:

    ```php
    FileUpload::widget([
        'plugin' => [
            'formData' => 'some data',
        ]
    ]);
    ```
	
3. To setup plugin events or callbacks - use `clientEvents` option for widget:

	```php
	FileUpload::widget([
		'clientEvents' => [
			'done' => 'function(e, data){console.log(data);}',
			'fail' => 'function(e, data){console.log(data);}'
		]
	]);
	```
4. Also you can use extension actions to store some file details after uploading. For example, update user avatar attribute in database.

	```php
	public function actions()
	{
		return[
			'some_action' => [
				'class' => 'jones\fuploader\actions\UploadAction',
				'path' => 'some path for uploading',
				'url' => 'some url for uploaded file', //this url will be accessable in action response
				'callback' => [$this, 'someCallback'] //any callable function
			]
		];
	}
	```
5. If callback was specified it will be triggered after uploading:
	
	```php
	public function someCallback($request, $files)
	{
	    // some code
	}
	```
`UploadAction()` return response in json format. This is structure of response:
	
	```json
	{"message": "some success message", "files": [{"path": "", "name": "", "ext": ""}], "url": "url to files directory"}
	```
	
	```json
	{"reason": "message with reason why file does not uploaded"}
	```
Also, status codes of response will be returned in headers.

License
----

MIT
