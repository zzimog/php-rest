<?php

namespace SQL;

use SQL\Types\Operation as OP;
use SQL\Traits\WhereClause;

class Query implements \Stringable
{
  use WhereClause;

  private OP $op;
  private string $table;

  public function __toString(): string
  {
    $base = match ($this->op) {
      OP::INSERT => "INSERT INTO {$this->table}",
      OP::SELECT => "SELECT ...\nFROM {$this->table}",
      OP::UPDATE => "UPDATE {$this->table}",
      OP::DELETE => "DELETE FROM {$this->table}"
    };

    $where = $this->getWhereExpression();
    return "$base\n$where";
  }

  public function getParams(): array
  {
    return $this->whereClauseParams;
  }

  public function insert(string $table): self
  {
    $this->op = OP::INSERT;
    $this->table = $table;
    return $this;
  }

  public function read(string $table): self
  {
    $this->op = OP::SELECT;
    $this->table = $table;
    return $this;
  }
}
