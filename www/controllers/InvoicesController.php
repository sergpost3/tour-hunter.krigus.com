<?php

namespace app\controllers;

use Yii;
use app\models\Invoices;
use app\models\InvoiceForm;
use app\models\Transactions;
use yii\data\ActiveDataProvider;
use yii\db\Transaction;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InvoicesController implements the CRUD actions for Invoices model.
 */
class InvoicesController extends Controller
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
            'query' => Invoices::find()
                ->where(['recipient_id' => Yii::$app->user->id])
        ]);

        $dataProviderTo = new ActiveDataProvider([
            'query' => Invoices::find()
                ->where(['sender_id' => Yii::$app->user->id])
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProviderTo' => $dataProviderTo,
        ]);
    }

    public function actionPay($id)
    {
        if(Yii::$app->user->isGuest)
            throw new \yii\web\ForbiddenHttpException();

        $invoice = Invoices::findOne(intval($id));

        if ($invoice && $invoice->pay()) {
            return $this->redirect(['invoices/index']);
        } else {
            throw new \yii\web\ForbiddenHttpException();
        }
    }

    public function actionDeny($id)
    {
        if(Yii::$app->user->isGuest)
            throw new \yii\web\ForbiddenHttpException();

        $invoice = Invoices::findOne(intval($id));

        if ($invoice && $invoice->deny()) {
            return $this->redirect(['invoices/index']);
        } else {
            throw new \yii\web\ForbiddenHttpException();
        }
    }

    public function actionCreate()
    {
        if(Yii::$app->user->isGuest)
            throw new \yii\web\ForbiddenHttpException();

        $model = new InvoiceForm();

        if ($model->load(Yii::$app->request->post()) && $model->createInvoice()) {
            return $this->redirect(['invoices/index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    protected function findModel($id)
    {
        if (($model = Invoices::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
