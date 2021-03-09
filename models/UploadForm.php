<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $urlFile;
	public $name;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }
    
    public function upload($idApp)
    {
        if ($this->validate()) {
            $carpeta='data/'.$idApp.'/';
            
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            $this->imageFile->saveAs($carpeta. $this->imageFile->baseName . '.' . $this->imageFile->extension);
            $this->urlFile=$carpeta. $this->imageFile->baseName . '.' . $this->imageFile->extension;
            return true;
        } else {
            $this->urlFile='';
            return false;
        }
    }
	
	public function subir($idApp){
	   //Recogemos el archivo enviado por el formulario
	   $archivo = $_FILES['imageFile']['name'];
	   //Si el archivo contiene algo y es diferente de vacio
	   if (isset($archivo) && $archivo != "") {
		  //Obtenemos algunos datos necesarios sobre el archivo
		  
		  $tipo = $_FILES['imageFile']['type'];
		  $tamano = $_FILES['imageFile']['size'];
		  $temp = $_FILES['imageFile']['tmp_name'];
		  //Se comprueba si el archivo a cargar es correcto observando su extensión y tamaño
		 if (!((strpos($tipo, "gif") || strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 2000000))) {
			$this->errors[]= '<div><b>Error. La extensión o el tamaño de los archivos no es correcta.<br/>-Se permiten archivos .gif, .jpg, .png. y de 200 kb como máximo.</b></div>';
		 }
		 else {
			//Si la imagen es correcta en tamaño y tipo
			$time=explode(' ',microtime())[1];
			$ext = pathinfo($archivo , PATHINFO_EXTENSION);
			$nameFile=$this->name.".".$ext;
			
			//Se intenta subir al servidor
			$carpeta='data/'.$idApp.'/';
            
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
			
			if (move_uploaded_file($temp, $carpeta.$nameFile)) {
				//Cambiamos los permisos del archivo a 777 para poder modificarlo posteriormente
				chmod( $carpeta.$nameFile, 0777);
				 $this->urlFile=$carpeta.$nameFile;
				 return true;
				
			}
			else {
			   //Si no se ha podido subir la imagen, mostramos un mensaje de error
			   $this->errors[]= '<div><b>Ocurrió algún error al subir el fichero. No pudo guardarse.</b></div>';
			   return false;
			}
		  }
	   }
	}
}