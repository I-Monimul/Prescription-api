<?php

use Phpmig\Migration\Migration;

class AddFieldsForNextVisitInPrescription extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
			$sql = "
				CREATE TABLE `next_visit` (
					`id` INT NOT NULL AUTO_INCREMENT ,
					`next_visit` VARCHAR(255) DEFAULT NULL ,
					`alternative` VARCHAR(255) DEFAULT NULL ,
					`created` DATETIME DEFAULT NULL ,
					`modified` DATETIME DEFAULT NULL ,
					`deleted` TINYINT(1) DEFAULT '0' ,
					PRIMARY KEY (`id`)
				);

				ALTER TABLE `prescription_history`
					ADD `next_visit` TEXT DEFAULT NULL AFTER `advices`;
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
