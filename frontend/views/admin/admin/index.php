<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
?>

<h2><?=Yii::$app->user->identity->username?></h2>

<h4>TODO:</h4>
<ul>
    <li>Редактирование профиля</li>
    <li><?=Html::a('Список блогов', ['admin/blog'])?></li>
</ul>

