<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\widgets\Alert;
use frontend\models\FileForm;
use frontend\models\FileUserForm;
use yii\web\UploadedFile;


/**
 * Site controller
 */
class FileController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout',],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new \frontend\models\FileForm();
    
        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'file');
            if ($file) {
                // Используйте временный путь к файлу
                $filePath = $file->tempName;
    
                $client = new \yii\httpclient\Client();
                $request = $client->createRequest()
                    ->setMethod('POST')
                    ->setUrl('http://webapiyii:8080/file/add')
                    ->addFile('file', $filePath);
                
                // Добавляем токен в заголовки запроса
                $request->addHeaders(['Authorization' => 'Bearer zt91FoBtNj3ZjLtca-8a-oMhXYJMQkou_1712548354']);
                
                $response = $request->send();
                if ($response->isOk) {
                    // обработка успешного ответа
                    Yii::info("File uploaded successfully.");
                } else {
                    Yii::error("Failed to upload file. Response: " . print_r($response->data, true), 'file_upload');
                }
            } else {
                Yii::warning("No file was uploaded.");
            }
        } else {
            Yii::warning("Failed to load model from POST data.");
        }
    
        return $this->render('//site/file', [
            'model' => $model,
        ]);
    }
    
    
}