<?php

use Phpmig\Migration\Migration;

class CreateInvestigationTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
            CREATE TABLE `investigation` (
                `id` INT NOT NULL AUTO_INCREMENT ,
                `investigation` VARCHAR(255) DEFAULT NULL ,
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
