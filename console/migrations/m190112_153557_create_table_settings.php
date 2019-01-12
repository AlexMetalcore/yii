<?php

use yii\db\Migration;

/**
 * Class m190112_153557_create_table_settings
 */
class m190112_153557_create_table_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            DROP TABLE IF EXISTS `settings`;
            CREATE TABLE `settings` (
            `key` varchar(50) NOT NULL,
            `name` varchar(250) NOT NULL,
            `value` text NOT NULL,
            `default_value` text,
            PRIMARY KEY (`key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
        );
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP TABLE `settings`;");
        return true;
    }

}
