<?php

require BASE_PATH . '/database/connection.php';
require BASE_PATH . '/database/query_builder.php';

// db conn
$connection = Connection::getInstance();
$pdo = $connection->getConnection();

// query builder instance
$query = new QueryBuilder($pdo);
