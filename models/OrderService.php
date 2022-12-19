<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_service".
 *
 * @property int $id
 * @property string $order_id
 * @property int $service_id
 *
 * @property Order $order
 * @property Service $service
 */
class OrderService extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'service_id'], 'required'],
            [['service_id'], 'integer'],
            [['order_id'], 'string', 'max' => 80],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'order_number']],
            [['service_id'], 'exist', 'skipOnError' => true, 'targetClass' => Service::className(), 'targetAttribute' => ['service_id' => 'id_service']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'service_id' => 'Service ID',
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['order_number' => 'order_id']);
    }

    /**
     * Gets query for [[Service]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Service::className(), ['id_service' => 'service_id']);
    }
}
