<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $login
 * @property integer $balance
 * @property string $authKey
 * @property string $accessToken
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /*public $id;
    public $username;
    public $balance;
    public $authKey;
    public $accessToken;*/

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'balance'], 'required'],
            [['balance'], 'integer'],
            [['username'], 'string', 'max' => 60],
            [['authKey', 'accessToken'], 'string', 'max' => 128],
            [['username'], 'unique'],
        ];
    }

    public static function createNewUser($username)
    {
        $new_user = new static();
        $new_user->username = $username;
        $new_user->balance = 0;
        $new_user->authKey = '';
        $new_user->accessToken = '';
        $new_user->save();
        return $new_user;
    }

    public static function findByUsername($username)
    {
        $user = self::getByWhere(['username' => $username]);

        if(!$user)
            $user = self::createNewUser($username);

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Login',
            'balance' => 'Balance',
            'authKey' => 'Auth Key',
            'accessToken' => 'Access Token',
        ];
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::getByWhere(['accessToken' => $token]);
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return self::getByWhere(['id' => $id]);
    }

    protected function getByWhere($where)
    {
        $users = self::find()
            ->where($where);

        if($users->count() > 0)
            return new static($users->one());

        return null;
    }
}
