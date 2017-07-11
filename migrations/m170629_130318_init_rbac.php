<?php

use yii\db\Migration;
use app\components\AuthorizationConstants;

//because i migrate using cmd i can't use the components so i have to 
//load the class directly with require
require_once(Yii::getAlias('@app') . "\components\AuthorizationConstants.php");

class m170629_130318_init_rbac extends Migration
{

    //to generate tables use yii migrate --migrationPath=@yii/rbac/migrations
    public function safeUp()
    {

        //create two types of user admins and regular users.
        $auth = Yii::$app->authManager;
        $adminPermission = $auth->createPermission(AuthorizationConstants::ADMIN_PERMISSION);
        $adminPermission->description = 'Admin rights';
        $auth->add($adminPermission);

        $admin = $auth->createRole(AuthorizationConstants::ADMIN_ROLE);
        $auth->add($admin);
        $auth->addChild($admin, $adminPermission);
        $auth->assign($admin, 1);
        
        
        $regularUserPermission = $auth->createPermission(AuthorizationConstants::REGULAR_USER_PERMISSION);
        $regularUserPermission->description = 'Regular user rights';
        $auth->add($regularUserPermission);
        
        $regularUser = $auth->createRole(AuthorizationConstants::REGULAR_USER_ROLE);
        $auth->add($regularUser);
        $auth->addChild($regularUser, $regularUserPermission);
        $auth->assign($regularUser, 2);
       
        
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m170629_125733_authorizations cannot be reverted.\n";

      return false;
      }

     */
}
