<?php

class m170510_015521_create_card_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('tcg_card', array(
			'id' => 'pk',
			'image_data' => 'mediumblob NOT NULL',
			'image_type' => 'varchar(20) NOT NULL',
			'name' => 'varchar(100) NOT NULL',
			'notes' => 'text',
			'quantity' => 'int NOT NULL',
			'date_modified' => 'datetime NOT NULL'
		), 'ENGINE=InnoDB');
	}

	public function down()
	{
		$this->dropTable('tcg_card');
	}

}