<?php
namespace app\controllers;

use app\models\Productos;
use yii\rest\ActiveController;
use Yii;
use yii\web\HttpException;

class ProductoController extends ActiveController
{
    public $modelClass = 'app\models\Productos';

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
		$productos=Null;
        if($id==0){
			$productos=Productos::find()->where(["app_idApp"=>$app_idApp])->all();   
		}else{
			$productos=Productos::findOne([$id,$app_idApp]); 
		}
		
		
        if($productos){
            return $productos;
        }else{
            throw new HttpException(404);
        }
        

    }
	
	public function actionUpdate(){
		   /*$request = Yii::$app->request;

			// returns all parameters
			$params = $request->getBodyParams();

			// returns the parameter "id"
			$param = $request->getBodyParam('id');*/
			$request = Yii::$app->request;

            if (isset($request)) {
				$id=$request->getBodyParam("id");
				$newId=$id;
				
				$producto=new Productos();
				
				if($id==0){
					$newId=$producto->maxId($request->getBodyParam("app_idApp"))+1;

				}else{
					$producto=Productos::findOne(["app_idApp"=>$request->getBodyParam("app_idApp"),"id"=>$id]);	
				}
				
				

				$fields=$producto->attributes();

				foreach($fields as $f){
					$producto->{$f}=$request->getBodyParam($f);
				}
				$producto->id=$newId;
				
				
				if($producto->save()){
					return $producto;
				}else{
					return $producto->errors;
				}
			}
			
		
		return [];
	}


}