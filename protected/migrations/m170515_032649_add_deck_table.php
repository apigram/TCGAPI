<?php

class m170515_032649_add_deck_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('tcg_decks', array(
            'id' => 'pk',
            'name' => 'varchar(30) NOT NULL',
            'user_id' => 'int NOT NULL',
            'date_modified' => 'datetime NOT NULL'
        ), 'ENGINE=InnoDB');
        $this->addForeignKey('tcg_decks_fk1', 'tcg_decks', 'user_id', 'tcg_users', 'id');

        // Add the table to represent the many-many relationship between cards and decks
        $this->createTable('tcg_deck_cards', array(
            'id' => 'pk',
            'deck_id' => 'int NOT NULL',
            'card_id' => 'int NOT NULL',
            'quantity' => 'int NOT NULL',
            'date_modified' => 'datetime NOT NULL'
        ), 'ENGINE=InnoDB');
        $this->addForeignKey('tcg_deck_cards_fk1', 'tcg_deck_cards', 'deck_id', 'tcg_decks', 'id', 'cascade');
        $this->addForeignKey('tcg_deck_cards_fk2', 'tcg_deck_cards', 'card_id', 'tcg_card', 'id', 'cascade');
        $this->createIndex('tcg_deck_cards_uk1', 'tcg_deck_cards', array('card_id', 'deck_id'), true);
    }

    public function down()
    {
        $this->dropForeignKey('tcg_deck_cards_fk2', 'tcg_deck_cards');
        $this->dropForeignKey('tcg_deck_cards_fk1', 'tcg_deck_cards');
        $this->dropIndex('tcg_deck_cards_uk1', 'tcg_deck_cards');

        $this->dropTable('tcg_deck_cards');

        $this->dropForeignKey('tcg_decks_fk1', 'tcg_decks');

        $this->dropTable('tcg_decks');
    }
}