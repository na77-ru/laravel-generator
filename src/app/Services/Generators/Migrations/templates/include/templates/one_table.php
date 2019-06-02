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
?>
$this->createTable('{{%<?= $t_1 ?>}}', [
           
<?php foreach($arUsualFields as $field): ?>
            <?= $field ?>

<?php endforeach;?>
<?php if($b_recursion_In):?>
<?php for($i = 0; $i < count($arParentIn["id"]); $i++):?>
            '<?= $arParentIn["id"][$i] ?>'  => $this->integer(),
<?php endfor;?>
<?php endif;?>
<?php if($b_recursion_Out):?>
<?php for($i = 0; $i < count($arParentOut["id"]); $i++):?>
            '<?= $arParentOut["id"][$i] ?>'  => $this->integer(),
<?php endfor;?>
<?php endif;?>

        ], $tableOptions);

<?php if($b_recursion_In):?>
<?php for($i = 0; $i < count($arParentIn["id"]); $i++): ?>
        $this->createIndex('idx-<?= $t_1 ?>-<?= $arParentIn["id"][$i] ?>', '{{%<?= $t_1 ?>}}', '<?= $arParentIn["id"][$i] ?>');
        $this->addForeignKey('fk-<?= $t_1 ?>-<?= $arParentIn["tab"][$i] ?>', '{{%<?= $t_1 ?>}}', '<?= $arParentIn["id"][$i] ?>', '{{%<?= $t_1 ?>}}', 'id', 'SET NULL', 'RESTRICT');
<?php endfor;?>
<?php endif;?>
<?php if($b_recursion_Out):?>
<?php for($i = 0; $i < count($arParentOut["id"]); $i++):?>
        $this->createIndex('idx-<?= $t_1 ?>-<?= $arParentOut["id"][$i] ?>', '{{%<?= $t_1 ?>}}', '<?= $arParentOut["id"][$i] ?>');
        $this->addForeignKey('fk-<?= $t_1 ?>-<?= $arParentOut["tab"][$i] ?>', '{{%<?= $t_1 ?>}}', '<?= $arParentOut["id"][$i] ?>', '{{%<?= $arParentOut["tab"][$i] ?>}}', 'id', 'SET NULL', 'RESTRICT');
<?php endfor;?>
<?php endif;?>
        $this->createIndex('idx-<?= $t_1 ?>-active', '{{%<?= $t_1 ?>}}', 'active');

            $this->addCommentOnTable('{{%<?= $t_1 ?>}}', '');
<?php foreach($arUsualComments as $comment): ?>
            <?= $comment[0] . $t_1 . $comment[1] ?>
           
<?php endforeach;?>            
                $this->createTable('{{%<?= "log_".$t_1 ?>}}', [

            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->defaultValue(0),
            'category_id' => $this->integer()->defaultValue(0),
            'date_up' => $this->integer()->defaultValue(0),
            'update' => $this->integer()->defaultValue(0),
            'create' => $this->integer()->defaultValue(0),
<?php foreach($arUsualFields as $field): ?>
            
            <?= $field ?>
<?php endforeach;?>  
        <?php if($b_recursion): ?>
            
            '<?= $parent_id ?>'  => $this->integer(),
        <?php endif;?>    
            
        ], $tableOptions);

            $this->addCommentOnTable('{{%<?= "log_".$t_1 ?>}}', '');
<?php foreach($arUsualComments as $comment): ?>
            <?= $comment[0] . "log_".$t_1 . $comment[1] ?>

<?php endforeach;?>

          $this->execute(
            "       
            CREATE TRIGGER <?= $t_1 ?>_update BEFORE UPDATE
            ON <?= $g_dbname.$t_1 ?>

            FOR EACH ROW BEGIN

            INSERT INTO <?= $g_dbname."log_".$t_1 ?> SET name = OLD.name, description = OLD.description;

            END;
            ");        
        $this->execute(
            "       
            CREATE TRIGGER <?= $t_1 ?>_insert AFTER INSERT
            ON <?= $g_dbname.$t_1 ?>

            FOR EACH ROW BEGIN

            INSERT INTO <?= $g_dbname."log_".$t_1 ?> SET name = NEW.name, description = NEW.description, user_id = NEW.user_id;

            END;
            ");     
    

