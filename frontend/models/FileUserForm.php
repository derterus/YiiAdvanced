<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\User;
use common\models\Files;

/* @var $model app\models\FileA */

$form = ActiveForm::begin();

echo $form->field($model, 'file_id')->dropDownList(
    ArrayHelper::map(Files::find()->all(), 'id', 'name')
);

echo $form->field($model, 'user_id')->checkboxList(
    ArrayHelper::map(User::find()->all(), 'id', 'username')
);

echo $form->field($model, 'access_level')->radioList([
    'all' => 'Все',
    'registered' => 'Зарегистрированные',
    'individual' => 'Отдельные пользователи',
]);

ActiveForm::end();
