<?php use app\helpers\CamelCase; global $className; ?>
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

<?php global $g_dbname;?>

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
      $t_in = 'link_'.$t_2.'_'.$t_1;
      require 'templates/link.php';
?>

      
}
    public function down() {

        Schema::dropIfExists('<?=  $t_1 ?>');
        Schema::dropIfExists('<?=  $t_2 ?>');
        Schema::dropIfExists('<?=  $t_in ?>');

    }

}
