<?php

use yii\widgets\Pjax;
use yii\bootstrap\ButtonDropdown;

/* @var $this yii\web\View */

$this->title = 'BlogsList';

$categories = \common\models\Category::find()->all();
$category_items = [];

//Blog->category
foreach ($categories as $category){
    $category_items[] = [
        'label' => $category->name,
        'url' => "/blog/category/{$category->id}",
    ];
}
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Blogslist</h1>
        <p class="lead">Добро пожаловать в агрегатор блогов!</p>
        <?php if(\Yii::$app->user->isGuest): ?>
            <p><?=\yii\bootstrap\Html::a('Регистрация','@web/site/signup', ['class'=>'btn btn-lg btn-success'])?></p>
        <?php endif; ?>
    </div>

    <div class="body-content">
        <div class="">
            <?php Pjax::begin(['timeout' => 5000 ]); ?>
            <?= ButtonDropdown::widget([
                'label' => 'Категория',
                'options' => [
                    'class' => 'btn-lg btn-link',
                    'style' => 'margin:5px'
                ],
                'dropdown' => [
                    'items' => $category_items,
                ],
            ]);?>

            <div class="row">
            <?php foreach ($blogs->all() as $blog): ?>
                <?php if(!empty($blog)): ?>

                        <div class="col-md-3 col-sm-6 hero-feature">
                            <div class="thumbnail">
<!--                                TODO: Убрать стили элемента-->
                                <iframe id="video" style="margin-left: 15px;" width="223" height="142" src="//www.youtube.com/embed/<?=$blog->getVideo()->video_id?>?rel=0" frameborder="0" allowfullscreen></iframe>
                                <div class="caption">
                                    <h3><?=$blog->name?></h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                                    <p><a href="#" class="btn btn-default">More Info</a></p>
                                </div>
                            </div>
                        </div>
                <?php endif; ?>
            <?php endforeach; ?>
            </div>

            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
