<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 11.09.16
 * Time: 13:10
 */

namespace common\components\youtube;

use common\models\VideoStatistic;
use Yii;
use common\models\Video;
use yii\base\Exception;

class Api
{
    private $url;
    private $errors;
    private $video = [];
    private $apiKey;

    public function setUrl($url)
    {
        $this->url = $url;
        $this->apiKey = \Yii::$app->params['youtube_api_key'];
        return $this;
    }

    public function getVideo()
    {
        preg_match('/^(http(s?):\/\/)?(www\.)?\w+?\.(com|ru)(\/?\w+)+/i', $this->url, $match);
        $chanel_id = preg_replace('/\//','',end($match));
        if($chanel_id){
            $url = "https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId={$chanel_id}&maxResults=50&key={$this->apiKey}";
            $nextPageUrl = '';
            do{
                $obj = json_decode(file_get_contents( $nextPageUrl ?: $url ));
                if(isset($obj->error)){
                    throw new Exception($obj->error);
                }
                $this->createVideo($obj);
                if(isset($obj->nextPageToken))
                    $nextPageUrl = $url.'&pageToken='.$obj->nextPageToken;
            } while(isset($obj->nextPageToken));
            return $this->video;
        }
    }

    public function chanelUrlValidate()
    {
        preg_match('/^(http(s?):\/\/)?(www\.)?\w+?\.(com|ru)(\/?\w+)+/i', $this->url, $match);
        $chanel_id = preg_replace('/\//','',end($match));
        if($chanel_id){
            $url = "https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId={$chanel_id}&maxResults=0&key={$this->apiKey}";
            $obj = json_decode(file_get_contents($url));
            if(isset($obj->error)){
                $this->addError('Некоректный урл канала');
            } elseif(isset($obj->pageInfo->totalResults) && $obj->pageInfo->totalResults == 0) {
                $this->addError('Мы не публикуем пустые каналы. Добавьте видео');
            }
        } else {
            $this->addError('Не удалось получить id-канала');
        }
    }


    public function createVideo($obj)
    {
        foreach ($obj->items as $item){
            $video = new Video();
            $video->video_id = isset($item->id->videoId)?$item->id->videoId:'';
            $video->title = isset($item->snippet->title)?$item->snippet->title:'';
            $video->description = isset($item->snippet->description)?$item->snippet->description:'';
            $video->published_at = $item->snippet->publishedAt;
            $this->videoStatistics($video);
            $this->video[] = $video;
        }
        return $this->video;
    }

    /**
     * @param array $video
     */
    public function setVideo($video)
    {
        $this->video = $video;
    }

    public function videoStatistics(Video $video)
    {
//        https://www.googleapis.com/youtube/v3/videos?id='.$vid.'&part=statistics&key='.$API_key.''
        $url = "https://www.googleapis.com/youtube/v3/videos?id={$video->video_id}&part=statistics&key={$this->apiKey}";
        $result = json_decode(file_get_contents($url), true);
        if(!isset($result['error'])){
            $stat = new VideoStatistic();
//            $stat->video_id = $video_id;
            $stat->view = isset($result['items'][0]['statistics']['viewCount'])?$result['items'][0]['statistics']['viewCount']:0;
            $stat->like = isset($result['items'][0]['statistics']['likeCount'])?$result['items'][0]['statistics']['likeCount']:0;
            $stat->dislike = isset($result['items'][0]['statistics']['dislikeCount'])?$result['items'][0]['statistics']['dislikeCount']:0;
            $stat->favorite = isset($result['items'][0]['statistics']['favoriteCount'])?$result['items'][0]['statistics']['favoriteCount']:0;
            $stat->comment = isset($result['items'][0]['statistics']['commentCount'])?$result['items'][0]['statistics']['commentCount']:0;
            $video->statistic = $stat;
        }
    }

    public function addError($msg)
    {
        $this->errors[] = trim($msg);
    }
}