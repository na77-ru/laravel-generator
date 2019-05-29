<?php

$name = app()->data->get('name');
$arr = app()->data->get('arr');

?>

@if (isset($name))
{{ $name }}

@endif

<?php

foreach ($arr as $item) {
    echo $item;
}

?>
$this->createTable('{{%<?= $name ?>}}', [
