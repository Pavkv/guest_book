<?php
if(isset($_GET['view'])) header('Location: v_page.php');
session_start();
$nm = $_SESSION['nams'];
?>
<!DOCTYPE html>
<html>
  <head>
    <title>GUEST BOOK</title>
    <link rel="stylesheet" href="style.css"/>
  </head>
  <body>
    <?php
    require "core.php";
    include('emoji.php');
    $db = pg_connect("host=localhost dbname=guest_book user=pavkv password=8421537690") or die("Can't connect to database".pg_last_error());
    $pid = 0;
    $query = "SELECT max(post_id) FROM guestbook";
    $result = pg_query($db, $query) or die("Can't find data: " . pg_last_error());
    if ($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
        foreach ($line as $value) $pid = $value + 1;
    }

    $_GB = new GuestBook();
    if (isset($_POST['go'])) {
        $str = $_POST['comment'];
        Smilify($str);
      if ($_GB->save($pid, $nm, $str)) {
        echo "<div>Guest Book Entry Saved</div>";
      } else {
        echo "<div>$_GB->error</div>";
      }
    }

    $entries = $_GB->get($pid);
    ?>

    <div id="gb-entries">
    <?php if (count($entries)>0) { foreach ($entries as $e) { ?>
    <div class="gb-row">
      <div class="gb-datetime"><?=$e['datetime']?></div>
      <div class="gb-name">
        <span class="gb-name-a"><?=$e['name']?></span>
        <span class="gb-name-b">signed:</span>
      </div>
      <div class="gb-comment"><?=$e['comment']?></div>
    </div>
    <?php }} ?></div>

    <form method="post" target="_self" id="gb-form">
      <label for="comment">Comment:</label>
      <textarea name="comment" required></textarea>
      <input type="submit" value="Sign Guestbook" name="go"/>
    </form>
    <form method="get" target="_self" id="gb-form">
        <input type="submit" value="View all entries" name="view"/>
    </form>
  </body>
</html>
