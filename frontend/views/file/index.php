<?php
/** @var $files \frontend\models\Files[] */
use yii\helpers\Html;

$this->title = 'ПОКАЗ ФАЙЛОВ';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-show-files">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-lg-5">
            <?php foreach ($files as $file): ?>
                <p>
                    <?= Html::a($file['name'], ['download', 'id' => $file['id']]) ?>
                </p>
            <?php endforeach; ?>
        </div>
    </div>
</div>

