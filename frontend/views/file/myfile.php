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
                            'id' => 'modalButton', // Добавьте эту строку
                            'value' => Url::to(['file/editaccess', 'id' => $model['id']]), // И эту строку
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(event) {
        if (event.target.id === 'modalButton') {
            event.preventDefault();
            var modal = document.getElementById('modal');
            var modalContent = modal.querySelector('#modalContent');
            var xhr = new XMLHttpRequest();
            xhr.open('GET', event.target.getAttribute('value'), true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    modalContent.innerHTML = xhr.responseText;
                    modal.style.display = 'block';
                }
            };
            xhr.send();
        }
    });
});

</script>
    

