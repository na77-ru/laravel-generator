<?php if(isset($g_dbname) && !(bool)strpos($g_dbname, ".")){$g_dbname .= ".";} ?>


Schema::connection('mysql2')->create('<?= $t_in ?>', function (Blueprint $table) {
                $table->bigInteger('<?=  $t_1 ?>_id')->unsigned();
                $table->bigInteger('<?=  $t_2 ?>_id')->unsigned();
                $table->timestamps();
                $table->softDeletes();

        $table->primary(['<?= $t_1 ?>_id', '<?= $t_2 ?>_id']);
        
        $table->index('<?= $t_1 ?>_id', 'idx-<?= $t_1 ?>-<?= $t_1 ?>_id');        
        $table->foreign('<?= $t_1 ?>_id')->references('id')->on('<?= $t_1 ?>')->onDelete('restrict');
        
        $table->index('<?= $t_2 ?>_id', 'idx-<?= $t_2 ?>-<?= $t_2 ?>_id');        
        $table->foreign('<?= $t_2 ?>_id')->references('id')->on('<?= $t_2 ?>')->onDelete('restrict');
        
        $table->engine = 'InnoDB';
    });



