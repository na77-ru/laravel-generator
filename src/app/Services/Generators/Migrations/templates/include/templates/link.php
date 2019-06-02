<?php if(isset($g_dbname) && !(bool)strpos($g_dbname, ".")){$g_dbname .= ".";} ?>
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
            ON <?= $g_dbname.$t_in ?>

            FOR EACH ROW BEGIN

            INSERT INTO <?= $g_dbname."log_".$t_in ?> SET <?=  $t_1 ?>_id = OLD.<?=  $t_1 ?>_id, <?=  $t_2 ?>_id = OLD.<?=  $t_2 ?>_id;

            END;
            ");        
        $this->execute(
            "       
            CREATE TRIGGER <?= $t_in ?>_insert AFTER INSERT
            ON <?= $g_dbname.$t_in ?>

            FOR EACH ROW BEGIN

            INSERT INTO <?= $g_dbname."log_".$t_in ?> SET <?=  $t_1 ?>_id = NEW.<?=  $t_1 ?>_id, <?=  $t_2 ?>_id = NEW.<?=  $t_2 ?>_id;

            END;
            ");