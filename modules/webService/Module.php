<?php

namespace app\modules\WebService;

use Yii;
/**
 * WebService module definition class
 */
class Module extends \yii\base\Module
{

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\webService\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        //Yii::$app->user->enableSession = false;
        //Yii::$app->user->loginUrl = null;
        // custom initialization code goes here
    }

}
