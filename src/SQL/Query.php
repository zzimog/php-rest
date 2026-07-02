<?php

namespace SQL;

use SQL\Types\Operation as OP;

class Query implements \Stringable
{
  private OP $op;
  private string $table;

  public function __toString(): string
  {
    $base = match ($this->op) {
      OP::INSERT => "INSERT INTO {$this->table}",
      OP::SELECT => "SELECT ... FROM {$this->table}",
    };

    return $base;
  }

  public function insert(string $table): self
  {
    $this->op = OP::INSERT;
    $this->table = $table;
    return $this;
  }
}

/**
 * CREATE
 * new Query()
 *   ->insert("table_name")
 *   ->values([
 *     "col1" => "foo",
 *     "col2" => 10
 *   ])
 *   ->values([
 *     "col1" => "bar",
 *     "col2" => 5
 *   ])
 *   ->execute();

 * READ
 * new Query()
 *   ->from('table_name', 'table_alias')
 *   ->select([
 *     'col1',
 *     'alias2' => 'col2',
 *     'col3'
 *   ])
 *   ->distinct()
 *   ->where('col1', 'not null')
 *   ->orWhere("col2", ">", 5)
 *   ->count()
 *   ->limit(10)
 *   ->offset(20)
 *   //->execute()
 *   //->all()
 *   ->first();

 * UPDATE
 * new Query()
 *   ->update("table_name")
 *   ->values([
 *     "col1" => "foo",
 *     "col2" => 10
 *   ])
 *   ->where("col1", "not null")
 *   ->where("col2", ">", 5)
 *   ->execute();

 * DELETE
 * new Query()
 *   ->delete("table_name")
 *   ->where("col1", "foo")
 *   ->andWhere("col2", ">", 5)
 *   ->execute();
 */
