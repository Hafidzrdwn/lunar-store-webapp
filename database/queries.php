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
