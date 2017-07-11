<?php

use yii\db\Migration;

class m170704_084317_added_webService extends Migration
{
    public function safeUp()
    {
        //32 is the size of the string generated with generateRandomString
        $this->addColumn('user', 'access_token', $this->string(32)->unique());
        $this->update('user', ['access_token' => \Yii::$app->security->generateRandomString()], ['id' => '1']);
    }

    public function safeDown()
    {
        $this->dropColumn('user', 'access_token');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170704_084317_added_webService cannot be reverted.\n";

        return false;
    }
    */
}
