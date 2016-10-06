<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2016
 * Time: 12:48
 */

namespace common\models;
use yii\base\Model,
    yii\helpers\Json;
use yii\base\InvalidParamException;

class OpenRTB extends Model {
    const VERSION = "2.3.1";
    const CONTENT_TYPE = "application/json";

    private $bidRequest = null;

    public function rules(){
        return [
            ['bidRequest', 'required'],
            ['bidRequest', 'validateRequest'],
        ];
    }

    public function load($request, $formName = null){
        if ($request->contentType !== self::CONTENT_TYPE) {
            \Yii::error('[ORTB] Wrong reqquest type (not JSON)');
            return false;
        }
        if (! ($version = $request->headers->get('x-openrtb-version'))) {
            \Yii::error('[OpenRTB]: undefined OpenRTB version. Required "'.self::VERSION.'"');
            return false;
        }
        // контроль версии запроса. важна только первая цифра до точки
        if ( explode('.', $version)[0] !== explode('.', self::VERSION)[0]) {
            \Yii::error('[OpenRTB]: incorrect OpenRTB version '.$version.'. Required "'.self::VERSION.'"');
            return false;
        }
        // попытка загрузить Json-данные
        try {
            $this->bidRequest = Json::decode(str_replace("'","",$request->rawBody), false); // как объект
        } catch (InvalidParamException $e){
            \Yii::error('Ошибка в данных '.$request->rawBody, 'ORTB');
            return false;
        }
        return true;
    }

    public function validateRequest(){
        if (!isset($this->bidRequest->id)) {
            // отсутсвует обязательный параметр ID
            \Yii::error('[OpenRTB] Required filed "ID" are not presented');
            return false;
        }
        if (!isset($this->bidRequest->imp) or !is_array($this->bidRequest)) {
            // отсутсвует обязательный параметр Impression
            \Yii::error('[OpenRTB] Required object "Impression" are not presented or is not array');
            return false;
        }
        foreach ($this->bidRequest->imp as $imp)
            if (!(isset($imp->banner) || isset($imp->video))) {
                // Нет ни одного обязательного параметра Banner и Video
                \Yii::error('[OpenRTB] Required objects "Banner" or "Video" are not presented');
                return false;
            }
        /*** Необязательные поля ***/
        if (isset($this->bidRequest->site) && isset($this->bidRequest->app)){
            // Одновременное присутствие двух параметров site и app нарушает формат
            \Yii::error('[OpenRTB] Objects "Site" and "App" are present at the same time');
            return false;
        }

        return true;
    }

} 