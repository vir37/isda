<?php

use yii\db\Migration;

/**
 * Handles the creation for table `as_products`.
 */
class m160927_181109_create_as_products_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('as_products', [
            'id' => $this->primaryKey(),
            'inner_product' => $this->bigInteger(),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('as_products');
    }
}
