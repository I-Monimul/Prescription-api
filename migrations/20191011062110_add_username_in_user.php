<?php

use Phpmig\Migration\Migration;

class AddUsernameInUser extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
            ALTER TABLE `user`
                ADD `username` VARCHAR(128) DEFAULT NULL AFTER `surname`;
            ";
        $container = $this->getContainer();
        $container['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        /*
        $sql = "

            ";
        $container = $this->getContainer();
        $container['db']->query($sql);
        //*/
    }
}
