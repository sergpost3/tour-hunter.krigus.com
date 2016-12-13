<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoices-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Invoice', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'Sender',
                'value' => function($data){
                    return User::findIdentity($data->sender_id)->username;
                }
            ],
            [
                'label' => 'Recipient',
                'value' => function($data){
                    return User::findIdentity($data->recipient_id)->username;
                }
            ],
            'total',
            [
                'label' => 'Status',
                'value' => function($data){
                    if(!$data->status)
                        return "New";
                    if(!$data->transaction_id)
                        return "Denied";
                    return "Paid";
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{pay} {deny}',
                'buttons' => [
                    'pay' => function ($url, $model) {
                        return Html::a("Pay", $url, [
                            'title' => Yii::t('yii', 'Pay'),
                            'class' => 'btn btn-success'
                        ]);
                    },
                    'deny' => function ($url, $model) {
                        return Html::a("Deny", $url, [
                            'title' => Yii::t('yii', 'Deny'),
                            'class' => 'btn btn-danger'
                        ]);
                    }
                ]
            ],
        ],
    ]); ?>

    <h1><?= Html::encode("My invoices") ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProviderTo,
        'columns' => [
            [
                'label' => 'Sender',
                'value' => function($data){
                    return User::findIdentity($data->sender_id)->username;
                }
            ],
            [
                'label' => 'Recipient',
                'value' => function($data){
                    return User::findIdentity($data->recipient_id)->username;
                }
            ],
            'total',
            [
                'label' => 'Status',
                'value' => function($data){
                    if(!$data->status)
                        return "New";
                    if(!$data->transaction_id)
                        return "Denied";
                    return "Paid";
                }
            ]
        ],
    ]); ?>
</div>
