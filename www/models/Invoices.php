<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoices".
 *
 * @property integer $id
 * @property integer $sender_id
 * @property integer $recipient_id
 * @property integer $total
 * @property integer $status
 * @property integer $transaction_id
 */
class Invoices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_id', 'recipient_id', 'total', 'status'], 'required'],
            [['sender_id', 'recipient_id', 'total', 'status', 'transaction_id'], 'integer'],
        ];
    }

    public function pay()
    {
        if($this->recipient_id !== Yii::$app->user->id)
            return false;

        $transaction_id = Transactions::sendMoney($this->recipient_id, $this->sender_id, $this->total);

        $this->status = 1;
        $this->transaction_id = $transaction_id;
        return $this->save();
    }

    public function deny()
    {
        if($this->recipient_id !== Yii::$app->user->id)
            return false;

        $this->status = 1;
        return $this->save();
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
            'status' => 'Status',
            'transaction_id' => 'Transaction ID',
        ];
    }
}
