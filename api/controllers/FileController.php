<?php
namespace api\controllers;
use Yii;
use yii\web\Response;
use common\models\Files;
use common\models\FileUser;
use Faker\Core\File;
use yii\rest\ActiveController;
use yii\web\UploadedFile;

class FileController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::class,
        ];
        return $behaviors;
    }
    public $modelClass = 'common\models\Files';
    public function actionAdd()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
    
        if (Yii::$app->request->isPost) {
            $fileModel = new Files();
    
            $fileInstance = UploadedFile::getInstanceByName('file');
    
            if ($fileInstance) {
                $filePath = Yii::getAlias('@webroot/uploads/') . $fileInstance->name;
                if ($fileInstance->saveAs($filePath)) {
                    $fileModel->name = $fileInstance->name; // имя файла
                    $fileModel->path = $filePath; // путь к файлу
                    if (!Yii::$app->user->isGuest) {
                        $fileModel->created_by = Yii::$app->user->identity->id; // ID создателя
                        if ($fileModel->save()) {
                            return ['success' => true];
                        } else {
                            return ['success' => false, 'errors' => $fileModel->getErrors()];
                        }
                    } else {
                        return ['success' => false, 'message' => 'Пользователь не аутентифицирован.'];
                    }
                } else {
                    return ['success' => false, 'message' => 'Не удалось сохранить файл.'];
                }
            } else {
                return ['success' => false, 'message' => 'Файл не был загружен.'];
            }
        }
    
        return ['success' => false, 'message' => 'Запрос не является POST запросом.'];
    }
    
    
    
    
}