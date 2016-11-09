<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Blog */
/* @var $form yii\widgets\ActiveForm */

$category = \common\models\Category::find0()->all();
$category_items = \yii\helpers\ArrayHelper::map($category,'id','name');

$selected_categories = $model->getCategories()->all();
foreach ($selected_categories as $selected_category){
    $model->categories_id[] = $selected_category->id;
}

if($model->getErrors()): ?>
    <?php foreach ($model->getErrors() as $field => $err):?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong><?=$field?>:</strong> <?=$err[0]?>
        </div>
    <?php endforeach; ?>
<?php endif;?>

<div class="blog-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url', ['enableAjaxValidation' => false])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'categories_id', ['enableAjaxValidation' => false, ])
            ->dropDownList($category_items, [  'multiple'=>'multiple', 'size'=>10, ])
            ->label('Категория');
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
