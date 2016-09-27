<?php

use yii\db\Migration;

/**
 * Handles the creation for table `as_adv_data`.
 */
class m160927_181258_create_as_adv_data_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('as_adv_data', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('as_adv_data');
    }
}
