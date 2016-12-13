<?php

namespace app\controllers;

use Yii;
use app\models\Transactions;
use app\models\TransactionForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TransactionsController implements the CRUD actions for Transactions model.
 */
class TransactionsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if(Yii::$app->user->isGuest)
            throw new \yii\web\ForbiddenHttpException();

        $dataProvider = new ActiveDataProvider([
            'query' => Transactions::find()
                ->where(['sender_id' => Yii::$app->user->id])
                ->orWhere(['recipient_id' => Yii::$app->user->id]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        if(Yii::$app->user->isGuest)
            throw new \yii\web\ForbiddenHttpException();

        $model = new TransactionForm();

        if( $model->load( Yii::$app->request->post() ) && $model->createTransaction() ) {
            return $this->redirect( [ 'transactions/index' ] );
        }
        else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    protected function findModel($id)
    {
        if (($model = Transactions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
