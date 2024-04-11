<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
{
    if ($this->validate()) {
       $client = new \yii\httpclient\Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('http://webapiyii:8080/users/login')
            ->setData([
                'username' => $this->username,
                'password' => $this->password,
            ])
            ->send();
             // Извлекаем токен из ответа
             $token = $response->data['data']['token'];
    
             // Сохраняем токен в сессии или куки, чтобы использовать его позже
             Yii::$app->session->set('user-token', $token);
              return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
    }
    return false;
       
    
}


    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
{
    if ($this->_user === null) {
        $this->_user = User::findByUsername($this->username);

        // Добавляем отладочные сообщения
        if ($this->_user === null) {
            Yii::warning("Пользователь с именем {$this->username} не найден.");
        } else {
            Yii::info("Пользователь с именем {$this->username} успешно найден.");
        }
    }

    return $this->_user;
}

}
