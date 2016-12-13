<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transactions".
 *
 * @property integer $id
 * @property integer $sender_id
 * @property integer $recipient_id
 * @property integer $total
 */
class Transactions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transactions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_id', 'recipient_id', 'total'], 'required'],
            [['sender_id', 'recipient_id', 'total'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_id' => 'Sender ID',
            'recipient_id' => 'Recipient ID',
            'total' => 'Total',
        ];
    }

    public static function sendMoney($from, $to, $sum)
    {
        $recipient = (is_numeric($to)) ? User::findIdentity($to) : User::findByUsername($to);
        $sender = (is_numeric($from)) ? User::findIdentity($from) : User::findByUsername($from);

        $transaction = new Transactions();
        $transaction->sender_id = $sender->getId();
        $transaction->recipient_id = $recipient->getId();
        $transaction->total = $sum;
        $transaction->created = time();
        $transaction_id = $transaction->save();

        $recipient->balance += intval($sum);
        $recipient->save();

        $sender->balance -= intval($sum);
        $sender->save();

        return $transaction_id;
    }
}
