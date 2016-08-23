<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "blog".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property integer $owner_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $owner
 */
class Blog extends \yii\db\ActiveRecord
{
    const URL_VALIDATE = 'urlValidate';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'url', 'owner_id'], 'required'],
            [['owner_id',], 'integer'],
//            [[ 'created_at', 'updated_at'],'timestamp'],
            [['name', 'url'], 'string', 'max' => 255],
            [['name','url'], 'unique'],
            ['url', 'match', 'pattern' => '/^(http(s?):\/\/)?(www\.)?\w+?\.(com|ru)(\/?\w+)+/i'],
            ['url', 'chanelUrlValidate'],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['owner_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'url' => 'URL',
            'owner_id' => 'Owner ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getChanelID()
    {
        preg_match('/^(http(s?):\/\/)?(www\.)?\w+?\.(com|ru)(\/?\w+)+/i',$this->url, $match);
        return preg_replace('/\//','',end($match));
    }

    public function chanelUrlValidate($attribute)
    {
        $api = \Yii::$app->params['youtube_api_key'];
        preg_match('/^(http(s?):\/\/)?(www\.)?\w+?\.(com|ru)(\/?\w+)+/i', $this->$attribute, $match);
        $chanel_id = preg_replace('/\//','',end($match));
        if($chanel_id){
            $url = "https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId={$chanel_id}&maxResults=0&key={$api}";
            $obj = json_decode(file_get_contents($url));
            if(isset($obj->error)){
                $this->addError($attribute, 'Некоректный урл канала');
            } elseif(isset($obj->pageInfo->totalResults) && $obj->pageInfo->totalResults == 0) {
                $this->addError($attribute, 'Мы не публикуем пустые каналы. Добавьте видео');
            }
        } else {
            $this->addError($attribute, 'Не удалось получить id-канала');
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::URL_VALIDATE] = ['url',];
        return $scenarios;
    }

    public function createVideous()
    {
        $api = \Yii::$app->params['youtube_api_key'];
        preg_match('/^(http(s?):\/\/)?(www\.)?\w+?\.(com|ru)(\/?\w+)+/i', $this->url, $match);
        $chanel_id = preg_replace('/\//','',end($match));
        if($chanel_id){
            $url = "https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId={$chanel_id}&maxResults=50&key={$api}";
            $obj = json_decode(file_get_contents($url));
            if(isset($obj->error)){
                return false;
            }
            foreach ($obj->items as $item){
                $video = new Video();
                $video->blog_id = $this->id;
                $video->video_id = isset($item->id->videoId)?$item->id->videoId:'';
                $video->title = isset($item->snippet->title)?$item->snippet->title:'';
                $video->description = isset($item->snippet->description)?$item->snippet->description:'';
                $video->published_at = $item->snippet->publishedAt;
                if($video->validate()){
                    $video->save();
                } else {
                    var_dump($video->getErrors());
                }
            }
        }
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function() { return date('Y-m-d H:m:i'); },
            ],
        ];
    }
}
