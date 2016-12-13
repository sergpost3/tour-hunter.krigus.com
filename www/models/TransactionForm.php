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

        $recipient->balance += intval($this->total);
        $recipient->save();

        $sender = User::findIdentity(Yii::$app->user->id);
        $sender->balance -= intval($this->total);
        $sender->save();

        return true;
    }
}
