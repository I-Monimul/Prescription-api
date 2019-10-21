<?php

use Phpmig\Migration\Migration;

class AddDateInPrescriptionHistory extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
            ALTER TABLE `prescription_history`
                ADD `date` DATE DEFAULT NULL AFTER `durations`;
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
