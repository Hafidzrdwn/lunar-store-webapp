<?php

/**
 * Database Connection Class
 * 
 * Handles the database connection using PDO
 */
class Connection
{
  private static $instance = null;
  private $conn;

  // Database configuration
  private $host = DB_HOST;
  private $db_name = DB_NAME;
  private $username = DB_USER;
  private $password = DB_PASS;
  private $charset = 'utf8mb4';

  /**
   * Private constructor to prevent direct instantiation
   */
  private function __construct()
  {
    $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
      $this->conn = new PDO($dsn, $this->username, $this->password, $options);
    } catch (PDOException $e) {
      throw new Exception("Connection failed: " . $e->getMessage());
    }
  }

  /**
   * Get singleton instance
   * 
   * @return Connection
   */
  public static function getInstance()
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Get the PDO connection
   * 
   * @return PDO
   */
  public function getConnection()
  {
    return $this->conn;
  }

  /**
   * Prevent cloning of the instance
   */
  private function __clone() {}

  /**
   * Prevent unserialization of the instance
   */
  public function __wakeup()
  {
    throw new Exception("Cannot unserialize singleton");
  }
}
