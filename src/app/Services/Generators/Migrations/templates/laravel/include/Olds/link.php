use yii\db\Migration;

class <?= $className ?> extends Migration
{
    public function safeUp() {   

        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
            
        
            $this->createTable('{{%<?=  $t_2 ?>_in_<?=  $t_1 ?>}}', [
                '<?=  $t_1 ?>_id' => $this->integer()->notNull(),
                '<?=  $t_2 ?>_id' => $this->integer()->notNull(),
                    ], $tableOptions);
        

        $this->addPrimaryKey('pk-<?=  $t_2 ?>_in_<?=  $t_1 ?>', '{{%<?=  $t_2 ?>_in_<?=  $t_1 ?>}}', ['<?=  $t_1 ?>_id', '<?=  $t_2 ?>_id']);

        $this->createIndex('idx-<?=  $t_2 ?>_in_<?=  $t_1 ?>-<?=  $t_1 ?>_id', '{{%<?=  $t_2 ?>_in_<?=  $t_1 ?>}}', '<?=  $t_1 ?>_id');
        $this->createIndex('idx-<?=  $t_2 ?>_in_<?=  $t_1 ?>-<?=  $t_2 ?>_id', '{{%<?=  $t_2 ?>_in_<?=  $t_1 ?>}}', '<?=  $t_2 ?>_id');

        $this->addForeignKey('fk-<?=  $t_2 ?>_in_<?=  $t_1 ?>-<?=  $t_1 ?>_id', '{{%<?=  $t_2 ?>_in_<?=  $t_1 ?>}}', '<?=  $t_1 ?>_id', '{{%<?=  $t_1 ?>}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-<?=  $t_2 ?>_in_<?=  $t_1 ?>-<?=  $t_2 ?>_id', '{{%<?=  $t_2 ?>_in_<?=  $t_1 ?>}}', '<?=  $t_2 ?>_id', '{{%<?=  $t_2 ?>}}', 'id', 'CASCADE', 'RESTRICT');

        

   }


    public function safeDown() {
    
        $this->dropTable('{{%<?=  $t_2 ?>_in_<?=  $t_1 ?>}}');
    }

}

