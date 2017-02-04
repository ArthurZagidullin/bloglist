<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "video_statistic".
 *
 * @property integer $id
 * @property integer $video_id
 * @property integer $view
 * @property integer $like
 * @property integer $dislike
 * @property integer $favorite
 * @property integer $comment
 * @property string $datetime
 *
 * @property Video $video
 */
class VideoStatistic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'video_statistic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['video_id', 'datetime'], 'required'],
            [['video_id', 'view', 'like', 'dislike', 'favorite', 'comment'], 'integer'],
            [['datetime'], 'safe'],
            [['video_id'], 'exist', 'skipOnError' => true, 'targetClass' => Video::className(), 'targetAttribute' => ['video_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'video_id' => 'Video ID',
            'view' => 'View',
            'like' => 'Like',
            'dislike' => 'Dislike',
            'favorite' => 'Favorite',
            'comment' => 'Comment',
            'datetime' => 'Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideo()
    {
        return $this->hasOne(Video::className(), ['id' => 'video_id']);
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'datetime',
                ],
                'value' => function() { return date('Y-m-d H:m:i'); },
            ],
        ];
    }
}
