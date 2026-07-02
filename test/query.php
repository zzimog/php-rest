<?php

require "../src/autoload.php";

class Where implements \Stringable
{
  private array $tokens;

  public function __toString(): string
  {
    return "";
  }

  private function handleWhere(
    string $join,
    string $param,
    string $op,
    mixed $value
  ): self {
    return $this;
  }

  public function where(string $param, string $op = '=', mixed $value = null)
  {
    if (empty($value)) {
      $value = $op;
      $op = '=';
    }

    $token = [
      "join"  => "AND",
      "param" => $param,
      "op"    => $op,
      "value" => $value
    ];

    $this->tokens[] = $token;
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
