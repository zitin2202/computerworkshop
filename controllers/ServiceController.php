<?php
namespace app\controllers;
use app\models\LoginForm;
use app\models\Service;
use app\models\ServiceChangeForm;
use app\models\User;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class ServiceController extends PatternController
{
    public $modelClass = 'app\models\service';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only'=>['create','change','delete']
        ];
        return $behaviors;
    }

    public function actionList()
    {
        $services = Service::find()->all();

        $content = ['content'=>['services'=>$services]];

        return $this->send(200,$content);

    }

    public function actionCreate()
    {
        $error = $this->is_admin();
        if ($error!=null){
            return $error;
        }

        $data = \Yii::$app->request->post();
        $service = new Service();
        $service->load($data,'');
        if(!$service->validate())
            return $this->ErrorValidation($service);

        $service->save();


        $content = ['content'=>['id_service'=>$service->id_service]];

        return $this->send(200,$content);

    }



    public function actionChange($id_service)
    {
        $error = $this->is_admin();
        if ($error!=null){
            return $error;
        }

        $data = \Yii::$app->request->post();


        $service = Service::findOne($id_service);
        if ($service==null){
            return $this->send(404, ['content'=>['code'=>404, 'message'=>'Service not found']]);

        }
        $service->load($data,'');
        if(!$service->validate())
            return $this->ErrorValidation($service);

        $service->save();



        return $this->send(202,null);

    }


    public function actionDelete($id_service)
    {

        $error = $this->is_admin();
        if ($error!=null){
            return $error;
        }



        $service = Service::findOne($id_service);
        if ($service==null){
            return $this->send(404, ['content'=>['code'=>404, 'message'=>'Service not found']]);

        }
        $service->delete();



        return $this->send(202,null);

    }


}