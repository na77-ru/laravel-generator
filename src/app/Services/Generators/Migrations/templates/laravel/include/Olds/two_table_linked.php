use yii\db\Migration;
<?global $g_dbname;?>
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
                $this->createTable('{{%<?= "log_".$t_1 ?>}}', [

            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->defaultValue(0),
            'category_id' => $this->integer()->defaultValue(0),
            'date_up' => $this->integer()->defaultValue(0),
            'update' => $this->integer()->defaultValue(0),
            'create' => $this->integer()->defaultValue(0),
<? foreach($arUsualFields as $field): ?>
            <?= $field ?>

<? endforeach;?>               
        ], $tableOptions);

            $this->addCommentOnTable('{{%<?= "log_".$t_1 ?>}}', '');
<? foreach($arUsualComments as $comment): ?>
            <?= $comment[0] . "log_".$t_1 . $comment[1] ?>

<? endforeach;?>

          $this->execute(
            "       
            CREATE TRIGGER <?= $t_1 ?>_update BEFORE UPDATE
            ON <?= $g_dbname.".".$t_1 ?>

            FOR EACH ROW BEGIN

            INSERT INTO <?= $g_dbname.".log_".$t_1 ?> SET name = OLD.name, description = OLD.description;

            END;
            ");        
        $this->execute(
            "       
            CREATE TRIGGER <?= $t_1 ?>_insert AFTER INSERT
            ON <?= $g_dbname.".".$t_1 ?>

            FOR EACH ROW BEGIN

            INSERT INTO <?= $g_dbname.".log_".$t_1 ?> SET name = NEW.name, description = NEW.description, user_id = NEW.user_id;

            END;
            ");     
    



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


          $this->createTable('{{%<?= "log_".$t_2 ?>}}', [

            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->defaultValue(0),
            'category_id' => $this->integer()->defaultValue(0),
<? foreach($arUsualFields as $field): ?>
            <?= $field ?>

<? endforeach;?>               
        ], $tableOptions);

            $this->addCommentOnTable('{{%<?= "log_".$t_2 ?>}}', '');
<? foreach($arUsualComments as $comment): ?>
            <?= $comment[0] . "log_".$t_1 . $comment[1] ?>

<? endforeach;?>

          $this->execute(
            "       
            CREATE TRIGGER <?= $t_2 ?>_update BEFORE UPDATE
            ON <?= $g_dbname.".".$t_2 ?>

            FOR EACH ROW BEGIN

            INSERT INTO <?= $g_dbname.".log_".$t_2 ?> SET name = OLD.name, description = OLD.description;

            END;
            ");        
        $this->execute(
            "       
            CREATE TRIGGER <?= $t_2 ?>_insert AFTER INSERT
            ON <?= $g_dbname.".".$t_2 ?>

            FOR EACH ROW BEGIN

            INSERT INTO <?= $g_dbname.".log_".$t_2 ?> SET name = NEW.name, description = NEW.description, user_id = NEW.user_id;

            END;
            "); 
    

          <? $t_in = $t_2 . "_in_" .  $t_1; ?>

            $this->createTable('{{%<?= $t_in ?>}}', [
                '<?=  $t_1 ?>_id' => $this->integer()->notNull(),
                '<?=  $t_2 ?>_id' => $this->integer()->notNull(),
                    ], $tableOptions);    

          
            $this->createTable('{{%<?=  "log_".$t_in ?>}}', [
                '<?=  $t_1 ?>_id' => $this->integer()->notNull(),
                '<?=  $t_2 ?>_id' => $this->integer()->notNull(),
                    ], $tableOptions);
        

        $this->addPrimaryKey('pk-<?=  $t_2 ?>_in_<?=  $t_1 ?>', '{{%<?=  $t_2 ?>_in_<?=  $t_1 ?>}}', ['<?=  $t_1 ?>_id', '<?=  $t_2 ?>_id']);

        $this->createIndex('idx-<?=  $t_2 ?>_in_<?=  $t_1 ?>-<?=  $t_1 ?>_id', '{{%<?=  $t_2 ?>_in_<?=  $t_1 ?>}}', '<?=  $t_1 ?>_id');
        $this->createIndex('idx-<?=  $t_2 ?>_in_<?=  $t_1 ?>-<?=  $t_2 ?>_id', '{{%<?=  $t_2 ?>_in_<?=  $t_1 ?>}}', '<?=  $t_2 ?>_id');

        $this->addForeignKey('fk-<?=  $t_2 ?>_in_<?=  $t_1 ?>-<?=  $t_1 ?>_id', '{{%<?=  $t_2 ?>_in_<?=  $t_1 ?>}}', '<?=  $t_1 ?>_id', '{{%<?=  $t_1 ?>}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-<?=  $t_2 ?>_in_<?=  $t_1 ?>-<?=  $t_2 ?>_id', '{{%<?=  $t_2 ?>_in_<?=  $t_1 ?>}}', '<?=  $t_2 ?>_id', '{{%<?=  $t_2 ?>}}', 'id', 'CASCADE', 'RESTRICT');

        

   
         $this->execute(
            "       
            CREATE TRIGGER <?= $t_in ?>_update BEFORE UPDATE
            ON <?= $g_dbname.".".$t_in ?>

            FOR EACH ROW BEGIN

            INSERT INTO <?= $g_dbname.".log_".$t_in ?> SET <?=  $t_1 ?>_id = OLD.<?=  $t_1 ?>_id, <?=  $t_2 ?>_id = OLD.<?=  $t_2 ?>_id;

            END;
            ");        
        $this->execute(
            "       
            CREATE TRIGGER <?= $t_in ?>_insert AFTER INSERT
            ON <?= $g_dbname.".".$t_in ?>

            FOR EACH ROW BEGIN

            INSERT INTO <?= $g_dbname.".log_".$t_in ?> SET <?=  $t_1 ?>_id = NEW.<?=  $t_1 ?>_id, <?=  $t_2 ?>_id = NEW.<?=  $t_2 ?>_id;

            END;
            ");
}
    public function safeDown() {
    
        $this->execute("DROP TRIGGER `<?=  $t_1 ?>_update`");
        $this->execute("DROP TRIGGER `<?=  $t_1 ?>_insert`");     
        $this->execute("DROP TRIGGER `<?=  $t_2 ?>_update`");
        $this->execute("DROP TRIGGER `<?=  $t_2 ?>_insert`");     
        $this->execute("DROP TRIGGER `<?=  $t_in ?>_update`");
        $this->execute("DROP TRIGGER `<?=  $t_in ?>_insert`"); 

        $this->dropTable('{{%<?=  $t_2 ?>_in_<?=  $t_1 ?>}}');
        $this->dropTable('{{%<?=  $t_1 ?>}}');
        $this->dropTable('{{%<?=  $t_2 ?>}}');

        $this->dropTable('{{%<?=  "log_".$t_2 ?>_in_<?=  $t_1 ?>}}');
        $this->dropTable('{{%<?=  "log_".$t_1 ?>}}');
        $this->dropTable('{{%<?=  "log_".$t_2 ?>}}');
    }

}