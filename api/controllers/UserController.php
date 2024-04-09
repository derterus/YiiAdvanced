<?php
namespace api\controllers;
use Yii;
use yii\web\Response;
use yii\rest\ActiveController;
use common\models\User;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';
    public function actionRegister()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $post = Yii::$app->request->post();

        if (!empty($post['username']) && !empty($post['email']) && !empty($post['password'])) {
            $user = new User();
            $user->username = $post['username'];
            $user->email = $post['email'];
            $user->setPassword($post['password']);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();

            if ($user->save()) {
                return [
                    'status'=>'success',
                    'id'=> $user->id
                ];
            } else {
                return ['status' => 'error', 'data' => $user->errors];
            }
        } else {
            return ['status' => 'error', 'data' => 'Invalid parameters.'];
        }
    }
    public function actionLogin()
{
    Yii::$app->response->format = Response::FORMAT_JSON;

    $post = Yii::$app->request->post();

    if (!empty($post['username']) && !empty($post['password'])) {
        $user = User::findByUsername($post['username']);

        if ($user && $user->validatePassword($post['password'])) {
            return ['user'=>$user];
        } else {
            return ['status' => 'error', 'data' => 'Invalid username or password.'];
        }
    } else {
        return ['status' => 'error', 'data' => 'Invalid parameters.'];
    }
}

}