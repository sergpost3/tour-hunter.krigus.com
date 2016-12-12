<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Transactions;

class TransactionForm extends Model
{
    public $recipient;
    public $total;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username is required
            [['recipient', 'total'], 'required'],
            ['total', 'integer']
        ];
    }

    public function createTransaction() {
        $recipient = User::findByUsername($this->recipient);

        $model = new Transactions();
        $model->sender_id = Yii::$app->user->id;
        $model->recipient_id = $recipient->getId();
        $model->total = $this->total;
        $model->save();
        return true;
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            if (!$user = $this->getUser())
                $user = User::createNewUser($this->username);
            return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
