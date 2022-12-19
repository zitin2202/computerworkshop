<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "service".
 *
 * @property int $id_service
 * @property string $name
 * @property int $price
 */
class Service extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price'], 'required'],
            [['price'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['name'],'match' ,'pattern' => '/^[a-zA-Zа-яёА-ЯЁ0-9\s]+$/u'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_service' => 'ID_service',
            'name' => 'Name',
            'price' => 'Price',
        ];
    }


    public function getOrders() {
        return $this->hasMany(Order::className(), ['id' => 'order_id'])
            ->viaTable('order_service', ['service_id' => 'id_service']);
    }

}
