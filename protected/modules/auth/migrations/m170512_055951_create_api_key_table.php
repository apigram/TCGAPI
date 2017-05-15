<?php

class m170512_055951_create_api_key_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('tcg_user_keys', array(
            'id' => 'pk',
            'user_id' => 'int NOT NULL',
            'key_title' => 'varchar(50) NOT NULL',
            'api_key' => 'varchar(32) NOT NULL'
        ));

        $this->addForeignKey('tcg_user_keys_fk1','tcg_user_keys', 'user_id', 'tcg_users', 'id');
        $this->createIndex('tcg_user_keys_uk1', 'tcg_user_keys', 'api_key', true);
    }

    public function down()
    {
        $this->dropIndex('tcg_user_keys_uk1', 'tcg_user_keys');
        $this->dropForeignKey('tcg_user_keys_fk1', 'tcg_user_keys');
        $this->dropTable('tcg_user_keys');
    }
}