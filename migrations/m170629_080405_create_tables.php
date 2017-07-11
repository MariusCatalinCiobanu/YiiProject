<?php

use yii\db\Migration;

class m170629_080405_create_tables extends Migration
{

    public function safeUp()
    {
        $this->createTable('trip' , [
            'id' => $this->primaryKey(),
            'name' => $this->string(45)->notNull(),
            'description' => $this->string(2000),
            'destination_country' => $this->string(45)->notNull(),
            'destination_city' => $this->string(45)->notNull(),
            'destination_latitude' => $this->float()->notNull(),
            'destination_longitude' => $this->float()->notNull(),
            'home_latitude' => $this->float()->notNull(),
            'home_longitude' => $this->float()->notNull(),
            'user_id' => $this->integer()
                ],
                'ENGINE InnoDB');
        $this->createTable('trip_image', [
           'id' => $this->primaryKey(),
            'image_path' => $this->string(100)->notNull(),
            'trip_id' => $this->integer()->notNull()
        ],
                'ENGINE InnoDB');
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'email' => $this->string(45)->notNull()->unique(),
            //yii2 hash size
            'password' => $this->string(60)->notNull(),
            'type' => $this->string(45)->notNull(),
            //generateRandomString result size
            'auth_key' => $this->string(32)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull()
        ], 
                'ENGINE InnoDB');
        $this->addForeignKey(
                'fk-trip-user_id',
                'trip',
                'user_id',
                'user',
                'id',
                'CASCADE');
        $this->addForeignKey(
                'fk-trip_image-trip_id',
                'trip_image',
                'trip_id',
                'trip',
                'id',
                'CASCADE');
        $this->addForeignKey(
                'fk-user-created_by',
                'user',
                'created_by',
                'user',
                'id');
        $this->addForeignKey(
                'fk-user-updated_by',
                'user',
                'updated_by',
                'user',
                'id');
        
    }
    
    

    public function safeDown()
    {
        $this->dropForeignKey('fk-user-updated_by', 'user');
        $this->dropForeignKey('fk-user-created_by', 'user');
        $this->dropForeignKey('fk-trip-image-trip_id', 'trip_image');
        $this->dropForeignKey('fk-trip-user_id','trip');
        $this->dropTable('user');
        $this->dropTable('trip_image');
        $this->dropTable('trip');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m170629_080405_create_tables cannot be reverted.\n";

      return false;
      }
     */
}
