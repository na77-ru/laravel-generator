<?php

//global $laravel, $className;
//dd($laravel, $className);
//dd($laravel, $className);
$sLaravelAddPath = "";
$nTabName = 2;
if($laravel){
    $sLaravelAddPath = "laravel/";
    $nTabName = 0;
}
require $sLaravelAddPath . 'include/add/tables_name.php';
require $sLaravelAddPath . 'include/add/usual_fields.php';

echo "<?php\n";

//exit($className);//11    m180829_124140_post_comment
//$className = m180829_124140_post_comment или m180829_124140_post  одна таблица
//или $className = m180829_124140_post__comment две таблицы со связующей итого три
//или $className = m180829_124140_post___comment или m180829_124140_order___furniture_model одна связующая 
// $t_1 - название первой таблицы
// $t_2 - название второй таблицы
//_f_parent добавляет inner foreign key (parent_id to id)

$ar = explode('_', $className);


if ($ar[$nTabName] === 'help') { 
            echo "\n\nhelp !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n\n";
            echo "wright  yii migrate/create post or furniture_model - \n- one table\n\n";
            echo "wright  yii migrate/create post_fIn_parent  - \n- one table with Inner foreign key (post.post_parent_id to post.id)\n\n";
            echo "wright  yii migrate/create post__user - \n- two table post, user linked by post_in_user (\n\n";
            echo "wright  yii migrate/create post_fIn_parent__user - \n- two table post, user linked by post_in_user with Inner foreign key (post.post_parent_id to post.id ) (\n\n";
            echo "wright  yii migrate/create post__user_fOut_order - \n- two table post, user linked by post_in_user with Outer foreign key (user.order_id to order.id) (\n\n";
            echo "wright  yii migrate/create post___user - \n- one link table post_in_user\n\n";
            exit ("help !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n\n");
}
if (strpos($className, '___')) { 
    $ar = explode('___', $className);
    $arr = explode('_', $ar[0]);
    $t_2 = $arr[$nTabName];
    for($i = 3; $i < count($arr); $i++){
        $t_2 .= '_'.$arr[$i];
    }
    $t_1 = $ar[1];
    require $sLaravelAddPath . 'include/link.php';
}elseif (strpos($className, '__')) {
    $ar = explode('__', $className);
    $arr = explode('_', $ar[0]);
    $t_1 = $arr[$nTabName];
    for($i = 3; $i < count($arr); $i++){
        $t_1 .= '_'.$arr[$i];
    }
    $t_2 = $ar[1];
    require $sLaravelAddPath . 'include/two_table_linked.php';
}else {
    $ar = explode('_', $className);
    //var_dump($ar);//11
    $t_1 = $ar[$nTabName];
    for($i = $nTabName+1; $i < count($ar); $i++){
        $t_1 .= '_'.$ar[$i];
    }

  require $sLaravelAddPath . 'include/one_table.php';
}

