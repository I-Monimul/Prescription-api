<?php

use Phpmig\Migration\Migration;

class CreateBaseTables extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
            CREATE TABLE `file` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `folder` varchar(512) DEFAULT NULL,
                `original_name` varchar(256) DEFAULT NULL,
                `name` varchar(512) DEFAULT NULL,
                `driver` varchar(45) DEFAULT NULL,
                `deleted` tinyint(1) DEFAULT '0',
                `created` datetime DEFAULT NULL,
                `modified` datetime DEFAULT NULL,
                PRIMARY KEY (`id`)
            );

        	CREATE TABLE `user` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(256) DEFAULT NULL,
                `surname` varchar(256) DEFAULT NULL,
                `email` varchar(256) DEFAULT NULL,
                `password` varchar(256) DEFAULT NULL,
                `role` char(2) DEFAULT NULL,
                `avatar` int(11) DEFAULT NULL,
                `deleted` tinyint(1) DEFAULT '0',
                `created` datetime DEFAULT NULL,
                `modified` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `fk_user_file_idx` (`avatar`),
                CONSTRAINT `fk_user_file` FOREIGN KEY (`avatar`) REFERENCES `file` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
        	);

        	CREATE TABLE `apikey` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `key` varchar(128) DEFAULT NULL,
                `user` int(11) DEFAULT NULL,
                `user_agent` varchar(256) DEFAULT NULL,
                `active` tinyint(1) DEFAULT '0',
                `created` datetime DEFAULT NULL,
                `modified` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `fk_apikey_user_idx` (`user`),
                CONSTRAINT `fk_apikey_user` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
        	);

        	CREATE TABLE `settings` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(128) DEFAULT NULL,
                `value` varchar(256) DEFAULT NULL,
                `display_name` varchar(128) DEFAULT NULL,
                `description` varchar(256) DEFAULT NULL,
                `modified` datetime DEFAULT NULL,
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
