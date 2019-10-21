<?php

use Phpmig\Migration\Migration;

class CreateInvestigationHistoryTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
            CREATE TABLE `investigation_history` (
                `id` INT NOT NULL AUTO_INCREMENT ,
                `patient` INT(11) DEFAULT NULL,
                `complaints` VARCHAR(255) DEFAULT NULL ,
                `examinations` VARCHAR(255) DEFAULT NULL ,
                `examination_descriptions` TEXT DEFAULT NULL ,
                `investigations` VARCHAR(255) DEFAULT NULL ,
                `created` DATETIME DEFAULT NULL ,
                `modified` DATETIME DEFAULT NULL ,
                `deleted` TINYINT(1) DEFAULT '0' ,
                PRIMARY KEY (`id`),
                KEY `fk_patient_idx` (`patient`),
                CONSTRAINT `fk_patient` FOREIGN KEY (`patient`) REFERENCES `patient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
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
