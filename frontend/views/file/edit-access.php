<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\User;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FileUser */

$currentUserId = Yii::$app->user->id; // Получаем ID текущего пользователя

$form = ActiveForm::begin([
    'id' => 'edit-access-form',
    'action' => ['file/accessform', 'id' => $model->id],
    'enableAjaxValidation' => true,
]);

echo $form->field($model, 'access_level')->radioList([
    'all' => 'Все',
    'registered' => 'Зарегистрированные',
    'individual' => 'Отдельные пользователи',
], [
    'item' => function($index, $label, $name, $checked, $value) {
        $checked = $checked ? 'checked' : '';
        $return = '<label class="modal-radio">';
        $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" ' . $checked . '>';
        $return .= '<i></i>';
        $return .= '<span>' . ucwords($label) . '</span>';
        $return .= '</label>';
        return $return;
    }
])->label(false);

// Поле для выбора пользователей появляется только когда выбраны "Отдельные пользователи"
$script = <<< JS
$('input[type="radio"]').on('change', function() {
    if ($(this).val() === 'individual') {
        $('#user-selection').show();
    } else {
        $('#user-selection').hide();
    }
});
JS;
$this->registerJs($script);

echo '<div id="user-selection" style="display:none;">';
echo $form->field($model, 'user_id')->checkboxList(
    ArrayHelper::map(User::find()->where(['not', ['id' => $currentUserId]])->all(), 'id', 'username') // Исключаем текущего пользователя
);
echo '</div>';

echo Html::submitButton('Save', ['class' => 'btn btn-success']);

ActiveForm::end();

// Кнопка для возврата на предыдущую страницу
echo Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-primary']);
?>
