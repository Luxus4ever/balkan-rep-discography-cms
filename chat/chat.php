<?php
require_once "../config/bootstrap.php";
include_once "header.php"; 


if(!isset($_SESSION['idKorisnici'])){
  header("location: ../login.php");
  exit;
}

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
if($user_id === 0){
    die("Nije odabran korisnik za chat.");
}

include_once "functions.php";
include_once "emojis.php";
?>

<body>
<div class="chat-wrapper">
  <section class="chat-area">
    <header>
      <?php 
        razgovorSa($user_id);
      ?>
    </header>
    <div class="chat-box"></div><!-- end .chatbox -->
    <div id="typing-indicator" style="display:none; padding:5px; color:#ccc; font-style:italic;">
    Korisnik kuca...
    </div><!-- end #typing-indicator -->

    <div class="typing-wrapper" style="position: relative;">
      <form action="#" class="typing-area" enctype="multipart/form-data">
        <!-- Ikonica za upload slike -->
         <div id="chat-image-wrapper">
          <label for="chat-image" class="image-btn" title="Slika: max 5mb">
            <i class="fas fa-paperclip"></i>
          </label>
          <input type="file" id="chat-image" name="chat_image" accept="image/*" hidden>
        </div><!-- end #chat-image-wrapper -->

        <!-- Ikonica za upload videa -->
         <div id="chat-video-wrapper">
          <label for="chat-video" class="video-btn" title="Video: max 20mb">
            <i class="fas fa-video"></i>
          </label>
          <input type="file" id="chat-video" name="chat_video" accept="video/*" hidden>
        </div><!-- end #chat-video-wrapper -->

        <!-- Thumbnail preview -->
        <div id="image-preview"></div><!-- end #image-preview -->
        <div id="video-preview"></div><!-- end #video-preview -->

        <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
        <input type="text" name="message" class="input-field" placeholder="Upiši poruku..." autocomplete="off">

        <!-- Dugme za emoji -->
        <button type="button" class="emoji-btn"><i class="far fa-smile"></i></button>

        <!-- Dugme za slanje -->
        <button type="submit"><i class="fab fa-telegram-plane"></i></button>
      </form>

      <!-- Emoji meni -->
      <div class="emoji-picker">
        <?php foreach(getEmojis() as $emoji): ?>
        <span class="emoji"><?= $emoji ?></span>
      <?php endforeach; ?>
      </div><!-- end .emoji-picker -->

    </div><!-- end .typing-wrapper -->
  </section>
</div><!-- end #chat-wrapper -->


  <!-- LIGHTBOX ZA SLIKE -->
<div id="lightbox" class="lightbox">
  <span class="close">&times;</span>
  <img class="lightbox-content" id="lightbox-img" alt="preview">
  <a class="prev">&#10094;</a>
  <a class="next">&#10095;</a>
</div><!-- end #lightbox -->

<!-- LIGHTBOX ZA VIDEO -->
<div id="videobox" class="lightbox">
  <span class="close">&times;</span>
  <video class="videobox-content" id="videobox-video" controls></video>
</div><!-- end #videobox .lightbox -->

  <script src="javascript/chat.js"></script>


</body>
<?php include_once "footer.php"; ?>
</html>
