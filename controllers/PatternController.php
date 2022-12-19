<?php
namespace app\controllers;
use yii\rest\ActiveController;
use yii\rest\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;
use Yii;
/*Опишите здесь действия, которые приходится
часто рсуществлять*/

class PatternController extends Controller {

    public function send($code, $data){
        $response=$this->response;
        $response->format = Response::FORMAT_JSON;
        header('Access-Control-Allow-Origin: *');
        $response->data=$data;
        $response->statusCode=$code;
        return $response;
    }

    /* Формирование и отправка ошибок валидации*/
    public function ErrorValidation($model){
        $error=['error'=> ['code'=>422, 'message'=>'Validation error',
            'errors'=>ActiveForm::validate($model)]];
        return $this->send(422, $error);
    }

    /*Проверка является ли пользователь админом*/
    public function is_admin(){
        $user = Yii::$app->user->identity;

        if ($user->is_admin==0){
            $content = ['error'=>['code'=>401,'message'=>"incorrect Bearer"]];
            return $this->send(401,$content);

        }
        else
            return null;

    }

}