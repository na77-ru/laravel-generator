<?php

use yii\db\Migration;
use mdm\admin\components\Configs;

class m180512_121555_group extends Migration {

    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        //$userTable = Configs::instance()->userTable;
        $db = Configs::userDb();

        if ($db->schema->getTableSchema('{{%group}}', true) === null) {
            
                       $this->createTable('{{%group}}', [
                'id' => $this->primaryKey(),
                'type' => $this->string()->notNull(),
                'name' => $this->string()->notNull(),
                'description' => $this->text()->notNull(), 
                    ], $tableOptions);
        
		$this->addCommentOnTable('{{%group}}', 'Группы пользователей');

        $this->addCommentOnColumn('{{%group}}', 'type', 'тип (проффесия)');
        $this->addCommentOnColumn('{{%group}}', 'name', 'название');
        $this->addCommentOnColumn('{{%group}}', 'description', 'описание');
        }

    }
    
        public function safeDown() {
        $this->dropTable('{{%group}}');
    }

}