<?php

namespace app\modules\trip\controllers;

use Yii;
use app\modules\trip\models\TripForm;
use app\modules\trip\models\TripSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\base\UserException;
use yii\web\ForbiddenHttpException;

//require_once(Yii::getAlias('@app') . "\components\AuthorizationConstants.php");

/**
 * TripController implements the CRUD actions for TripForm model.
 */
class TripController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        //The permission name it's found in the authorizationConstants componenent
        $constants = Yii::$app->authorizationConstants;
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [$constants::REGULAR_USER_PERMISSION],
                        //matchCallback is used to decide if the rule should be used
                        //or not. If the rule is used everyone with the regular user
                        //permission has access. If the rule isn't used then 
                        //no one has access because there are no more rules and
                        // accessControls default action is to deny all access
                        'matchCallback' => function ($rule, $action) {
                            return $this->checkAuthroziation($rule, $action);
                        },
                    ],
//                    [
//                        'allow' => true,
//                        'roles' => ['@']
//                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    //
    //This method is used to check if the current user has the authorization
    //to access or modify the trip with the id found in query string.
    //@return boolean the authorization result
    //
    private function checkAuthroziation($rule, $action)
    {
        Yii::info('Action id is:' . $action->id);
        $model = new TripForm();
        //the id for delete can be found in get paramater
        $data = Yii::$app->request->get();
        $tripid = null;
        if (isset($data['id'])) {
            $tripid = $data['id'];
        }
        Yii::info('Trip id is:' . json_encode($data));

        switch ($action->id) {
            case 'view':
                return $model->hasViewAuthorization($tripid);
                break;
            case 'delete':
                return $model->hasDeleteAuthorization($tripid);
                break;
            case 'update':
                return $model->hasUpdateAuthorization($tripid);
                break;
        }
        return true;
    }

    //
    //This method is used to check if the current user has the authorization
    //to access or modify the trip with the id found in query string.
    //@param action
    //@throws ForbiddenHttpException
//    public function beforeAction($action)
//    {
//        $parentBeforeActionResult = parent::beforeAction($action);
//
//        Yii::info('Action id is:' . $action->id);
//        $model = new TripForm();
//        //the id for delete can be found in get paramater
//        $data = Yii::$app->request->get();
//        $tripid = null;
//        if (isset($data['id'])) {
//            $tripid = $data['id'];
//        }
//        Yii::info('Trip id is:' . json_encode($data));
//        $result = null;
//        switch ($action->id) {
//            case 'view':
//                $result = $model->hasViewAuthorization($tripid);
//                break;
//            case 'delete':
//                $result = $model->hasDeleteAuthorization($tripid);
//                break;
//            case 'update':
//                $result = $model->hasUpdateAuthorization($tripid);
//                break;
//        }
//        if (!is_null($result)) {
//            if (!$result) {
//                throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action.');
//            }
//        }
//       
//        return $parentBeforeActionResult;
//    }

    /**
     * Lists all TripForm models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TripSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TripForm model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TripForm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TripForm();

        //begin transaction
        $transaction = Yii::$app->db->beginTransaction();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::info('Trip create: post id: ' . $model->id);
            try {

                //get all the pictures uploaded
                $model->pictures = UploadedFile::getInstances($model, 'pictures');
                Yii::info('TripController/create/ nr of pictures:' . count($model->pictures));
//                throw new \Exception('exception test');
                //store the pictures
                if ($model->upload()) {
                    $transaction->commit();

                    return $this->redirect(['view',
                                'id' => $model->id]);
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::error($e->getMessage() . ' ' . $e->getFile() . ' '
                        . $e->getLine() . ' ' . $e->getTraceAsString());
                return $this->render('create', [
                            'model' => $model,
                            'exception' => ['message' => 'Could not create the trip please try again later'],
                ]);
                //throw new UserException($e->getMessage());
            }
        }
        Yii::info('Trip/create/ model erros:' . json_encode($model->getErrors()));
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing TripForm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        //it's possible that there will be multiple inserts, so they are done
        //inside a transaction
        $transaction = Yii::$app->db->beginTransaction();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            try {

                //get all the pictures uploaded
                $model->pictures = UploadedFile::getInstances($model, 'pictures');
//                throw new \Exception('exception test');
                //store the pictures
                if ($model->upload()) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::error($e->getMessage() . ' ' . $e->getFile() . ' '
                        . $e->getLine() . ' ' . $e->getTraceAsString());
                return $this->render('create', [
                            'model' => $model,
                            'exception' => ['message' => 'Could not update the trip please try again later'],
                ]);
            }
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Trip model and the Trip_images associated with it.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $trip = $this->findModel($id);
        $trip_images = $trip->getTripImage()->all();
        foreach ($trip_images as $trip_image) {
            $trip_image->delete();
        }
        $trip->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TripForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TripForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TripForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
