<?php

use Phpmig\Migration\Migration;

class CreatePatientTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
            CREATE TABLE `patient` (
                `id` INT NOT NULL AUTO_INCREMENT ,
                `name` VARCHAR(255) DEFAULT NULL ,
                `age` TINYINT(3) DEFAULT NULL ,
                `gender` CHAR(1) DEFAULT NULL ,
                `phone` VARCHAR(128) DEFAULT NULL ,
                `email` VARCHAR(255) DEFAULT NULL ,
                `address` VARCHAR(255) DEFAULT NULL ,
                `reference` VARCHAR(255) DEFAULT NULL ,
                `created` DATETIME DEFAULT NULL ,
                `modified` DATETIME DEFAULT NULL ,
                `deleted` TINYINT(1) DEFAULT '0' ,
                PRIMARY KEY (`id`)
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
