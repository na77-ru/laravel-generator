use yii\db\Migration;

class <?= $className ?> extends Migration{

    public function safeUp(){ 

        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%<?= $t_1 ?>}}', [

            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->defaultValue(0),
            'category_id' => $this->integer()->defaultValue(0),
<?php foreach($arUsualFields as $field): ?>
            <?= $field ?>

<?php endforeach;?>               
        ], $tableOptions);

            $this->addCommentOnTable('{{%<?= $t_1 ?>}}', '');
<?php foreach($arUsualComments as $comment): ?>
            <?= $comment[0] . $t_1 . $comment[1] ?>

<?php endforeach;?>

       
    }

    public function safeDown() {
  
        $this->dropTable('{{%<?= $t_1 ?>}}');        
    }

}