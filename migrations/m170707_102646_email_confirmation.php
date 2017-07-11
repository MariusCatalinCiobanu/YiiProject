<?php

use yii\db\Migration;

class m170707_102646_email_confirmation extends Migration
{
    public function safeUp()
    {
        $this->createTable('Email_Confirmation',[
            'id' => $this->primaryKey(),
            'register_key' => $this->string(32)->unique(),
            'forgot_password' => $this->string(32)->unique(),
            'user_id' => $this->integer()
        ]);
        
        $this->addForeignKey('fk-Email_Confirmation-user_id',
                'Email_Confirmation',
                'user_id',
                'user',
                'id',
                'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('Email_Confirmation');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170707_102646_email_confirmation cannot be reverted.\n";

        return false;
    }
    */
}
