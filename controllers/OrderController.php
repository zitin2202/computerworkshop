<?php
namespace app\controllers;
use app\models\Order;
use app\models\OrderService;
use app\models\Service;
use phpDocumentor\Reflection\Type;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class OrderController extends PatternController
{
    public $modelClass = 'app\models\order';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only'=>['order','delete']
        ];
        return $behaviors;
    }

    private function generateOrderNumber()
    {
        $random = rand(1, 10000000000000);
        $order_number = hash('ripemd160', $random);
        return $order_number;
    }
    public function actionOrder()
    {
        $data = \Yii::$app->request->post();
        $order = new Order();
        $order->order_number = $this->generateOrderNumber();
        $user = Yii::$app->user->identity;
        $order->user_id = $user->id;
        $order->status = "expected";
        $order->date = date('Y-m-d H:i:s');
        $order->total=0;
        $order->save();

        if ($data == null or gettype($data)!='array'){
            $order->delete();
            $content = ['error'=>['code'=>422,'message'=>"Validation error",'errors'=>["Сервисы не были введены, или введены не правильно"]]];
            return $this->send(422,$content);
        }

        $orders_services = [];
        foreach ($data["services"] as $service_id){
            $service = Service::findOne($service_id);
            if ($service==null){
                $order->delete();
                return $this->send(401, ['content'=>['code'=>404, 'message'=>'Service ' . $service_id . ' not found']]);

            }
            $order_service = new OrderService();
            $order_service->order_id = $order->order_number;
            $order_service->service_id = $service_id;
            if (!$order_service->validate()) {
                $error_validate = $this->ErrorValidation($order_service);
                $order->delete();
                return $error_validate;

            }
            array_push($orders_services,$order_service);

            $price = Service::findOne($service_id)->price;

            $order->total = $order->total+=$price;
        }

        $order->save();
        foreach ($orders_services as $order_service) {
            $order_service->save();
        }



        $content = ['content'=>['order_number'=>$order->order_number]];

        return  $this->send(200,$content);

    }

    public function actionDelete($order_number){

        $order = Order::findOne([$order_number]);
        if ($order==null){
            return $this->send(404, ['content'=>['code'=>404, 'message'=>'Order not found']]);

        }

        $user = Yii::$app->user->identity;
        if ($user->id != $order->user_id and $user->is_admin==0){
            $content = ['error'=>['code'=>401,'message'=>"incorrect Bearer"]];
            return  $this->send(401,$content);

        }

        OrderService::deleteAll(['order_id'=>$order_number]);
        $order->delete();







    }



}