<?php

class m170515_013310_add_user_type_field extends CDbMigration
{
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $this->addColumn('tcg_users', 'user_type', 'varchar(20) NOT NULL');

        // Make all current users developers except for the admin user who receives the administrator type.
        $this->update('tcg_users', array('user_type'=>'developer'), 'id != 1');
        $this->update('tcg_users', array('user_type'=>'administrator'), 'id=1');
    }

    public function safeDown()
    {
        $this->dropColumn('tcg_users', 'user_type');
    }
}