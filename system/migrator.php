<?php

foreach($models as $model) {

    $table = isset($model::$table) ? $model::$table : false;;

    if($table && isset($model::$drop)) {
        $db->query("drop table $model::$table");
    }

    if(!$table || tableExists($model::$table)) {
        continue;
    }

    $fields = $model::$fields;

    $prepfields = [];
    foreach($fields as $name => $value) {
        $pvalue = explode(':', $value);
        $adds = isset($pvalue[2]) ? $pvalue[2] : false;

        $field = (object) [
            'name' => $name,
            'type' => $pvalue[0],
            'key' => preg_match('/key/', $adds),
            'ai' => preg_match('/ai/', $adds),
            'def' => isset($pvalue[1]) ? $pvalue[1] : ''
        ];

        array_push($prepfields, $field);
    }

    $sql = "CREATE TABLE `" . $table . "` (\n";
    foreach($prepfields as $i => $field) {

        $sql .= "`" . $field->name . "` "
            . $field->type
            . (preg_match('/id$/', $field->name) ? " NOT NULL " : '')
            . ($field->def != '' ? " DEFAULT " . $field->def : "")
            . ($i < count($prepfields) - 1 ? ',' : '')
            . "\n";
    }
    $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf16;\n\n";
    $db->query($sql);
    echo $sql . "<br>";

    foreach($prepfields as $field) {
        if($field->key) {
            $sql = "ALTER TABLE `" . $table . "` ADD PRIMARY KEY (`" . $field->name . "`);\n\n";
            $db->query($sql);
        }
        if($field->ai) {
            $sql = "ALTER TABLE `" . $table . "` MODIFY `" . $field->name . "` " . $field->type . " NOT NULL AUTO_INCREMENT;\n\n";
            $db->query($sql);
        }
    }
}

$migrated = false;
$migrDir = 'data/migrations/';
$sqlFiles = scandir($migrDir);
$sqlFiles = delDots($sqlFiles);
foreach($sqlFiles as $file) {
    $table = preg_replace('/\.\w+$/', '', $file);
    if(tableExists($table)) {
        continue;
    }
    $sql = file_get_contents($migrDir . $file);
    $db->multi_query($sql);
    while($db->more_results()) {
        $db->next_result();
    }
    $migrated = true;
}

if($migrated) {
    redirect(ROOT);
}