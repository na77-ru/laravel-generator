<?php 
//exit("\n t1 = ".$t_1."\n");//11
if(isset($g_dbname) && !(bool)strpos($g_dbname, ".")){$g_dbname .= ".";} 
$b_recursion_In = false;
$b_recursion_Out = false;
    if(strpos($t_1, "_fIn_"))  {
        $arIn = explode("_fIn_", $t_1);
        //unset($arIn[0]);
        //$arIn = array_values($arIn);
        $arOut = [];
        //exit(print_r($arIn, true));//11
        for($i = 0; $i < count($arIn); $i++){
            if(strpos($arIn[$i], "_fOut_"))  {
                $arOutSmall = explode("_fOut_", $arIn[$i]) ;
                //if($i === 0){$arIn[0] = $arOutSmall[0];}
                $arIn[$i] = $arOutSmall[0];
                 unset($arOutSmall[0]);
                $arOut = array_merge( $arOut, $arOutSmall );
                $b_recursion_Out = true;
            }
        }
        $t_1 = $arIn[0];
        for ($ii = 1; $ii < count($arIn); $ii++) {
//                $arParentIn["tab"][$ii-1] = $t_1."_".$arIn[$ii];
//                $arParentIn["id"][$ii-1] = $t_1."_".$arIn[$ii]."_id";
                $arParentIn["tab"][$ii-1] = $arIn[$ii];
                $arParentIn["id"][$ii-1] = $arIn[$ii]."_id";
        }
        for ($i = 0; $i < count($arOut); $i++) {
                $arParentOut["tab"][$i] = $arOut[$i];
                $arParentOut["id"][$i] = $arOut[$i]."_id";
        }
       // exit(print_r($arParentOut, true));//11
        $b_recursion_In = true;
    }elseif (strpos($t_1, "_fOut_")) {
         $arOut = explode("_fOut_", $t_1);
        //unset($arIn[0]);
        //$arIn = array_values($arIn);
        
        $t_1 = $arOut[0];
        for ($i = 1; $i < count($arOut); $i++) {
                $arParentOut["tab"][$i-1] = $arOut[$i];
                $arParentOut["id"][$i-1] = $arOut[$i]."_id";
        }

        $b_recursion_Out = true;
    }
   $t_1 = lcfirst($t_1);
?>
Schema::connection('mysql2')->create('<?= $t_1 ?>', function (Blueprint $table) {
           
<?php foreach($arUsualFields as $field): ?>
            <?= $field ?>

<?php endforeach;?>
<?php if($b_recursion_In):?>
<?php for($i = 0; $i < count($arParentIn["id"]); $i++):?>
            $table->integer('<?= $arParentIn["id"][$i] ?>')->unsigned()->nullable();                    
<?php endfor;?>
<?php endif;?>
<?php if($b_recursion_Out):?>
<?php for($i = 0; $i < count($arParentOut["id"]); $i++):?>
            $table->integer('<?= $arParentOut["id"][$i] ?>')->unsigned()->nullable();
<?php endfor;?>
<?php endif;?>

       

<?php if($b_recursion_In):?>
<?php for($i = 0; $i < count($arParentIn["id"]); $i++): ?>
        $table->index('<?= $arParentIn["id"][$i] ?>', 'idx-<?= $t_1 ?>-<?= $arParentIn["id"][$i] ?>');        
        $table->foreign('<?= $arParentIn["id"][$i] ?>')->references('id')->on('<?= $t_1 ?>')->onDelete('set null');
<?php endfor;?>
<?php endif;?>
<?php if($b_recursion_Out):?>
<?php for($i = 0; $i < count($arParentOut["id"]); $i++):?>
        $table->index('<?= $arParentOut["id"][$i] ?>', 'idx-<?= $t_1 ?>-<?= $arParentOut["id"][$i] ?>');        
        $table->foreign('<?= $arParentOut["id"][$i] ?>')->references('id')->on('<?= $arParentOut["tab"][$i] ?>')->onDelete('set null');
<?php endfor;?>
<?php endif;?>
        
        $table->index('active', 'idx-<?= $t_1 ?>-active');
        
        $table->engine = 'InnoDB';

 });


Schema::connection('mysql2')->create('<?= "log_" . $t_1 ?>', function (Blueprint $table) {

<?php foreach($arUsualFields as $field): ?>
            <?= $field ?>

<?php endforeach;?>
            $table->engine = 'InnoDB';
    });


        DB::unprepared('
        CREATE TRIGGER <?= $t_1 ?>_update BEFORE UPDATE
        ON `<?= $t_1 ?>`
        FOR EACH ROW BEGIN
        INSERT INTO <?= $g_dbname."log_".$t_1 ?> SET name = OLD.name, description = OLD.description;
        END
        ');

        DB::unprepared('
        CREATE TRIGGER <?= $t_1 ?>_insert AFTER INSERT
        ON `<?= $t_1 ?>`
        FOR EACH ROW BEGIN
        INSERT INTO <?= $g_dbname."log_".$t_1 ?> SET name = NEW.name, description = NEW.description, user_id = NEW.user_id;
        END
        ');