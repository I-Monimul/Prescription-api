<?php

use Phpmig\Migration\Migration;

class AddFieldsForDiagnosesInPrescription extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
                ALTER TABLE `prescription_history`
                        ADD `diagnoses` TEXT DEFAULT NULL AFTER `investigation_history`;
                        
                ALTER TABLE `prescription_history`
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
