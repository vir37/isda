<?php

use yii\db\Migration;

/**
 * Handles the creation for table `as_act_campaings`.
 */
class m160927_181213_create_as_act_campaings_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('as_act_campaings', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('as_act_campaings');
    }
}
