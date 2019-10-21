<?php

use Phpmig\Migration\Migration;

class CreateExaminationTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
            CREATE TABLE `examination` (
                `id` INT NOT NULL AUTO_INCREMENT ,
                `examination` VARCHAR(255) DEFAULT NULL ,
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
