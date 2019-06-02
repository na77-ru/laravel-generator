use yii\db\Migration;
<?php dd('gggggggggggg', $className) ?>
class <?= $className ?> extends Migration
{
    public function safeUp() {   

        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        <?php 
              $t_in =  $t_2.'_in_'.$t_1;
              require 'templates/link.php';
        ?> 

   }


    public function safeDown() {
    
     
        $this->execute("DROP TRIGGER `<?=  $t_in ?>_update`");
        $this->execute("DROP TRIGGER `<?=  $t_in ?>_insert`"); 

        
        $this->dropTable('{{%<?=  $t_in ?>}}');
       

        
        $this->dropTable('{{%<?=  "log_".$t_in ?>}} ');
        
    }

}

