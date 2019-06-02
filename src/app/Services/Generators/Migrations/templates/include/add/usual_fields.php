<?php
$arUsualFields = array(
"'id' => \$this->primaryKey(),",
"'user_id' => \$this->integer(),",
"'name' => \$this->string()->notNull()->defaultValue(''),",
"'comment' => \$this->text()->notNull()->defaultValue(''),",
"'description' => \$this->text()->notNull()->defaultValue(''),",
"'active' => \$this->smallInteger(1)->notNull()->defaultValue(0),",
);

$arUsualComments = array(
["\$this->addCommentOnColumn('{{%", "}}', 'name', 'название');"],
["\$this->addCommentOnColumn('{{%", "}}', 'comment', 'комментарий');"],
["\$this->addCommentOnColumn('{{%", "}}', 'description', 'описание');"],
);