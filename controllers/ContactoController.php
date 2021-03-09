<?php
namespace app\controllers;

use yii\rest\ActiveController;
use app\models\Contacto;
use Yii;
use yii\web\HttpException;

class ContactoController extends ActiveController
{
    public $modelClass = 'app\models\Contacto';
	
	public function behaviors()
	{

		
		$behaviors = parent::behaviors();

		// remove authentication filter
		$auth = $behaviors['authenticator'];
		unset($behaviors['authenticator']);
		
		// add CORS filter
		$behaviors['corsFilter'] = [
			'class' => \yii\filters\Cors::className(),
		];
		
		// re-add authentication filter
		$behaviors['authenticator'] = $auth;
		// avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
		$behaviors['authenticator']['except'] = ['options'];

		return $behaviors;
	}
	
	    public function actions()

    {

        $actions = parent::actions();


        unset($actions['view']);
		unset($actions['index']);
		unset($actions['update']);

        return $actions;

    }

    public function actionIndex($app_idApp,$id)

    {
		$contactos=Null;
        if($id==0 ){
			$contactos=Contacto::find()->where(["app_idApp"=>$app_idApp])->all();   
		}else{
			$contactos=Contacto::findOne([$id,$app_idApp]); 
		}
		
		
        if($contactos){
            return $contactos;
        }else{
            throw new HttpException(404);
        }
        

    }
	
	public function actionUpdate(){

			$request = Yii::$app->request;

            if (isset($request)) {
				$id=$request->getBodyParam("id");
				$newId=$id;
				
				$contacto=new Contacto();
				
				if($id==0){
					 $newId=$contacto->maxId($request->getBodyParam("app_idApp"))+1;

				}else{
					$contacto=Contacto::findOne(["app_idApp"=>$request->getBodyParam("app_idApp"),"id"=>$id]);	
					if($contacto==Null){
						$contacto=new Contacto();
					}
				}
				
				

				$fields=$contacto->attributes();

				foreach($fields as $f){
					$contacto->{$f}=$request->getBodyParam($f);
				}
				if($contacto->tipo==null) $contacto->tipo='NORMAL';
				$contacto->id=$newId;
				
				if($contacto->save()){
					return $contacto;
				}else{
					return $contacto->errors;
				}
			}
			
		
		return [];
	}

}