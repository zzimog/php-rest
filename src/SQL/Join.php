<?php

namespace SQL;

class Join implements \Stringable
{
  const INNER = 0;
  const LEFT  = 1;
  const RIGHT = 2;
  const FULL  = 3;

  private array $chunks = [];
  private array $subJoins = [];

  public function __construct(
    private string $mainTable,
    private ?string $otherTable,
    private ?int $type = 0
  ) {
    if ($type < 0 || $type > 3) {
      throw new \InvalidArgumentException("Invalid `Join` type");
    }
  }

  public function __toString(): string
  {
    $joinType = match ($this->type) {
      static::INNER => 'INNER',
      static::LEFT  => 'LEFT',
      static::RIGHT => 'RIGHT',
      static::FULL  => 'FULL'
    };

    $sql = "$joinType JOIN {$this->otherTable} ON ";

    foreach ($this->chunks as $i => $chunk) {
      [
        'main'  => $main,
        'other' => $other,
        'join'  => $join
      ] = $chunk;

      if ($i > 0) {
        $sql .= " $join ";
      }

      $sql .= "$main=$other";
    }

    foreach ($this->subJoins as $subJoin) {
      $sql .= "\n\t$subJoin";
    }

    return trim($sql);
  }

  public function on(
    string $main,
    string $other,
    ?string $join = 'AND'
  ): self {
    $this->chunks[] = [
      'main'  => "{$this->mainTable}.$main",
      'other' => "{$this->otherTable}.$other",
      'join'  => $join
    ];

    return $this;
  }

  public function orOn(string $main, string $other): self
  {
    return $this->on($main, $other, 'OR');
  }

  public function join(
    string $other,
    \Closure $callback,
    ?int $type = 0,
  ): self {
    $main = $this->otherTable;
    $subJoin = new static($main, $other, $type);
    $callback($subJoin);
    $this->subJoins[] = $subJoin;

    return $this;
  }

  public function leftJoin(string $other, \Closure $callback): self
  {
    return $this->join($other, $callback, Join::LEFT);
  }

  public function rightJoin(string $other, \Closure $callback): self
  {
    return $this->join($other, $callback, Join::RIGHT);
  }

  public function fullJoin(string $other, \Closure $callback): self
  {
    return $this->join($other, $callback, Join::FULL);
  }
}
