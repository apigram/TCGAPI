<?php

class m170511_233908_add_user_card_link extends CDbMigration
{
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        // As users can now be registered, put a unique index on the username column to ensure that usernames are unique.
        $this->createIndex('tcg_users_uk1', 'tcg_users', 'username', true);

        // Add a user_id column to tcg_cards to associate a card to a user.
        $this->addColumn('tcg_card', 'user_id','int NOT NULL');
        $this->addForeignKey('tcg_card_fk1', 'tcg_card', 'user_id', 'tcg_users','id');

        // update all pre-existing cards to point to the admin user.
        $this->update('tcg_card', array('user_id' => 1));
    }

    public function safeDown()
    {
        // drop the foreign key, the new column and the new index.
        $this->dropForeignKey('tcg_card_fk1', 'tcg_card');
        $this->dropColumn('tcg_card', 'user_id');
        $this->dropIndex('tcg_users_uk1', 'tcg_users');
    }
}