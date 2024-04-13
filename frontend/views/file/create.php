<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\FileForm $model */

use Psy\VersionUpdater\Downloader\FileDownloader;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Загрузка файла';
$this->params['breadcrumbs'][] = $this->title;
$model = new \frontend\models\FileForm();
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-lg-5">
        <?php $form = ActiveForm::begin(['id' => 'file-form', 'action' => ['create']]); ?>

                <?= $form->field($model, 'file')->fileInput(['autofocus' => true])?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>