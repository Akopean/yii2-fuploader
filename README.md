File Uploader
=============
> Not ready for production yet.

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

License
----

MIT
