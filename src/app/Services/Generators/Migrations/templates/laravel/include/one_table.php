<?php use app\helpers\CamelCase; ?>
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

<?php global $g_dbname;?>

<?php 
//$arClassName = explode("_", $className); 
//$className = $arClassName[2];
?>
class <?= Illuminate\Support\Str::camel($className) ?> extends Migration{

    public function up(){ 



        <?php 
        $t_1 = lcfirst($t_1); 
        require 'templates/one_table.php';
        ?>

       
    }

    public function down() {     

        DB::unprepared('DROP TRIGGER `<?=  $t_1 ?>_update`');
        DB::unprepared('DROP TRIGGER `<?=  $t_1 ?>_insert`');
         
        Schema::dropIfExists('<?=  $t_1 ?>');
            
    }

}
