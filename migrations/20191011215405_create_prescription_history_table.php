<?php

use Phpmig\Migration\Migration;

class CreatePrescriptionHistoryTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
            CREATE TABLE `prescription_history` (
                `id` INT NOT NULL AUTO_INCREMENT ,
                `patient` INT(11) DEFAULT NULL,
                `investigation_history` INT(11) DEFAULT NULL,
                `medicines` VARCHAR(255) DEFAULT NULL ,
                `times` VARCHAR(255) DEFAULT NULL ,
                `comments` VARCHAR(255) DEFAULT NULL ,
                `durations` VARCHAR(255) DEFAULT NULL ,
                `created` DATETIME DEFAULT NULL ,
                `modified` DATETIME DEFAULT NULL ,
                `deleted` TINYINT(1) DEFAULT '0' ,
                PRIMARY KEY (`id`),
                FOREIGN KEY (`patient`)
                    REFERENCES `patient`(`id`) 
                    ON DELETE NO ACTION
                    ON UPDATE NO ACTION,
                FOREIGN KEY (`investigation_history`)
                    REFERENCES `investigation_history`(`id`)
                    ON DELETE NO ACTION
                    ON UPDATE NO ACTION
            );
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
