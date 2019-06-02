<?php

$arUsualFields = array(
    "\$table->bigIncrements('id');",
    "\$table->bigInteger('user_id')->unsigned()->nullable()->comment('id создателя, редактора');",
    "\$table->string('name')->default('')->comment('название');",
    "\$table->string('comment')->default('')->comment('комментарий');",
    "\$table->string('description')->default('')->comment('описание');",
    "\$table->smallInteger('active')->default(0);",
    "\$table->timestamp('published_at')->nullable();",
    "\$table->timestamps();",
    "\$table->softDeletes();"


);

