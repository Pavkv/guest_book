<?php
class GuestBook {
  private $pdo;
  private $stmt;
  public $error;
  function __construct() {
    try {
      $this->pdo = new PDO(
        "pgsql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
      );
    } catch (Exception $ex) {
      die($ex->getMessage());
    }
  }
  
  function __destruct() {
    $this->pdo = null;
    $this->stmt = null;
  }

  function get ($pid) {
    $this->stmt = $this->pdo->prepare(
      "SELECT * FROM guestbook WHERE post_id=?"
    );
    $this->stmt->execute([$pid]);
    return $this->stmt->fetchall(PDO::FETCH_NAMED);
  }

  function save($pid, $name, $comment, $date=null) {
    if ($date==null) { $date = date("Y-m-d H:i:s"); }
    try {
      $this->stmt = $this->pdo->prepare(
        "INSERT INTO guestbook (post_id, name, comment, datetime) VALUES (?,?,?,?)"
      );
      $this->stmt->execute([$pid, $name, $comment, $date]);
    } catch (Exception $ex) {
      $this->error = $ex->getMessage();
      return false;
    }
    return true;
  }
}
define('DB_HOST', 'localhost');
define('DB_NAME', 'guest_book');
define('DB_USER', 'pavkv');
define('DB_PASSWORD', '8421537690');
$_GB = new GuestBook();