<?php

use Phpmig\Migration\Migration;

class AddAlternativeFields extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
			$sql = "
				ALTER TABLE `time`
					ADD `alternative` VARCHAR(255) DEFAULT NULL AFTER `time`;
					
				ALTER TABLE `comment`
					ADD `alternative` VARCHAR(255) DEFAULT NULL AFTER `comment`;
				
				ALTER TABLE `duration`
					ADD `alternative` VARCHAR(255) DEFAULT NULL AFTER `duration`;
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
