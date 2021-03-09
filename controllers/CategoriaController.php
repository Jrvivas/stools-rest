<?php
namespace app\controllers;

use yii\rest\ActiveController;
use app\models\Categoria;
use Yii;
use yii\web\HttpException;

class CategoriaController extends ActiveController
{
    public $modelClass = 'app\models\Categoria';
	
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

    public function actionIndex($app_idApp,$codigo)

    {
		$categorias=Null;
        if($codigo=='#' or $codigo==''){
			$categorias=Categoria::find()->where(["app_idApp"=>$app_idApp])->all();   
		}else{
			$categorias=Categoria::findOne([$codigo,$app_idApp]); 
		}
		
		
        if($categorias){
            return $categorias;
        }else{
            throw new HttpException(404);
        }
        

    }
	
	public function actionUpdate(){

			$request = Yii::$app->request;

            if (isset($request)) {
				$codigo=$request->getBodyParam("codigo");
				
				
				$categoria=new Categoria();
				
				if($codigo==''){
					 throw new HttpException(404);

				}else{
					$categoria=Categoria::findOne(["app_idApp"=>$request->getBodyParam("app_idApp"),"codigo"=>$codigo]);	
					if($categoria==Null){
						$categoria=new Categoria();
					}
				}
				
				

				$fields=$categoria->attributes();

				foreach($fields as $f){
					$categoria->{$f}=$request->getBodyParam($f);
				}
				
				
				
				if($categoria->save()){
					return $categoria;
				}else{
					return $categoria->errors;
				}
			}
			
		
		return [];
	}

}