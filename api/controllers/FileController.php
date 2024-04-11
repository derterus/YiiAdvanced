<?php
namespace api\controllers;
use Yii;
use yii\web\Response;
use common\models\Files;
use common\models\FileUser;
use Faker\Core\File;
use yii\rest\ActiveController;
use yii\web\UploadedFile;
use common\models\User;

class FileController extends ActiveController
{
    public $modelClass = 'common\models\Files';
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::class,
        ];
    
        return $behaviors;
    }
    public function actionAdd()
    {
        $fileModel = new Files();
    
        $fileInstance = UploadedFile::getInstanceByName('file');
        $filePath = Yii::getAlias('@webroot/uploads/') . $fileInstance->name;
    
        // Проверяем, существует ли уже файл с таким именем
        if (file_exists($filePath)) {
            Yii::$app->response->statusCode = 403;
            return ['success' => false, 'errors' => 'Файл с таким именем уже существует.'];
        }
    
        $fileModel->name = $fileInstance->name; // имя файла
        $fileModel->path = $filePath; // путь к файлу
    
        // Получаем текущего пользователя
        $user = Yii::$app->user->identity;
        $fileModel->created_by = $user->id;
    
        if ($fileInstance->saveAs($filePath) && $fileModel->save()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => $fileModel->getErrors()];
        }
    }
    


}