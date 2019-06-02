use yii\db\Migration;

class <?= $className ?> extends Migration
{
    public function safeUp() {   

            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
            
                $this->createTable('{{%<?= $t_1 ?>}}', [

            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->defaultValue(0),
            'category_id' => $this->integer()->defaultValue(0),
<? foreach($arUsualFields as $field): ?>
            <?= $field ?>

<? endforeach;?>               
        ], $tableOptions);

            $this->addCommentOnTable('{{%<?= $t_1 ?>}}', '');
<? foreach($arUsualComments as $comment): ?>
            <?= $comment[0] . $t_1 . $comment[1] ?>

<? endforeach;?>

       
    



          $this->createTable('{{%<?= $t_2 ?>}}', [

            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->defaultValue(0),
            'category_id' => $this->integer()->defaultValue(0),
<? foreach($arUsualFields as $field): ?>
            <?= $field ?>

<? endforeach;?>               
        ], $tableOptions);

            $this->addCommentOnTable('{{%<?= $t_2 ?>}}', '');
<? foreach($arUsualComments as $comment): ?>
            <?= $comment[0] . $t_2 . $comment[1] ?>

<? endforeach;?>

    

          
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