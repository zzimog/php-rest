<?php

namespace SQL\Traits;

use SQL\Where;

trait WhereClause
{
  private array $whereClauseChunks = [];
  private array $whereClauseParams = [];

  private function getWhereExpression(): string
  {
    $sql = '';

    foreach ($this->whereClauseChunks as $chunk) {
      [
        'key'   => $key,
        'op'    => $op,
        'value' => $value,
        'join'  => $join
      ] = $chunk;

      if ($value instanceof Where) {
        $sql .= " $join (" . trim($value) . ")";
      } elseif (is_array($value)) {
        $sql .= " $join $key $op ";

        switch ($op) {
          case 'IN':
          case 'NOT IN':
            $placeholders = array_fill(0, count($value), '?');
            $sql .= '(' . implode(',', $placeholders) . ')';
            break;
          case 'BETWEEN':
          case 'NOT BETWEEN':
            $sql .= "? AND ?";
            break;
          default:
            throw new \Error("Invalid operator");
        }
      } else {
        $sql .= " $join " . $key . $op . '?';
      }
    }

    return trim(preg_replace('/^\s*(AND|OR)?\s*/i', '', $sql));
  }

  public function where(
    \Closure|Where|string $key,
    mixed $op = null,
    mixed $value = null,
    ?string $join = 'AND',
  ): static {
    if ($value === null) {
      $value = $op ?? true;
      $op = '=';
    }

    if ($key instanceof \Closure) {
      $key = $key(new Where());
    }

    if ($key instanceof Where) {
      $value = $key;
      $key = null;
      $params = $value->getParams();
      array_push($this->whereClauseParams, ...$params);
    } elseif (is_array($value)) {
      array_push($this->whereClauseParams, ...$value);
    } else {
      $this->whereClauseParams[] = $value;
    }

    $this->whereClauseChunks[] = [
      'key'   => $key,
      'op'    => $op,
      'value' => $value,
      'join'  => $join
    ];

    return $this;
  }

  public function orWhere(\Closure|Where|string $key, mixed $op = null, mixed $value = null)
  {
    return $this->where($key, $op, $value, 'OR');
  }

  public function notWhere(\Closure|Where|string $key, mixed $op = null, mixed $value = null)
  {
    return $this->where($key, $op, $value, 'AND NOT');
  }

  public function orNotWhere(\Closure|Where|string $key, mixed $op = null, mixed $value = null)
  {
    return $this->where($key, $op, $value, 'OR NOT');
  }

  // --------------------------------------------------------------------------
  // IS (NOT) NULL conditions
  // --------------------------------------------------------------------------

  public function whereNull(string $key)
  {
    return $this->where($key, 'IS', 'NULL');
  }

  public function orWhereNull(string $key)
  {
    return $this->where($key, 'IS', 'NULL', 'OR');
  }

  public function whereNotNull(string $key)
  {
    return $this->where($key, 'IS NOT', 'NULL');
  }

  public function orWhereNotNull(string $key)
  {
    return $this->where($key, 'IS NOT', 'NULL', 'OR');
  }

  // --------------------------------------------------------------------------
  // IN conditions
  // --------------------------------------------------------------------------

  public function whereIn(string $key, array $values)
  {
    return $this->where($key, 'IN', $values);
  }

  public function orWhereIn(string $key, array $values)
  {
    return $this->where($key, 'IN', $values, 'OR');
  }

  public function whereNotIn(string $key, array $values)
  {
    return $this->where($key, 'NOT IN', $values);
  }

  public function orWhereNotIn(string $key, array $values)
  {
    return $this->where($key, 'NOT IN', $values, 'OR');
  }

  // --------------------------------------------------------------------------
  // BETWEEN conditions
  // --------------------------------------------------------------------------

  public function whereBetween(string $key, string|int $start, string|int $end)
  {
    return $this->where($key, 'BETWEEN', [$start, $end]);
  }

  public function orWhereBetween(string $key, string|int $start, string|int $end)
  {
    return $this->where($key, 'BETWEEN', [$start, $end], 'OR');
  }

  public function whereNotBetween(string $key, string|int $start, string|int $end)
  {
    return $this->where($key, 'NOT BETWEEN', [$start, $end]);
  }

  public function orWhereNotBetween(string $key, string|int $start, string|int $end)
  {
    return $this->where($key, 'NOT BETWEEN', [$start, $end], 'OR');
  }
}
