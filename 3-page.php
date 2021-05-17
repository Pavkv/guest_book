<!DOCTYPE html>
<html>
  <head>
    <title>DEMO GUEST BOOK PAGE</title>
    <link rel="stylesheet" href="4-page.css"/>
  </head>
  <body>
    <?php
    require "2-core.php";
    $pid = 1;
    $_GB = new GuestBook();
    if (isset($_POST['name'])) {
      if ($_GB->save($pid, $_POST['name'], $_POST['comment'])) {
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
      <label for="name">Name:</label>
      <input type="text" name="name" required/>
      <label for="email">Email:</label>
      <input type="email" name="email" required/>
      <label for="comment">Comment:</label>
      <textarea name="comment" required></textarea>
      <input type="submit" value="Sign Guestbook"/>
    </form>
  </body>
</html>