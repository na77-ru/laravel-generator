use yii\db\Migration;

class <?= $className ?> extends Migration{

    public function safeUp(){ 

        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        <?php require 'templates/one_table.php';?>

       
    }

    public function safeDown() {
    
        $this->execute("DROP TRIGGER `<?=  $t_1 ?>_update`");
        $this->execute("DROP TRIGGER `<?=  $t_1 ?>_insert`");     


        
        $this->dropTable('{{%<?=  $t_1 ?>}}');
       

        
        $this->dropTable('{{%<?=  "log_".$t_1 ?>}}');
            
    }

}