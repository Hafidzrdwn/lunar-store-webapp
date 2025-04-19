<?php

/**
 * Query Builder Class
 * 
 * A fluent interface for building SQL queries
 */
class QueryBuilder
{
  private $pdo;
  private $table;
  private $select = '*';
  private $where = [];
  private $whereBindings = [];
  private $orderBy = [];
  private $limit = null;
  private $offset = null;
  private $joins = [];
  private $groupBy = [];
  private $having = [];
  private $havingBindings = [];

  /**
   * Constructor
   * 
   * @param PDO $pdo PDO connection instance
   */
  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  /**
   * Set the table to query
   * 
   * @param string $table Table name
   * @return QueryBuilder
   */
  public function table($table)
  {
    $this->table = $table;
    return $this;
  }

  /**
   * Set the columns to select
   * 
   * @param string|array $columns Columns to select
   * @return QueryBuilder
   */
  public function select($columns)
  {
    $this->select = is_array($columns) ? implode(', ', $columns) : $columns;
    return $this;
  }

  /**
   * Add a where clause
   * 
   * @param string $column Column name
   * @param string $operator Comparison operator
   * @param mixed $value Value to compare against
   * @return QueryBuilder
   */
  public function where($column, $operator, $value = null)
  {
    // If only 2 parameters are provided, assume equals operator
    if ($value === null) {
      $value = $operator;
      $operator = '=';
    }

    $this->where[] = "$column $operator ?";
    $this->whereBindings[] = $value;

    return $this;
  }

  /**
   * Add a where clause with OR
   * 
   * @param string $column Column name
   * @param string $operator Comparison operator
   * @param mixed $value Value to compare against
   * @return QueryBuilder
   */
  public function orWhere($column, $operator, $value = null)
  {
    // If only 2 parameters are provided, assume equals operator
    if ($value === null) {
      $value = $operator;
      $operator = '=';
    }

    if (empty($this->where)) {
      return $this->where($column, $operator, $value);
    }

    $this->where[] = "OR $column $operator ?";
    $this->whereBindings[] = $value;

    return $this;
  }

  /**
   * Add a where in clause
   * 
   * @param string $column Column name
   * @param array $values Values to check against
   * @return QueryBuilder
   */
  public function whereIn($column, array $values)
  {
    $placeholders = rtrim(str_repeat('?, ', count($values)), ', ');
    $this->where[] = "$column IN ($placeholders)";
    $this->whereBindings = array_merge($this->whereBindings, $values);

    return $this;
  }

  /**
   * Add a join clause
   * 
   * @param string $table Table to join
   * @param string $first First column
   * @param string $operator Comparison operator
   * @param string $second Second column
   * @param string $type Join type (INNER, LEFT, RIGHT)
   * @return QueryBuilder
   */
  public function join($table, $first, $operator, $second, $type = 'INNER')
  {
    $this->joins[] = "$type JOIN $table ON $first $operator $second";
    return $this;
  }

  /**
   * Add a left join clause
   * 
   * @param string $table Table to join
   * @param string $first First column
   * @param string $operator Comparison operator
   * @param string $second Second column
   * @return QueryBuilder
   */
  public function leftJoin($table, $first, $operator, $second)
  {
    return $this->join($table, $first, $operator, $second, 'LEFT');
  }

  /**
   * Add a right join clause
   * 
   * @param string $table Table to join
   * @param string $first First column
   * @param string $operator Comparison operator
   * @param string $second Second column
   * @return QueryBuilder
   */
  public function rightJoin($table, $first, $operator, $second)
  {
    return $this->join($table, $first, $operator, $second, 'RIGHT');
  }

  /**
   * Add an order by clause
   * 
   * @param string $column Column to order by
   * @param string $direction Direction (ASC or DESC)
   * @return QueryBuilder
   */
  public function orderBy($column, $direction = 'ASC')
  {
    $direction = strtoupper($direction);
    if (!in_array($direction, ['ASC', 'DESC'])) {
      $direction = 'ASC';
    }

    $this->orderBy[] = "$column $direction";
    return $this;
  }

  /**
   * Add a group by clause
   * 
   * @param string|array $columns Columns to group by
   * @return QueryBuilder
   */
  public function groupBy($columns)
  {
    if (is_array($columns)) {
      $this->groupBy = array_merge($this->groupBy, $columns);
    } else {
      $this->groupBy[] = $columns;
    }

    return $this;
  }

  /**
   * Add a having clause
   * 
   * @param string $column Column name
   * @param string $operator Comparison operator
   * @param mixed $value Value to compare against
   * @return QueryBuilder
   */
  public function having($column, $operator, $value = null)
  {
    // If only 2 parameters are provided, assume equals operator
    if ($value === null) {
      $value = $operator;
      $operator = '=';
    }

    $this->having[] = "$column $operator ?";
    $this->havingBindings[] = $value;

    return $this;
  }

  /**
   * Set the limit
   * 
   * @param int $limit Limit value
   * @return QueryBuilder
   */
  public function limit($limit)
  {
    $this->limit = (int) $limit;
    return $this;
  }

  /**
   * Set the offset
   * 
   * @param int $offset Offset value
   * @return QueryBuilder
   */
  public function offset($offset)
  {
    $this->offset = (int) $offset;
    return $this;
  }

