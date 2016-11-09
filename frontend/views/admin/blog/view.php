<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Blog */

$this->title = $model->name;
$this->params['breadcrumbs'][] = [ 'label' => 'Панель администратора', 'url' => '/admin'];
$this->params['breadcrumbs'][] = ['label' => 'Мои блоги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="blog-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'url:url',
//            'owner_id',
            'created_at',
            'updated_at',
            [
                'label' => 'Категория',
                'value' => call_user_func(function($data){
                    $result = '<table>';
                    $categories = $data->getCategories()->all();
                    foreach ($categories as $category){
                        $result .= "<tr><th>{$category->name}</th></tr>";
                    }
                    $result .= '</table>';
                    return $result;
                },$model),
                'format' => 'html',
            ],
        ],
    ]) ?>

</div>
