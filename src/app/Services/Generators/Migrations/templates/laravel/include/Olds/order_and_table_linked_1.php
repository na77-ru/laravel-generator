use yii\db\Migration;

class <?= $className ?> extends Migration
{
    public function safeUp() {   

            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
            
            $this->createTable('{{%<?=  $t_1 ?>}}', [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer()->notNull(),
                'model_id' => $this->integer()->notNull(),
                'material_id' => $this->integer()->notNull(),
                'accessory_id' => $this->integer()->notNull(),
                'change_id' => $this->integer()->notNull(),
                'lastname' => $this->string()->notNull(),
                'name' => $this->string()->notNull(),
                'midllename' => $this->string()->notNull(),
                'lastname_2' => $this->string()->defaultValue(''),
                'name_2' => $this->string()->defaultValue(''),
                'midllename_2' => $this->string()->defaultValue(''),
                'telephone' => $this->string()->notNull(),
                'telephone_2' => $this->string()->defaultValue(''),
                'address' => $this->string()->notNull(),
                'create_date' => $this->integer()->notNull(),
                'delivery_date' => $this->integer()->notNull(),
                'description' => $this->text()->defaultValue(''),
                    ], $tableOptions);
       
        $this->addCommentOnTable('{{%<?=  $t_1 ?>}}', 'заказы');

        $this->addCommentOnColumn('{{%<?=  $t_1 ?>}}', 'lastname', 'фамилия');
        $this->addCommentOnColumn('{{%<?=  $t_1 ?>}}', 'name', 'имя');
        $this->addCommentOnColumn('{{%<?=  $t_1 ?>}}', 'midllename', 'отчество');
        $this->addCommentOnColumn('{{%<?=  $t_1 ?>}}', 'lastname_2', '-фамилия-');
        $this->addCommentOnColumn('{{%<?=  $t_1 ?>}}', 'name_2', '-имя-');
        $this->addCommentOnColumn('{{%<?=  $t_1 ?>}}', 'midllename_2', '-отчество-');
        $this->addCommentOnColumn('{{%<?=  $t_1 ?>}}', 'telephone', 'телефон');
        $this->addCommentOnColumn('{{%<?=  $t_1 ?>}}', 'telephone_2', '-телефон-');
        $this->addCommentOnColumn('{{%<?=  $t_1 ?>}}', 'create_date', 'дата оформления');
        $this->addCommentOnColumn('{{%<?=  $t_1 ?>}}', 'delivery_date', 'дата доставки');
        $this->addCommentOnColumn('{{%<?=  $t_1 ?>}}', 'description', 'описание');
        
        
        
       
            $this->createTable('{{%<?=  $t_2 ?>}}', [
                'id' => $this->primaryKey(),
                'model_id' => $this->integer()->notNull(),
                'material_id' => $this->integer()->notNull(),
                'accessory_id' => $this->integer()->notNull(),
                'change_id' => $this->integer()->notNull(),
                'type' => $this->string()->notNull(),
                'weight' => $this->integer()->notNull(),
                'description' => $this->text()->defaultValue(''),
                    ], $tableOptions);
        
        $this->addCommentOnTable('{{%<?=  $t_2 ?>}}', 'изделия');

        $this->addCommentOnColumn('{{%<?=  $t_2 ?>}}', 'type', 'тип изделия');
        $this->addCommentOnColumn('{{%<?=  $t_2 ?>}}', 'weight', 'вес изделия');
        $this->addCommentOnColumn('{{%<?=  $t_2 ?>}}', 'description', 'описание');
 
        
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
        $this->dropTable('{{%<?=  $t_1 ?>}}');
        $this->dropTable('{{%<?=  $t_2 ?>}}');
    }

}