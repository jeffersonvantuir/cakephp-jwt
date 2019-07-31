<?php
use Migrations\AbstractMigration;

class AddForeignKeyArticlesUsers extends AbstractMigration
{

    public function up()
    {
        $this->table('articles')
            ->addForeignKey('user_id', 'users', 'id')
            ->save();
    }

    public function down()
    {
        $this->table('articles')
            ->dropForeignKey('user_id');
    }

}
