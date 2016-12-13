<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Transactions;

class TransactionForm extends Model
{
    public $recipient;
    public $total;

    public function rules()
    {
        return [
            [['recipient', 'total'], 'required'],
            //['recipient', 'validateRecipient', 'skipOnEmpty' => false, 'skipOnError' => false],
            ['total', 'integer'],
        ];
    }

    public function validateRecipient($attribute, $params)
    {
        var_dump($this->$attribute);
        if(Yii::$app->user->identity->username == $this->$attribute)
        {
            $this->addError($attribute, 'It is your username');
        }
    }

    public function createTransaction() {
        return Transactions::sendMoney(Yii::$app->user->id, $this->recipient, $this->total);
    }
}
