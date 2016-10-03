<?php

use yii\db\Migration;
use yii\db\Exception as DbExc;

class m161003_112837_first_creation extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // 1. Рекламные продукты
        $this->createTable('as_products', [
            'id' => $this->primaryKey(),
            'description' => $this->text(),
            'inner_product' => $this->bigInteger()->comment('Ссылка на внутренний продукт'),
            'active' => $this->boolean()->comment('Флаг активности продукта'),
            'keywords' => $this->string()->comment('Ключевые слова товара'),
            'c_participation' => $this->integer()->comment('Количество участий в аукционах'),
            'c_bid_win' => $this->integer()->comment('Количество побед в аукционах'),
            'c_display' => $this->integer()->comment('Количество показов рекламных объявлений'),
        ], $tableOptions);
        $this->addCommentOnTable('as_products', 'Рекламные продукты');

        // 2. Настройки продуктов для участия в аукционе
        $this->createTable('as_product_bid_settings', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Наименование настройки')->notNull(),
            'description' => $this->text()->comment('Описание настройки')
        ], $tableOptions);
        $this->addCommentOnTable('as_product_bid_settings', 'Настройки фильтрации рекламных продуктов при участии в аукционе');

        // 3. Значения настроек участия в аукционе для конкретного продукта
        $this->createTable('as_product_bid_setting_values', [
            'product_id' => $this->bigInteger()->comment('Идентификатор продукта')->notNull(),
            'setting_id' => $this->bigInteger()->comment('Идентификатор настройки')->notNull(),
            'setting_value' => $this->string()->comment('Значение настройки')
        ], $tableOptions);
        $this->addPrimaryKey('as_product_bid_setting_values_pkey', 'as_product_bid_setting_values', ['product_id', 'setting_id']);
        $this->addForeignKey('product_id_fkey', 'as_product_bid_setting_values', ['product_id'],
                             'as_products', ['id'], 'CASCADE', 'CASCADE');
        $this->addForeignKey('setting_id_fkey', 'as_product_bid_setting_values', ['setting_id'],
                             'as_product_bid_settings', ['id'], 'CASCADE', 'CASCADE');
        $this->addCommentOnTable('as_product_bid_setting_values', 'Значения аукционных настроек рекламных продуктов');

        // 4. Активные рекламные кампании
        $this->createTable('as_act_campaigns', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Наименование кампании'),
            'start_time' => $this->dateTime()->comment('Начало кампании'),
            'geo_target' => $this->string()->comment('Информация о гео-направленности кампании'),
            'keywords' => $this->string()->comment('Ключевые слова кампании'),
        ], $tableOptions);
        $this->addCommentOnTable('as_act_campaigns', 'Активные рекламные кампании');

        // 5. Завершенные рекламные кампании
        $this->createTable('as_complete_campaigns', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Наименование кампании'),
            'start_time' => $this->dateTime()->comment('Начало кампании'),
            'finish_time' => $this->dateTime()->comment('Окончание кампании'),
            'geo_target' => $this->string()->comment('Информация о гео-направленности кампании'),
            'keywords' => $this->string()->comment('Ключевые слова кампании'),
        ], $tableOptions);
        $this->addCommentOnTable('as_complete_campaigns', 'Завершенные рекламные кампании');

        // 6. Рекламные продукты, участвующие в рекламной кампании
        $this->createTable('as_campaign_products', [
            'id' => $this->primaryKey(),
            'campaign_id' => $this->bigInteger()->comment('Идентификатор кампании')->notNull(),
            'product_id' => $this->bigInteger()->comment('Идентификатор продукта')->notNull(),
        ], $tableOptions);
        $this->addForeignKey('product_id_fkey', 'as_campaign_products', ['product_id'],
                             'as_products', ['id'], 'CASCADE', 'CASCADE');
        $this->addCommentOnTable('as_campaign_products', 'Продукты, участвующие в кампании');

        // 7. Данные для рекламного объявления
        $this->createTable('as_adv_data', [
            'id' => $this->primaryKey(),
        ], $tableOptions);
        $this->addCommentOnTable('as_adv_data', 'Данные для рекламного объявления');

    }

    public function down()
    {
        $tables = [
            'as_adv_data',
            'as_campaign_products',
            'as_complete_campaigns',
            'as_act_campaigns',
            'as_product_bid_setting_values',
            'as_product_bid_settings',
            'as_products',
        ];

        foreach ($tables as $table) {
            try {
                $this->dropTable($table);
            } catch(DbExc $e){
                echo $e->errorInfo[2].'\n';
            }
        }
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
