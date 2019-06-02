<?php use app\helpers\CamelCase; ?>
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;



class <?= str_replace("_", "", Illuminate\Support\Str::camel($className)) ?> extends Migration{

    public function up() {    
            <?php
              $t_1 = lcfirst($t_1);
              $t_2 = lcfirst($t_2);
              $t_in =  $t_2.'___'.$t_1;  
            ?>
<?php require 'templates/one_table.php';?>
            
<?php $t_11 = $t_1;
      $t_1 = $t_2;
      require 'templates/one_table.php';
?>
<?php 
        $t_2 = $t_1;
        $t_1 = $t_11;
      $t_in = $t_2 . "___" .  $t_1;
      require 'templates/link.php';
?>

      
}
    public function down() {
    
        DB::unprepared('DROP TRIGGER `<?=  $t_1 ?>_update`');
        DB::unprepared('DROP TRIGGER `<?=  $t_1 ?>_insert`');
        DB::unprepared('DROP TRIGGER `<?=  $t_2 ?>_update`');
        DB::unprepared('DROP TRIGGER `<?=  $t_2 ?>_insert`');
        DB::unprepared('DROP TRIGGER `<?=  $t_in ?>_update`');
        DB::unprepared('DROP TRIGGER `<?=  $t_in ?>_insert`');
         
        Schema::dropIfExists('<?=  $t_1 ?>');
        Schema::dropIfExists('<?=  $t_2 ?>');
        Schema::dropIfExists('<?=  $t_in ?>');
         
        Schema::dropIfExists('<?=  "log_" . $t_1 ?>');
        Schema::dropIfExists('<?=  "log_" . $t_2 ?>');
        Schema::dropIfExists('<?=  "log_" . $t_in ?>');


    }

}
