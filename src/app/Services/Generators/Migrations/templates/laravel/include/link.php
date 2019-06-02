<?php use Illuminate\Support\Str; ?>
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

<?php global $g_dbname, $className; ?>

class <?= str_replace("__", "", ucfirst(Str::camel($className))) ?> extends Migration{

    public function up() {   

        
        <?php 
              //$t_in =  $t_2.'___'.$t_1;
              $t_1 = lcfirst($t_1);
              $t_2 = lcfirst($t_2);
              $t_in =  'link_'.$t_2.'_'.$t_1;
              require 'templates/link.php';
        ?> 

   }


    public function down() {
    
          
        DB::unprepared('DROP TRIGGER `<?= $t_in ?>_update`');
        DB::unprepared('DROP TRIGGER `<?= $t_in ?>_insert`');
         
        Schema::dropIfExists('<?=  $t_in ?>');
        Schema::dropIfExists('<?=  "log_" . $t_in ?>');
              
        
    }

}

