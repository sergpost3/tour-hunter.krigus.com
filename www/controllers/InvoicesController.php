<?php

namespace app\controllers;

use Yii;
use app\models\Invoices;
use app\models\InvoiceForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InvoicesController implements the CRUD actions for Invoices model.
 */
class InvoicesController extends CController
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

    public function actionCreate()
    {
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
