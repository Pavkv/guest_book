<?php
require "core.php";
include('emoji.php');
$_GB = new GuestBook();
$db = pg_connect("host=localhost dbname=guest_book user=pavkv password=8421537690") or die("Can't connect to database".pg_last_error());
if (isset($_GET['delete'])) {
    $i = $_GET['delete'];
    $query1 = "DELETE FROM guestbook WHERE post_id='" . $i . "'";
    $result1 = pg_query($db, $query1) or die("Can't find data: " . pg_last_error());
    $_SESSION['f'] = $i;
    header('Location: delete.php');
}
if(isset($_GET['edited'])){
    $text = $_GET['text'];
    $i = $_GET['text2'];
    $query3 = "UPDATE guestbook SET comment = '".$text."' WHERE post_id ='".$i."'";
    $result3 = pg_query($db, $query3) or die("Can't find data: " . pg_last_error());
}
session_start();
$nm = $_SESSION['nams'];
$f = 0;
?>
<!DOCTYPE html>
<html>
  <head>
    <title>GUEST BOOK</title>
    <link rel="stylesheet" href="style.css"/>
  </head>
  <body>
    <?php
    $pid = 0;
    $query = "SELECT max(post_id) FROM guestbook";
    $result = pg_query($db, $query) or die("Can't find data: " . pg_last_error());
    if ($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
        foreach ($line as $value) $pid = $value + 1;
    }

    if (isset($_POST['go'])) {
        $str = $_POST['comment'];
      if ($_GB->save($pid, $nm, $str)) {
        echo "<div>Guest Book Entry Saved</div>";
      } else {
        echo "<div>$_GB->error</div>";
      }
    }
    $pid = 0;
    $query = "SELECT max(post_id) FROM guestbook";
    $result = pg_query($db, $query) or die("Can't find data: " . pg_last_error());
    if ($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
        foreach ($line as $value) $pid = $value;
    }
    $s = 1;
    $k = 1;
    while ($pid >= $s){
        $entries = $_GB->get($s);?>
        <form>
        <div id='gb-entries'>
            <?php if (count($entries)>0) { foreach ($entries as $e) { ?>
                <div class="gb-row">
                    <?php if ($e['name'] == $nm) {?>
                        <button type="submit" class="deletebutton" name="delete" value="<?php echo $k?>"></button>
                        <button type="submit" class="editbutton" name="edit" value="<?php echo $k?>"></button>
                    <?php }?>
                    <div class="gb-datetime"><?=$e['datetime']?></div>
                    <div class="gb-name">
                        <span class="gb-name-a"><?=$e['name']?></span>
                        <span class="gb-name-b">signed:</span>
                    </div>
                    <div class="gb-comment"><?php $str = $e['comment']; Smilify($str); echo $str;?></div>
                </div>
            <?php }} ?></div>
        </form><?php
        $s++;
        $k++;
    }?>

    <form method="post" target="_self" id="gb-form">
      <label for="comment">Comment:</label>
      <textarea name="comment" required></textarea>
      <input type="submit" value="Sign Guestbook" name="go"/>
    </form>
    <form method="get" target="_self" id="gb-form" style="<?php if(!isset($_GET['edit']) || isset($_GET['canceled'])) echo 'display:none'?>">
        <input type="hidden" name="text2" value="<?php echo $_GET['edit']; ?>"/><input type="text" name="text" value="<?php  $entries = $_GB->get($_GET['edit']); if (count($entries)>0)
        { foreach ($entries as $e) { echo $e['comment']; }}?>">
        <input type="submit" value="Save" name="edited">
        <input type="submit" value="Cancel" name="canceled">
    </form>
  </body>
</html>
