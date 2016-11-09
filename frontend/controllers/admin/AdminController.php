<?php

namespace frontend\controllers\admin;

class AdminController extends \yii\web\Controller
{
    /**
     * TODO: запрет на доступ не авторизованым пользователям
     * TODO: Актуальные хлебные крошки
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

}
