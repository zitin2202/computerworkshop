<?php
namespace app\controllers;
use app\models\LoginForm;
use Yii;
use app\models\User;
use yii\debug\models\search\Log;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\rest\Controller;

class UserController extends PatternController
{
    public $modelClass = 'app\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only'=>['profile','change-profile','view-profile-by-admin']
        ];
        return $behaviors;
    }

    private function getProfile($user){
        $orders = $user->getOrders()->all();

        $orders_content = [];
        for ($order_i=0; $order_i<count($orders); $order_i++) {
            $orders_content[$order_i] = [
                'order_number'=>$orders[$order_i]->order_number,
                'status'=>$orders[$order_i]->status,
                'total'=>$orders[$order_i]->total,
                'date'=>$orders[$order_i]->date,
                'services'=>[]
            ];

            $services = $orders[$order_i]->getServices()->all();
            for ($service_i = 0; $service_i<count($services); $service_i++) {
                $orders_content[$order_i]['services'][$service_i] = [
                    'name'=>$services[$service_i]->name,
                    'price'=>$services[$service_i]->price
                ];

            }
        }

        $content = ['content'=>[
            'name'=>$user->first_name . " " . $user->last_name,
            'login'=>$user->login,
            'phone'=>$user->phone,
            'orders'=>$orders_content]];

        return $this->send(200,$content);
    }

    public function actionCreate()
    {
        $data= Yii::$app->request->post();
        $user = new User();
        $user->load($data,'');
        if(!$user->validate())
            return $this->ErrorValidation($user);
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);
        $user->save();
        return $this->send(204,null);
 }

    public function actionLogin(){
        $data = \Yii::$app->request->post();
        $login_data = new LoginForm();
        $login_data->load($data,'');
        if(!$login_data->validate())
            return $this->ErrorValidation($login_data);
        $user = User::find()->where(['login'=>$login_data->login])->one();
        if(!is_null($user) and Yii::$app->getSecurity()->validatePassword($login_data->password,$user->password)){
            $token = Yii::$app->getSecurity()->generateRandomString();
            $user->token = $token;
            $user->save(false);
            $content = ['content'=>['token'=>$token]];
            return $this->send(200,$content);
        }

        return $this->send(401, ['content'=>['code'=>401, 'message'=>'Неверный логин или пароль']]);

    }

    public function actionProfile(){
        $user = Yii::$app->user->identity;

        return $this->getProfile($user);


    }

    public function actionChangeProfile(){
        $data = Yii::$app->request->post();

        $user = Yii::$app->user->identity;

        $user->load($data,'');
        if (!$user->validate()){
            return $this->ErrorValidation($user);
        }
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);


        $user->save();

        $this->send(204,null);


    }


    public function actionViewProfileByAdmin($id){
        $error = $this->is_admin();
        if ($error!=null){
            return $error;
        }

        $user = User::findOne($id);
        if ($user==null){
            return $this->send(404, ['content'=>['code'=>404, 'message'=>'User not found']]);

        }
        return $this->getProfile($user);


    }


}
