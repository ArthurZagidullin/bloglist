<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мои блоги';

$this->params['breadcrumbs'][] = [ 'label' => 'Панель администратора', 'url' => '/admin'];
$this->params['breadcrumbs'][] = 'Мои блоги';

?>
<div class="blog-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить блог', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'label' => 'Категория',
                'value' => function($data){
                    $result = '<table>';
                    $categories = $data->getCategories()->all();
                    foreach ($categories as $category){
                        $result .= "<tr><td>{$category->name}</td></tr>";
                    }
                    $result .= '</table>';
                    return $result;
                },
                'format' => 'html',
            ],
            'url:url',
            'owner.username',
            'created_at',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
