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
      OP::UPDATE => "UPDATE {$this->table}",
      OP::DELETE => "DELETE FROM {$this->table}"
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
