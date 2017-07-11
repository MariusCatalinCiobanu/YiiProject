<?php

use yii\db\Migration;

class m170711_113129_email_confirmation_timestamp extends Migration
{
    public function safeUp()
    {
        $this->addColumn('Email_Confirmation',
                'expiration_timestamp',
                $this->timestamp());
    }

    public function safeDown()
    {
        $this->dropColumn('Email_Confirmation', 'expiration_timestamp');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170711_113129_email_confirmation_timestamp cannot be reverted.\n";

        return false;
    }
    */
}
