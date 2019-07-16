use yii\db\Migration;
<?global $g_dbname;?>
class <?= $className ?> extends Migration
{
    public function safeUp() {   

            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
            
<?php require 'templates/one_table.php';?>
            
<?php $t_11 = $t_1;
      $t_1 = $t_2;
      require 'templates/one_table.php';
?>
<?php 
        $t_2 = $t_1;
        $t_1 = $t_11;
      $t_in = $t_2 . "_in_" .  $t_1;
      require 'templates/pivot.php';
?>

      
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
