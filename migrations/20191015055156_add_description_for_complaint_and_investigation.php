<?php

use Phpmig\Migration\Migration;

class AddDescriptionForComplaintAndInvestigation extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
            ALTER TABLE `investigation_history`
                ADD `complaint_descriptions` TEXT DEFAULT NULL AFTER `complaints`;
                
            ALTER TABLE `investigation_history`
                ADD `investigation_descriptions` TEXT DEFAULT NULL AFTER `investigations`;
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
