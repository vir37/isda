<?php

use yii\db\Migration;

/**
 * Handles the creation for table `as_complete_campaings`.
 */
class m160927_181229_create_as_complete_campaings_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('as_complete_campaings', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('as_complete_campaings');
    }
}
