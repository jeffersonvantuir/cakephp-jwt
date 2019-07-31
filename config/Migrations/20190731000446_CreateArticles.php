<?php
use Migrations\AbstractMigration;

class CreateArticles extends AbstractMigration
{
    public $autoId = false;

    /**
     * Migrate Up.
     */
    public function up()
    {

        $table = $this->table('articles');

        $table->addColumn('id', 'uuid', [
            'default' => null,
            'null' => false
        ]);

        $table->addColumn('title', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false
        ]);

        $table->addColumn('content', 'text', [
            'default' => null,
            'null' => false
        ]);

        $table->addColumn('user_id', 'uuid', [
            'default' => null,
            'null' => false
        ]);

        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false
        ]);

        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false
        ]);

        $table->addPrimaryKey('id');

        $table->create();

    }

    /**
     * Migrate Down.
     */
    public function down()
    {

        $table = $this->table('articles');
        $table->drop();

    }

}
