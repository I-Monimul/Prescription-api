<?php

use Phpmig\Migration\Migration;

class AddFieldsForRefereeInPrescription extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
			$sql = "
				CREATE TABLE `referee` (
					`id` INT NOT NULL AUTO_INCREMENT ,
					`referee` VARCHAR(255) DEFAULT NULL ,
					`created` DATETIME DEFAULT NULL ,
					`modified` DATETIME DEFAULT NULL ,
					`deleted` TINYINT(1) DEFAULT '0' ,
					PRIMARY KEY (`id`)
				);

				ALTER TABLE `prescription_history`
					ADD `referee` TEXT DEFAULT NULL AFTER `next_visit`;
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
