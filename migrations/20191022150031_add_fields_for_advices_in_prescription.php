<?php

use Phpmig\Migration\Migration;

class AddFieldsForAdvicesInPrescription extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
			$sql = "
				CREATE TABLE `advice` (
					`id` INT NOT NULL AUTO_INCREMENT ,
					`advice` VARCHAR(255) DEFAULT NULL ,
					`alternative` VARCHAR(255) DEFAULT NULL ,
					`created` DATETIME DEFAULT NULL ,
					`modified` DATETIME DEFAULT NULL ,
					`deleted` TINYINT(1) DEFAULT '0' ,
					PRIMARY KEY (`id`)
				);

				ALTER TABLE `prescription_history`
					ADD `advices` TEXT DEFAULT NULL AFTER `durations`;
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
