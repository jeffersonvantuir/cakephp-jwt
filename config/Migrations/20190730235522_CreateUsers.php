<?php
use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration
{

    public $autoId = false;

    /**
     * Migrate Up.
     */
    public function up()
    {

        $table = $this->table('users');

        $table->addColumn('id', 'uuid', [
            'default' => null,
            'null' => false
        ]);

        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false
        ]);

        $table->addColumn('email', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false
        ]);

        $table->addColumn('password', 'string', [
            'default' => null,
            'limit' => 255,
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

        $table->addIndex(['email'], ['unique' => true]);
        $table->addPrimaryKey('id');

        $table->create();

    }

    /**
     * Migrate Down.
     */
    public function down()
    {

        $table = $this->table('users');
        $table->drop();

    }

}
