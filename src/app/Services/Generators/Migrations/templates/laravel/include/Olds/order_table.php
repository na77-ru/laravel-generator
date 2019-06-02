use yii\db\Migration;

class <?= $className ?> extends Migration
{
    public function safeUp()
    { 
    
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
        
     
    }


    public function safeDown() {
        $this->dropTable('{{%<?=  $t_1 ?>}}');        
    }

}