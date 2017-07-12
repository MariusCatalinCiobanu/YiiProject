<?php

namespace app\modules\login\controllers;

use Yii;
use app\modules\login\models\UsersAdmin;
use app\modules\login\models\UsersAdminSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\AuthItem;

//require_once(Yii::getAlias('@app') . "\components\AuthorizationConstants.php");
/**
 * AdminPanelController implements the CRUD actions for UsersAdmin model.
 */
class AdminPanelController extends Controller
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
                        'roles' => [$constants::ADMIN_PERMISSION]
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
            
              //
              //Doesn't work because the sql dependecy only checks if the number
              //of users has changed and not if any of the user is updated, or
              //what page is selected
//            //page cache
//            [
//                'class' => 'yii\filters\PageCache',
//                'only' => ['index'],
//                'duration' => 60,
//                'variations' => [
//                    \Yii::$app->language,
//                ],
//                'dependency' => [
//                    'class' => 'yii\caching\DbDependency',
//                    'sql' => 'SELECT COUNT(*) FROM user',
//                ],
//            ],
        ];
    }

    /**
     * Lists all UsersAdmin models.
     * @return string
     */
    public function actionIndex()
    {

        $searchModel = new UsersAdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UsersAdmin model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModelWithUpdatedAndCreated($id);

        return $this->render('view', [
                    'model' => $model,
                    'id' => $id,
        ]);
    }

    /**
     * Creates a new UsersAdmin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string
     * @throws \yii\db\Exception 
     */
    public function actionCreate()
    {
        Yii::info('ActionCreate data: ' . json_encode(Yii::$app->request->post()));
        $roles = $this->getRoles();
        $model = new UsersAdmin(['scenario' => UsersAdmin::SCENARIO_REGISTER]);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post()) && $model->signUp()) {
//                throw new \Exception('Test');
//
//                $result = Yii::$app->mailer->compose()
//                        ->setFrom('clarisotmarius.ciobanu@gmail.com')
//                        ->setTo('marius.ciobanu@clarisoft.com')
//                        ->setSubject('Login verification')
//                        ->setTextBody('Plain text content')
//                        ->send();
//                Yii::info('The email has been sent' . $result ? 'yes' : 'no');
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $model->removePasswordFromModel();
                return $this->render('create', [
                            'model' => $model,
                            'roles' => $roles,
                ]);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage() . ' ' . $e->getFile() . ' '
                    . $e->getLine() . ' ' . $e->getTraceAsString());
            $model->removePasswordFromModel();

            //throw the exception back
            throw $e;
//            return $this->render('create', [
//                        'exception' => ['message' => 'Could not create user, please try again later'],
//                        'model' => $model,
//                        'roles' => $roles,
//            ]);
        }
    }

    /**
     * Updates an existing UsersAdmin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id, $scenario = UsersAdmin::SCENARIO_REGISTER);
        $roles = $this->getRoles();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {

            //add the password in the confimpassword
            $model->confirmPassword = $model->password;
            return $this->render('update', [
                        'model' => $model,
                        'roles' => $roles,
            ]);
        }
    }

    /**
     * Deletes an existing UsersAdmin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UsersAdmin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown. You can
     * set a scenario to the model
     * @param integer $id
     * $param string scenario 
     * @return UsersAdmin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $scenario = null)
    {
        if (($model = UsersAdmin::findOne($id)) !== null) {
            if ($scenario !== null) {
                $model->scenario = $scenario;
            }
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //
    // Finds the UsersAdmin model based on its primary key, also returns
    // the foreign keys values
    // @param integer $id
    // @return UsersAdmin the loaded model
    // @throws NotFoundHttpException if the model cannot be found
    //
    protected function findModelWithUpdatedAndCreated($id)
    {
        if (($model = UsersAdmin::findModelWithUpdatedAndCreated($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //
    //Get all the roles used for authorization
    //@return array
    //
    public function getRoles()
    {
        $model = new AuthItem();
        $roles = $model->getRolesNames();

        return $roles;
    }

}
