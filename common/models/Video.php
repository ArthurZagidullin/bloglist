<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "video".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $video_id
 * @property integer $blog_id
 * @property string $published_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Blog $blog
 */
class Video extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'video';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'video_id', 'blog_id'], 'required'],
            [['description'], 'string'],
            [['blog_id'], 'integer'],
            [['published_at', 'created_at', 'updated_at'], 'safe'],
            [['title', 'video_id'], 'string', 'max' => 255],
            [['title'], 'unique'],
            [['video_id'], 'unique'],
            [['blog_id'], 'exist', 'skipOnError' => true, 'targetClass' => Blog::className(), 'targetAttribute' => ['blog_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'video_id' => 'Video ID',
            'blog_id' => 'Blog ID',
            'published_at' => 'Published At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlog()
    {
        return $this->hasOne(Blog::className(), ['id' => 'blog_id']);
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
