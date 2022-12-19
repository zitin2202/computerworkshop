<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property string $order_number
 * @property string $status
 * @property int $total
 * @property string $date
 * @property int $user_id
 *
 * @property OrderService[] $orderServices
 * @property User $user
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_number', 'status', 'total', 'date', 'user_id'], 'required'],
            [['total', 'user_id'], 'integer'],
            [['date'], 'safe'],
            [['order_number'], 'string', 'max' => 80],
            [['status'], 'string', 'max' => 100],
            [['order_number'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_number' => 'Order Number',
            'status' => 'Status',
            'total' => 'Total',
            'date' => 'Date',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[OrderServices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderServices()
    {
        return $this->hasMany(OrderService::className(), ['order_id' => 'order_number']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getServices() {
        return $this->hasMany(Service::className(), ['id_service' => 'service_id'])
            ->viaTable('order_service', ['order_id' => 'order_number']);
    }
}
