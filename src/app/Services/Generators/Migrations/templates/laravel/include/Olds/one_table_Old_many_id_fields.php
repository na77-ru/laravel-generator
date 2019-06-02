use yii\db\Migration;

class <?= $className ?> extends Migration{

    public function safeUp(){ 

        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%<?= $t_1 ?>}}', [

            'id' => $this->primaryKey(),
<?php// foreach($arTablesName as $name): ?>
<?php// if($t_1 != $name): ?>
            <?//'= $name _id' => $this->integer()->defaultValue(0),?>
<?php// endif;?>
<?php// endforeach;?>
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

<?php //foreach($arTablesName as $name): ?>
<?php //if($t_1 != $name): ?>
            <?//$this->createIndex('idx-= $t_1 ?><?//-= $name _id', '{{%?><?//= $t_1 }}', '?><?//= $name _id');?>
<?php //endif;?>
<?php// endforeach;?>
       
    }

    public function safeDown() {
  
        $this->dropTable('{{%<?= $t_1 ?>}}');        
    }

}