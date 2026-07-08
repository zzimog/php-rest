<?php

namespace SQL;

use SQL\Traits\WhereClause;

final class Where implements \Stringable
{
  use WhereClause;

  public function __toString(): string
  {
    return $this->getWhereExpression();
  }

  public function getParams(): array
  {
    return $this->whereClauseParams;
  }
}
