<?php
use Migrations\AbstractMigration;

class CreateTables extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('users');
        $table->addColumn('first_name', 'string')
              ->addColumn('last_name', 'string')
              ->addColumn('gender', 'string', [
                'limit' => 1
              ])
              ->addColumn('email', 'string')
              ->addColumn('password', 'string')
              ->addColumn('created', 'datetime')
              ->addColumn('modified', 'datetime')
              ->addIndex(array('email'), array('unique' => true))
              ->create();

        $table = $this->table('posts');
        $table->addColumn('title', 'string')
              ->addColumn('body', 'text')
              ->addColumn('created', 'datetime')
              ->addColumn('modified', 'datetime')
              ->addIndex(array('title'), array('unique' => true))
              ->create();
    }
}
