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

    public $category;

    private $videos;
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
            [['name', 'url'], 'string', 'max' => 255],
            [['name','url'], 'unique'],
            ['url', 'match', 'pattern' => '/^(http(s?):\/\/)?(www\.)?\w+?\.(com|ru)(\/?\w+)+/i'],
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

    public function setVideo($video)
    {
        foreach ($video as $v) {
            if($v instanceof Video){
                $v->blog_id = $this->id;
                $v->save();
            }
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


    /**
     * Добавляет видео которое будет привязано к текущему блогу, при сохранении
     * @param Video $video
     * @return $this
     */
    public function addVideo(Video $video)
    {
        $this->videos[] = $video;
        return $this;
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
