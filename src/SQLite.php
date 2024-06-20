<?php

namespace GooGee\Entity;

class SQLite extends DataBase
{

    protected function getSchema(string $database)
    {
        $tables = [];
        $list = \DB::select("SELECT name from sqlite_master WHERE type = 'table';");
        foreach ($list as $item) {
            $tables[] = $this->getTable($item->name);
        }
        return $tables;
    }

    protected function getTable(string $name)
    {
        $indexes = \DB::select("PRAGMA index_list('{$name}');");
        foreach ($indexes as &$index) {
            $index->columns = \DB::select("PRAGMA index_info('{$index->name}');");
        }
        $table = [
            'name' => $name,
            'fields' => \DB::select("PRAGMA table_info('{$name}');"),
            'indexes' => $indexes,
        ];
        return $table;
    }

}
