<?php

use Phpmig\Migration\Migration;

class AddDateInInvestigationHistory extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
            ALTER TABLE `investigation_history`
                ADD `date` DATE DEFAULT NULL AFTER `investigations`;
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
