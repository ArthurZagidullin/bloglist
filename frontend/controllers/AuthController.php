<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.08.16
 * Time: 22:46
 */

namespace frontend\controllers;


use yii\base\Controller;

class AuthController extends Controller
{
    public function actionAuth()
    {
        $this->render('auth');
    }
}