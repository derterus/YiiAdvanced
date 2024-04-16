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
use common\models\Files;
use common\models\FileUser;
use frontend\models\FileUserForm;
use yii\web\UploadedFile;
use common\models\FileAccess;


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
                'only' => ['create','showmyfiles','delete'],
                'rules' => [
                    [
                        'actions' => ['create','showmyfiles','delete'],
                        'allow' => true,
                        'roles' => ['@'],
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
    public function actionIndex()
    {
        $client = new \yii\httpclient\Client();
        $request = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://webapiyii:8080/file/show');
        $response = $request->send();
        if ($response->getStatusCode() == 200) { // HTTP OK
            $files = json_decode($response->getContent(), true);
            return $this->render('index', ['files' => $files]);
        } else {
            throw new \yii\web\ServerErrorHttpException('API request failed.');
        }
    }
    public function actionEditaccess($id)
    {
    Yii::$app->session->setFlash('warning', "дадада");
    $model = FileUser::findOne($id);


    return $this->render('edit-access', [
        'model' => $model,
    ]);
    }
    
    public function actionAccessform($id) 
    {
        $model = FileUser::findOne($id);
        $postData = Yii::$app->request->post();
        $accessLevels = [
            'all' => 0,
            'registered' => 1,
            'individual' => 2,
        ];
    
        // Загружаем уровень доступа напрямую из данных формы
        $accessLevel = $postData['FileUser']['access_level'];
    
        // Устанавливаем уровень доступа в зависимости от выбранного значения
        $model->access_level = $accessLevels[$accessLevel];
    
        // Сохраняем модель FileUser
        if ($model->save()) {
            // Если выбраны отдельные пользователи, обновляем записи в таблице FileAccess
            if ($model->access_level == 2) {
                // Удаляем все текущие записи для этого файла
                FileAccess::deleteAll(['file_id' => $model->file_id]);
                // Добавляем новые записи для каждого выбранного пользователя
                $selectedUsers = $postData['FileUser']['user_id'];
                if ($selectedUsers != null) {
                    foreach ($selectedUsers as $userId) {
                        $fileAccess = new FileAccess();
                        $fileAccess->file_id = $model->file_id;
                        $fileAccess->user_id = $userId;
                        $fileAccess->save();
                    }
                   
                    $fileAccess = new FileAccess();
                    $fileAccess->file_id = $model->file_id;
                    $fileAccess->user_id = Yii::$app->user->id; // ID текущего пользователя
                    $fileAccess->save();
                }
                else{
                    // Если не выбраны отдельные пользователи, создаем запись доступа для текущего пользователя
                    $fileAccess = new FileAccess();
                    $fileAccess->file_id = $model->file_id;
                    $fileAccess->user_id = Yii::$app->user->id; // ID текущего пользователя
                    $fileAccess->save();
                }
            }
        }
    
        return $this->render('edit-access', [
            'model' => $model,
        ]);
    }
    


    
    public function actionCreate()
    {
        $model = new \frontend\models\FileForm();
    
        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'file');
            if ($file !== null) {
                $client = new \yii\httpclient\Client();
                $request = $client->createRequest()
                    ->setMethod('POST')
                    ->setUrl('http://webapiyii:8080/file/add')
                    ->addFile('file', $file->tempName, ['fileName' => $file->name]); // Используем оригинальное имя файла
    
                // Добавляем токен в заголовки запроса
                $request->addHeaders(['Authorization' => 'Bearer ' . Yii::$app->session->get('user-token')]);
    
                $response = $request->send();
                if ($response->isOk) {
                    // обработка успешного ответа

                    Yii::$app->session->setFlash('success', "Файл успешно загружен.");
                    return $this->refresh();
                }
                else if($response->statusCode==403){
                    Yii::$app->session->setFlash('warning', "Файл уже загружен.");
                    
                }
                 else {
                    Yii::$app->session->setFlash('error', "Ошибка загрузки.");
                }
            } else {
                Yii::$app->session->setFlash('warning', "Нет файла для загрузки.");
            }
        }
    
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    public function actionDownload($id)
    {
    $file = Files::findOne($id);
    if ($file) {
        // Получаем текущего пользователя
        $user = Yii::$app->user->identity;

        // Находим запись в таблице FileUser для данного файла
        $fileUser = FileUser::find()->where(['file_id' => $id])->one();

        // Проверяем, есть ли запись в таблице FileUser и соответствует ли уровень доступа требованиям
        if ($fileUser) {
            switch ($fileUser->access_level) {
                case 0: // Файл доступен для всех пользователей
                    return \Yii::$app->response->sendFile($file->path, $file->name);
                case 1: // Файл доступен только для авторизованных пользователей
                    if (!Yii::$app->user->isGuest) {
                        return \Yii::$app->response->sendFile($file->path, $file->name);
                    } else {
                        throw new \yii\web\ForbiddenHttpException('Требуется авторизация.');
                    }
                case 2: // Файл доступен только для определенных пользователей
                    $access = FileAccess::find()->where(['file_id' => $file->id, 'user_id' => $user->id])->one();
                    if ($access !== null) {
                        return \Yii::$app->response->sendFile($file->path, $file->name);
                    } else {
                        throw new \yii\web\ForbiddenHttpException('У вас нет доступа к этому файлу.');
                    }
                default:
                    throw new \yii\web\ForbiddenHttpException('У вас нет доступа к этому файлу.');
            }
        } else {
            throw new \yii\web\ForbiddenHttpException('У вас нет доступа к этому файлу.');
        }
    }

    throw new \yii\web\NotFoundHttpException('The requested file does not exist.');
    }

    public function actionShowmyfiles()
    {
        $client = new \yii\httpclient\Client();
        $request = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://webapiyii:8080/file/show/my');
        // Добавляем токен в заголовки запроса
        $request->addHeaders(['Authorization' => 'Bearer ' . Yii::$app->session->get('user-token')]);
        $response = $request->send();
        if ($response->getStatusCode() == 200) { // HTTP OK
            $files = json_decode($response->getContent(), true);
            return $this->render('myfile', ['files' => $files]);
        } else {
            throw new \yii\web\ServerErrorHttpException('API request failed.');
        }
    }


    public function actionDelete($id)
    {
        $client = new \yii\httpclient\Client();
        $request = $client->createRequest()
            ->setMethod('DELETE')
            ->setUrl('http://webapiyii:8080/file/delete/' . $id); // Добавляем id в URL
        // Добавляем токен в заголовки запроса
        $request->addHeaders(['Authorization' => 'Bearer ' . Yii::$app->session->get('user-token')]);
        $response = $request->send();
        if ($response->getStatusCode() == 200) { // HTTP OK
            Yii::$app->session->setFlash('success','Файл успешно удален');
            return $this->actionShowmyfiles(); // Вызываем метод actionShowmyfiles
        } else {
            throw new \yii\web\ServerErrorHttpException('API request failed.');
        }
    }
    

  
} 