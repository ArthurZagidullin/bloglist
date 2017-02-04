<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 06.09.16
 * Time: 20:20
 */

namespace common\components;

use Yii;
use yii\base\Component;

class ApiFactory extends Component
{
    /**
     * В зависимости от передаваемого урла возвращает класс для работы с апи
     * @param $url
     */
    public function get($url)
    {
        if(preg_match('/youtube\.com/', $url)){
            return (new youtube\Api())->setUrl($url);
        }
    }




}