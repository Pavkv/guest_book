<?php
include('emoji.php');
$db = pg_connect("host=localhost dbname=guest_book user=pavkv password=8421537690") or die("Can't connect to database".pg_last_error());
if(isset($_GET['go'])) header('Location: b_page.php');
session_start();
$nm = $_SESSION['nams'];
$f = 0;
if (isset($_GET['delete'])) {
    $i = $_GET['delete'];
    $query1 = "DELETE FROM guestbook WHERE post_id='" . $i . "'";
    $result1 = pg_query($db, $query1) or die("Can't find data: " . pg_last_error());
    $_SESSION['f'] = $i;
    header('Location: delete.php');
}
if(isset($_GET['edited'])){
    $text = $_GET['text'];
    Smilify($text);
    $i = $_GET['edit'];
    $query2 = "UPDATE guestbook SET comment = '".strval($text)."' WHERE post_id ='" .$i. "'";
    $result2 = pg_query($db, $query2) or die("Can't find data: " . pg_last_error());
}
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
$pid = 0;
$query = "SELECT max(post_id) FROM guestbook";
$result = pg_query($db, $query) or die("Can't find data: " . pg_last_error());
if ($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
    foreach ($line as $value) $pid = $value;
}
$_GB = new GuestBook();
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
                    <div class="gb-comment"><?=$e['comment']?></div>
                </div>
        <?php }} ?></div>
    </form><?php
    $s++;
    $k++;
}?>
<form method="get" target="_self" id="gb-form" style="<?php if(!isset($_GET['edit'])) echo 'display:none'?>">
    <input type="text" name="text" value="<?php  $entries = $_GB->get($_GET['edit']); if (count($entries)>0)
    { foreach ($entries as $e) { echo $e['comment'];}}?>">
    <input type="submit" value="save" name="edited">
</form>
<form method="get" target="_self" id="gb-form">
    <input type="submit" value="Go back" name="go"/>
</form>
</body>
</html>