<?php
use yii\bootstrap5\Modal;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $files array */

$this->title = 'Мои файлы';
?>

<div class="site-show-files">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => new \yii\data\ArrayDataProvider([
            'allModels' => $files,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]),
        'columns' => [
            'name',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('Edit access', ['file/editaccess', 'id' => $model['id']], [
                            'class' => 'btn btn-warning',
                        
                            'data-method' => 'post',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('Delete', ['file/delete', 'id' => $model['id']], [
                            'class' => 'btn btn-danger',
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

    <?php
    Modal::begin([
        'id' => 'modal',
        'title' => '<h2>Edit Access</h2>',
        'options' => ['class' => 'modal-lg'], // Здесь вы можете установить класс для изменения размера модального окна
    ]);

    echo "<div id='modalContent'></div>";

    Modal::end();
    ?>

</div>

<?php
$this->registerJs("
$(function(){
    $('#modalButton').click(function (){
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });
});
");
?>
