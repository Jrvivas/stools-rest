<?php

namespace app\models;

use Yii;


/**
 * This is the model class for table "detalle pedido".
 *
 * @property int $id
 * @property int $pedido_id
 * @property string $app_idApp
 * @property int $productos_id
 * @property string $detalle
 * @property float $cantidad
 * @property float|null $fraccion
 * @property float|null $alto
 * @property float|null $ancho
 * @property float $monto
 * @property Apps $appIdApp
 */
class DetallePedido extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detallepedido';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'app_idApp', 'pedido_id','productos_id','cantidad','monto'], 'required'],
            [['id','pedido_id','productos_id','inst'], 'integer'],
            [['cantidad','monto','costo', 'alto', 'ancho','fraccion'], 'number'],
            [['app_idApp'], 'string', 'max' => 124],
            [['detalle'], 'string', 'max' => 512],
            [['id', 'app_idApp','pedido_id'], 'unique', 'targetAttribute' => ['id', 'app_idApp','pedido_id']],
            [['app_idApp'], 'exist', 'skipOnError' => true, 'targetClass' => Apps::className(), 'targetAttribute' => ['app_idApp' => 'idApp']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_idApp' => 'App Id App',
            'pedido_id' => 'Id Pedido',
            'productos_id' => 'Id Producto',
            'detalle' => 'Descripcion',
            'cantidad'=>'Cantidad',
            'monto' => 'Monto',
            'costo'=>'Costo',
            'alto' => 'Alto',
            'ancho'=>'Ancho',
            'inst'=>'Instalación',
            'fraccion' => 'Francion',
            
        ];
    }



    /**
     * Gets query for [[AppIdApp]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppIdApp()
    {
        return $this->hasOne(Apps::className(), ['idApp' => 'app_idApp']);
    }

    /**
     * Gets query for [[Productos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducto()
    {
        return $this->hasOne(Productos::className(), ['app_idApp' => 'app_idApp','id'=>'productos_id']);
    }
        /**
     * Gets query for Pedido.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPedido()
    {
        return $this->hasOne(Pedido::className(), ['app_idApp' => 'app_idApp','id'=>'pedido_id']);
    }


    public function updateStock($tipo){
        //Avtualiza el stock segun el tipo de operación
    }

    

    public function maxId($id,$pedido_id){
        return $this->find()->where(['app_idApp'=>$id,'pedido_id'=>$pedido_id])->max('id');
     }

     /**
      * Devuelve el objeto en un json cargado
      */
    public function toJson(){
        return  json_encode($this->find()->where(['id'=>$this->id,'app_idApp'=>$this->app_idApp,'pedido_id'=>$this->pedido_id])->asArray()->one());
    }

}
