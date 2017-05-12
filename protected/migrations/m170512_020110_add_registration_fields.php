<?php

class m170512_020110_add_registration_fields extends CDbMigration
{
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        // add the registration columns to the users table.
        $this->addColumn('tcg_users', 'first_name', 'varchar(100) NOT NULL');
        $this->addColumn('tcg_users', 'surname', 'varchar(100) NOT NULL');
        $this->addColumn('tcg_users', 'email', 'varchar(100) NOT NULL');

        // modify the admin user to prevent NOT NULL constraint violations.
        $this->update('tcg_users', array('first_name'=>'Admin', 'surname'=>'User', 'email'=>'admin@tcgapi.com'));
    }

    public function safeDown()
    {
        // drop the new columns.
        $this->dropColumn('tcg_users', 'first_name');
        $this->dropColumn('tcg_users', 'surname');
        $this->dropColumn('tcg_users', 'email');
    }
}