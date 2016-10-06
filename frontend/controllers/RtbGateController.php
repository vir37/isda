<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2016
 * Time: 15:55
 */

namespace frontend\controllers;

use yii\web\Controller,
    yii\web\Response,
    yii\web\NotFoundHttpException,
    yii\filters\VerbFilter;
use common\models\OpenRTB;

class RtbGateController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex(){
        throw new NotFoundHttpException();
    }

    public function actionRequest() {
        $req = \yii::$app->request;
        $response = new Response();
        $response->format = Response::FORMAT_JSON;
        $response->headers->set('x-openrtb-version',
                                $req->headers->get('x-openrtb-version', OpenRTB::VERSION));
        $response->statusCode = 204;
        $model = new OpenRTB();
        if ($model->load($req) && $model->validate()) {
            $response->statusCode = 200;
        }
        return $response;
    }
} 