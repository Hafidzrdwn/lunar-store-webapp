<?php

function query($query)
{
  global $conn;
  $result = mysqli_query($conn, $query);
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }
  return $rows;
}

// Function to execute a query with prepared statements
function stmt_query($sql, $params = [])
{
  global $conn;

  if (empty($params)) {
    // No parameters, use regular query
    $result = mysqli_query($conn, $sql);
    if (!$result) {
      throw new Exception("Query failed: " . mysqli_error($conn));
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $data[] = $row;
    }
    return $data;
  } else {
    // Use prepared statement
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
      throw new Exception("Prepare failed: " . mysqli_error($conn));
    }

    // Determine parameter types
    $types = '';
    foreach ($params as $param) {
      if (is_int($param)) {
        $types .= 'i';
      } elseif (is_float($param)) {
        $types .= 'd';
      } elseif (is_string($param)) {
        $types .= 's';
      } else {
        $types .= 'b';
      }
    }

    // Bind parameters
    if (!empty($params)) {
      $bindParams = array_merge([$stmt, $types], $params);
      call_user_func_array('mysqli_stmt_bind_param', $bindParams);
    }

    // Execute and get results
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $data[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $data;
  }
}

function queryOne($sql, $params = [])
{
  $results = query($sql, $params);
  return !empty($results) ? $results[0] : null;
}

function countData($table)
{
  global $conn;
  $query = "SELECT * FROM $table";
  $result = mysqli_query($conn, $query);
  return mysqli_num_rows($result);
}

function getSingleData($table, $column, $value)
{
  global $conn;
  $query = "SELECT * FROM $table WHERE $column = ?";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, 's', $value);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return mysqli_fetch_assoc($result);
}

function insertData($table, $data)
{
  global $conn;
  $columns = implode(", ", array_keys($data));
  $values = array_values($data);
  $values = array_map(function ($value) use ($conn) {
    return "'" . mysqli_real_escape_string($conn, $value) . "'";
  }, $values);
  $values = implode(", ", $values);
  $query = "INSERT INTO $table ($columns) VALUES ($values)";
  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function updateData($table, $data, $column, $value)
{
  global $conn;
  $set = [];
  foreach ($data as $key => $val) {
    $set[] = "$key = '" . mysqli_real_escape_string($conn, $val) . "'";
  }
  $set = implode(", ", $set);
  $query = "UPDATE $table SET $set WHERE $column = '$value'";
  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function deleteData($table, $column, $value)
{
  global $conn;
  $query = "DELETE FROM $table WHERE $column = '$value'";
  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function sanitizeInput($input)
{
  return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// function generateCode($table, $field, $char)
// {
//   global $conn;
//   $query = "SELECT MAX($field) as max_code FROM $table";
//   $result = mysqli_query($conn, $query);
//   $row = mysqli_fetch_assoc($result);
//   $code = $row['max_code'] ?? '0';
//   $no_urut = (int) substr($code, 3, 3);
//   $no_urut++;
//   $new_code = $char . sprintf("%03s", $no_urut) . '-' . date('ymdhis');
//   return $new_code;
// }
