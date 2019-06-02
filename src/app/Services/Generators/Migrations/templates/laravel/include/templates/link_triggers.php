<?php if(isset($g_dbname) && !(bool)strpos($g_dbname, ".")){$g_dbname .= ".";} ?>


Schema::connection('mysql2')->create('<?= $t_in ?>', function (Blueprint $table) {
                $table->integer('<?=  $t_1 ?>_id')->unsigned();
                $table->integer('<?=  $t_2 ?>_id')->unsigned();
                  

        $table->primary(['<?= $t_1 ?>_id', '<?= $t_2 ?>_id']);
        
        $table->index('<?= $t_1 ?>_id', 'idx-<?= $t_1 ?>-<?= $t_1 ?>_id');        
        $table->foreign('<?= $t_1 ?>_id')->references('id')->on('<?= $t_1 ?>')->onDelete('restrict');
        
        $table->index('<?= $t_2 ?>_id', 'idx-<?= $t_2 ?>-<?= $t_2 ?>_id');        
        $table->foreign('<?= $t_2 ?>_id')->references('id')->on('<?= $t_2 ?>')->onDelete('restrict');
        
        $table->engine = 'InnoDB';
    });


Schema::connection('mysql2')->create('<?= "log_" . $t_in ?>', function (Blueprint $table) {
                $table->integer('<?=  $t_1 ?>_id')->unsigned();
                $table->integer('<?=  $t_2 ?>_id')->unsigned();
        });




        DB::unprepared('
        CREATE TRIGGER <?= $t_in ?>_update BEFORE UPDATE
        ON `<?= $t_in ?>`
        FOR EACH ROW BEGIN
        INSERT INTO <?= $g_dbname."log_".$t_in ?> SET <?= $t_1 ?>_id = OLD.<?= $t_1 ?>_id, <?= $t_2 ?>_id = OLD.<?= $t_2 ?>_id;
        END
        ');

        DB::unprepared('
        CREATE TRIGGER <?= $t_in ?>_insert AFTER INSERT
        ON `<?= $t_in ?>`
        FOR EACH ROW BEGIN
        INSERT INTO <?= $g_dbname."log_".$t_in ?> SET <?= $t_1 ?>_id = NEW.<?= $t_1 ?>_id, <?= $t_2 ?>_id = NEW.<?= $t_2 ?>_id;
        END
        ');
