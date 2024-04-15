<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\User;
use common\models\Files;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FileUserForm */

$form = ActiveForm::begin([
    'id' => 'edit-access-form',
    'action' => ['file/edit-access', 'id' => $model->id],
    'enableAjaxValidation' => true,
]);

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

echo Html::submitButton('Save', ['class' => 'btn btn-success']);

ActiveForm::end();
?>
