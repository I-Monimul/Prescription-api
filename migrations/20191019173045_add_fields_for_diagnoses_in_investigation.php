<?php

use Phpmig\Migration\Migration;

class AddFieldsForDiagnosesInInvestigation extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
				$sql = "
						CREATE TABLE `diagnosis` (
								`id` INT NOT NULL AUTO_INCREMENT ,
								`diagnosis` VARCHAR(255) DEFAULT NULL ,
                `created` DATETIME DEFAULT NULL ,
                `modified` DATETIME DEFAULT NULL ,
                `deleted` TINYINT(1) DEFAULT '0' ,
                PRIMARY KEY (`id`)
            );

						ALTER TABLE `investigation_history`
								ADD `diagnoses` TEXT DEFAULT NULL AFTER `investigation_descriptions`;
								
						ALTER TABLE `investigation_history`
								ADD `diagnosis_descriptions` TEXT DEFAULT NULL AFTER `diagnoses`;
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
