<?php

use yii\db\Migration;
use app\components\AuthorizationConstants;

require_once(Yii::getAlias('@app') . "\components\AuthorizationConstants.php");

class m170629_085000_insert_data extends Migration
{

    public function safeUp()
    {
        //temporary disable foreign key constraints so i can insert the first user
        $this->execute('SET foreign_key_checks = 0');
        $this->insert('user', [
            'id' => 1,
            'email' => 'admin@mail.com',
            'password' => '$2y$13$Ns6WgDgj8WZ51jg2PVYcEO1kP0EI0KcOg99y6c8hSILLOVGTLIyZa',
            'type' => AuthorizationConstants::ADMIN_ROLE,
            'auth_key' => '1JvxT2EeBBXXmJCSPFjM9PPG9qfurAPf',
            'created_at' => '2017-06-27 10:47:10',
            'updated_at' => '2017-06-27 10:47:10',
            'created_by' => 1,
            'updated_by' => 1
        ]);

        $this->execute("INSERT INTO `user` (`id`, `email`, `password`, `type`, "
                . "`auth_key`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES"
                . "(2, 'regular@mail.com', " . '\'$2y$13$Ns6WgDgj8WZ51jg2PVYcEO1kP0EI0KcOg99y6c8hSILLOVGTLIyZa\'' . ", '"
                . AuthorizationConstants::REGULAR_USER_ROLE . "', '1JvxT2EeBBXXmJCSPFjM9PPG9qfurAPf', "
                . "'2017-06-27 10:47:10', '2017-06-27 10:47:10', 1, 1)");

        //enable the foreign key constrains
        $this->execute('SET foreign_key_checks = 1');
        $this->insert('trip', [
            'id' => 1,
            'name' => 'Hawaii',
            'description' => 'It was awesome',
            'destination_country' => 'USA',
            'destination_city' => 'Honolulu',
            'destination_latitude' => '21.516962',
            'destination_longitude' => '-157.960739',
            'home_latitude' => '21.329036',
            'home_longitude' => '-157.794914',
            'user_id' => 2
        ]);


        $this->execute("INSERT INTO `trip_image` (`id`, `image_path`, `trip_id`) VALUES"
                . "(1, '/upload/imagePath/1.jpg', 1)");
        $this->execute("INSERT INTO `trip_image` (`id`, `image_path`, `trip_id`) VALUES"
                . "(2, '/upload/imagePath/2.jpg', 1)");
        $this->execute("INSERT INTO `trip_image` (`id`, `image_path`, `trip_id`) VALUES"
                . "(3, '/upload/imagePath/3.jpg', 1)");
        $this->execute("INSERT INTO `trip_image` (`id`, `image_path`, `trip_id`) VALUES"
                . "(4, '/upload/imagePath/4.jpg', 1)");
    }

    public function safeDown()
    {
        $this->execute('SET foreign_key_checks = 0');
        $this->truncateTable('trip_image');
        $this->truncateTable('trip');
        $this->truncateTable('user');
        $this->execute('SET foreign_key_checks = 1');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m170629_085000_insert_data cannot be reverted.\n";

      return false;
      }
     */
}
