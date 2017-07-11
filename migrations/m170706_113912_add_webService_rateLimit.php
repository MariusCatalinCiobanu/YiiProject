<?php

use yii\db\Migration;

class m170706_113912_add_webService_rateLimit extends Migration
{
    public function safeUp()
    {
//        $this->addColumn('user', 'rate_limit', $this->integer());
//        $this->addColumn('user', 'allowance', $this->integer());
//        $this->addColumn('user', 'allowance_updated_at', $this->dateTime());
//        $this->update('user',[
//            'rate_limit' => 1, 'allowance' => 1],['id' => 1]);
    }

    public function safeDown()
    {
//        $this->dropColumn('user', 'rate_limit');
//        $this->dropColumn('user', 'allowance');
//        $this->dropColumn('user', 'allowance_updated_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170706_113912_add_webService_rateLimit cannot be reverted.\n";

        return false;
    }
    */
}
