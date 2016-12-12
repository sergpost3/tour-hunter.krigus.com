<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transactions-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Send money', ['create'], ['class' => 'btn btn-success']) ?>
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
        ],
    ]); ?>
</div>
