<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $login
 * @property string $phone
 * @property string $password
 * @property int $is_admin
 * @property string|null $token
 *
 * @property Order[] $orders
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'login', 'phone', 'password'], 'required'],
            [['is_admin'], 'boolean'],
            [['first_name', 'last_name', 'login', 'phone', 'password'], 'string', 'max' => 100],
            [['token'], 'string', 'max' => 500],
            [['login'],'unique'],
            [['first_name', 'last_name'],'match' ,'pattern' => '/^[a-zA-Zа-яёА-ЯЁ]+$/u'],
            [['login'],'match' ,'pattern' => '/^[a-zA-Z0-9]+$/'],
            [['phone'],'match' ,'pattern' => '/^[+]{1}[0-9]{11}$/'],
            [['password'],'match' ,'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'login' => 'Login',
            'phone' => 'Phone',
            'password' => 'Password',
            'is_admin' => 'Is Admin',
            'token' => 'Token',
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['user_id' => 'id']);
    }

    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token'=>$token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
}
