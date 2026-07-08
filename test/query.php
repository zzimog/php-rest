<?php

use SQL\Query;

require "../src/autoload.php";

$exp = new Query()
  ->read('test_table')
  ->where('foo')
  ->orWhere('hello', 'world')
  ->where('number', '>', 665)
  ->orWhere(
    fn($w) => $w
      ->where('sub1', 1)
      ->orWhere('sub2', 2)
  )
  ->whereIn('fruit', ['Banana', 'Apple', 'Mango', 'Strawberry'])
  ->whereNotIn('vegetable', ['Banana', 'Apple', 'Mango', 'Strawberry'])
  ->whereBetween('CURRENT_DATE', '2026-01-01', '2026-12-31');

echo "<pre>";
echo $exp;
echo "<br/><br/>";
print_r($exp->getParams());
echo "</pre>";

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