  /**
   * Build the SELECT query
   * 
   * @return string
   */
  private function buildSelectQuery()
  {
    $query = "SELECT {$this->select} FROM {$this->table}";

    // Add joins
    if (!empty($this->joins)) {
      $query .= ' ' . implode(' ', $this->joins);
    }

    // Add where clauses
    if (!empty($this->where)) {
      $query .= ' WHERE ';
      $firstWhere = true;

      foreach ($this->where as $condition) {
        if ($firstWhere) {
          // Remove "OR" if it's the first condition
          $condition = preg_replace('/^OR /', '', $condition);
          $firstWhere = false;
        }

        $query .= $condition . ' AND ';
      }

      $query = rtrim($query, ' AND ');
    }

    // Add group by
    if (!empty($this->groupBy)) {
      $query .= ' GROUP BY ' . implode(', ', $this->groupBy);
    }

    // Add having
    if (!empty($this->having)) {
      $query .= ' HAVING ' . implode(' AND ', $this->having);
    }

    // Add order by
    if (!empty($this->orderBy)) {
      $query .= ' ORDER BY ' . implode(', ', $this->orderBy);
    }

    // Add limit and offset
    if ($this->limit !== null) {
      $query .= " LIMIT {$this->limit}";

      if ($this->offset !== null) {
        $query .= " OFFSET {$this->offset}";
      }
    }

    return $query;
  }

  /**
   * Get all records
   * 
   * @return array
   */
  public function get()
  {
    $query = $this->buildSelectQuery();
    $stmt = $this->pdo->prepare($query);

    // Merge all bindings
    $bindings = array_merge($this->whereBindings, $this->havingBindings);

    $stmt->execute($bindings);
    return $stmt->fetchAll();
  }

  /**
   * Get the first record
   * 
   * @return array|null
   */
  public function first()
  {
    $this->limit(1);
    $query = $this->buildSelectQuery();
    $stmt = $this->pdo->prepare($query);

    // Merge all bindings
    $bindings = array_merge($this->whereBindings, $this->havingBindings);

    $stmt->execute($bindings);
    $result = $stmt->fetch();

    return $result !== false ? $result : null;
  }

  /**
   * Count records
   * 
   * @return int
   */
  public function count()
  {
    $this->select = 'COUNT(*) as count';
    $query = $this->buildSelectQuery();
    $stmt = $this->pdo->prepare($query);

    // Merge all bindings
    $bindings = array_merge($this->whereBindings, $this->havingBindings);

    $stmt->execute($bindings);
    return (int) $stmt->fetchColumn();
  }

  /**
   * Insert a new record
   * 
   * @param array $data Data to insert
   * @return int Last insert ID
   */
  public function insert(array $data)
  {
    $columns = implode(', ', array_keys($data));
    $placeholders = rtrim(str_repeat('?, ', count($data)), ', ');

    $query = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute(array_values($data));

    return $this->pdo->lastInsertId();
  }

  /**
   * Update records
   * 
   * @param array $data Data to update
   * @return int Number of affected rows
   */
  public function update(array $data)
  {
    $sets = [];
    $values = [];

    foreach ($data as $column => $value) {
      $sets[] = "$column = ?";
      $values[] = $value;
    }

    $query = "UPDATE {$this->table} SET " . implode(', ', $sets);

    // Add where clauses
    if (!empty($this->where)) {
      $query .= ' WHERE ';
      $firstWhere = true;

      foreach ($this->where as $condition) {
        if ($firstWhere) {
          // Remove "OR" if it's the first condition
          $condition = preg_replace('/^OR /', '', $condition);
          $firstWhere = false;
        }

        $query .= $condition . ' AND ';
      }

      $query = rtrim($query, ' AND ');
    }

    $stmt = $this->pdo->prepare($query);
    $stmt->execute(array_merge($values, $this->whereBindings));

    return $stmt->rowCount();
  }

  /**
   * Delete records
   * 
   * @return int Number of affected rows
   */
  public function delete()
  {
    $query = "DELETE FROM {$this->table}";

    // Add where clauses
    if (!empty($this->where)) {
      $query .= ' WHERE ';
      $firstWhere = true;

      foreach ($this->where as $condition) {
        if ($firstWhere) {
          // Remove "OR" if it's the first condition
          $condition = preg_replace('/^OR /', '', $condition);
          $firstWhere = false;
        }

        $query .= $condition . ' AND ';
      }

      $query = rtrim($query, ' AND ');
    }

    $stmt = $this->pdo->prepare($query);
    $stmt->execute($this->whereBindings);

    return $stmt->rowCount();
  }

  /**
   * Execute a raw query
   * 
   * @param string $query SQL query
   * @param array $bindings Parameter bindings
   * @return \PDOStatement
   */
  public function raw($query, array $bindings = [])
  {
    $stmt = $this->pdo->prepare($query);
    $stmt->execute($bindings);
    return $stmt;
  }

  /**
   * Begin a transaction
   * 
   * @return bool
   */
  public function beginTransaction()
  {
    return $this->pdo->beginTransaction();
  }

  /**
   * Commit a transaction
   * 
   * @return bool
   */
  public function commit()
  {
    return $this->pdo->commit();
  }

  /**
   * Rollback a transaction
   * 
   * @return bool
   */
  public function rollBack()
  {
    return $this->pdo->rollBack();
  }
}
