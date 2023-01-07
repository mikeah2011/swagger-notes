<?php

declare(strict_types=1);

namespace Toolbox;

trait DBTrait
{
    /**
     * @description 查看指定DBName库的tableName表中columnName字段的指定字段信息
     *
     * @param array $tableNames 支持數組
     * @param array $columns    字段集合
     *
     * @return array
     */
    public function getColumnsInfo(array $tableNames, array $columns = []): array
    {
        $relNames = "'" . implode("','", $tableNames) . "'";
        $where = $columns ? " AND a.attname IN ('" . implode("', '", $columns) . "')" : "";
        $sql = <<<EOF
SELECT COL_DESCRIPTION(a.attrelid, a.attnum) AS comment, t.typname AS typename, a.attname AS name, a.attnotnull AS "notnull"
FROM pg_class AS c,
     pg_attribute AS a
         INNER JOIN pg_type AS t ON t.oid = a.atttypid
WHERE c.relname IN ({$relNames})
  AND a.attrelid = c.oid
{$where}
  AND a.attnum > 0;
EOF;

        return \DB::select($sql);
    }
}