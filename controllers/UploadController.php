<?php
namespace app\controllers;

use yii\web\UploadedFile;
use Yii;
use yii\rest\ActiveController;
use app\models\UploadForm;


class UploadController extends ActiveController
{
    public $modelClass = 'app\models\UploadForm';
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
	
	
	public function actionUpload()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
			
			$model->name=Yii::$app->request->post('name');
			
		    $archivo = $_FILES['imageFile']['name'];
			//$model->imageFile =Yii::$app->request->post('imageFile');
			
			if($archivo){
				if($model->subir("aaaa")){
					return ["url"=>$model->urlFile];
				}else{
					return $model->errors;
				}
			}
			
            if (!is_null($model->imageFile)){
				if($model->upload("aaaa")) {
                // el archivo se subió exitosamente
					return ["url"=>$model->urlFile];
				}else{
					return $model->errors;
				}
            }
        }

        return [];
    }
}