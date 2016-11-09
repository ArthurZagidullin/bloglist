<?php

namespace frontend\controllers;

use common\models\Blog;
use common\models\BlogCategoryRelation;
use common\models\Category;

class BlogController extends \yii\web\Controller
{
    public function actionCategory($id)
    {
        if(\Yii::$app->request->isPjax){
//            $blogs = Blog::find()->select('blog.*')
//                                 ->leftJoin(BlogCategoryRelation::tableName(), BlogCategoryRelation::tableName().'.category_id = '.$id);
            if($category = Category::findOne($id)){
                return $this->render('@frontend/views/site/index',['blogs' => $category->getBlogs()]);
            }
        }
        return $this->render('category',['id' => $id]);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUser()
    {
        return $this->render('user');
    }

}
