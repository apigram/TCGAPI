<?php

class m170510_033438_create_user_table extends CDbMigration
{
	public function up()
	{
		
	}

	public function down()
	{
		
	}

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		$this->createTable('tcg_users', array(
				'id' => 'pk',
				'username' => 'varchar(20) NOT NULL',
				'password' => 'text NOT NULL',
		));
		
		$this->insert('tcg_users', array(
			'username' => 'apigram',
			'password' => password_hash('fivium12', PASSWORD_DEFAULT),
		));
	}

	public function safeDown()
	{
		$this->dropTable('tcg_users');
	}
}