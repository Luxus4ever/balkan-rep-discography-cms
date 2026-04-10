<?php
//Funkcije u ovom fajlu
//1. razgovorSa()
//2. aktivniRazgovori()
//3. cleanMessage()


/********* Heder sa kime se vodi chat, piše u hederu chata ***********/
function razgovorSa($user_id)
{
    global $conn;

    $logId= $_SESSION['idKorisnici'];
    $logUsername= $_SESSION['username'];
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $q="SELECT * FROM korisnici WHERE idKorisnici = {$user_id}";
    $select_chat = mysqli_query($conn, $q);
    if(mysqli_num_rows($select_chat) > 0){
      $row = mysqli_fetch_assoc($select_chat);
    }else{
      header("location: users.php");
    }
    ?>
    <div class="left-options1">
      <div class="left-options2">
        <a href="users.php?username=<?php echo urlencode($logUsername) .'&user_id='. $logId;?>" class="back-icon">
          <i class="fas fa-arrow-left"></i>
          <span id="unread-counter" class="unread-badge" style="display:none;">0</span>
          <!-- Ako je ovaj span #unred-counter, uključen u headeru ili nekom drugom mjestu, onda se ne prikazuje ovde. U svakom slučaju bolja opcija je da bude prikaz u header-u. Na mestu gde je oznaka za poruke. -->
        </a>
        <img src="../images/profilne/<?php echo $row['profilnaSlika']; ?>" alt="">
      </div><!-- end .left-options2 -->
      <div class="details">
        <span><b><?php echo $row['username'] ?></b></span>

      </div><!-- end .details -->
    </div><!-- end .left-options1 -->
    

    <!-- Dodatne opcije - desno u headeru -->
    <div id="chat-options" class="chat-options">
      <div id="block-unblock-user" class="block-unblock-user" data-userid="<?php echo $row['idKorisnici']; ?>">
        <i id="block-icon" class="fas fa-ban" style="color:white; font-size:20px; cursor:pointer;" title="Blokiraj korisnika"></i>
      </div><!-- end #block-unblock-user -->

      <div id="enable-disable-files" class="enable-disable-files" data-userid="<?php echo $row['idKorisnici']; ?>">
        <i id="files-icon" class="fas fa-toggle-on" style="color:white; font-size:20px; cursor:pointer;" title="Dozvoli/onemogući fajlove"></i>
      </div><!-- end #enable-disable-files -->

    </div><!-- end #chat-options .chat-options -->
    <?php
}//end function razgovorSa()

 //--------------------------------------------------------------------------------------------------------------------------------

/********************************** Lista aktivnih korisnika na chatu *********************************************/
function aktivniRazgovori(){
  global $conn;
  include_once "../config/config.php";

  $outgoing_id = (int)$_SESSION['idKorisnici'];
  $sql = "SELECT * FROM korisnici WHERE idKorisnici != {$outgoing_id} ORDER BY idKorisnici DESC";
  $query = mysqli_query($conn, $sql);
  $output = "";
  if(mysqli_num_rows($query) == 0){
      $output .= "Nema dostupnih korisnika za chat";
  }elseif(mysqli_num_rows($query) > 0){
      while($row = mysqli_fetch_assoc($query))
      {
          $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = {$row['idKorisnici']}
                  OR outgoing_msg_id = {$row['idKorisnici']}) AND (outgoing_msg_id = {$outgoing_id} 
                  OR incoming_msg_id = {$outgoing_id}) ORDER BY msg_id DESC LIMIT 1";
          $query2 = mysqli_query($conn, $sql2);
          $row2 = mysqli_fetch_assoc($query2);
          (mysqli_num_rows($query2) > 0) ? $result = $row2['msg'] : $result ="Nema poruka";
          (strlen($result) > 28) ? $msg =  substr($result, 0, 28) . '...' : $msg = $result;
          
          if(isset($row2['outgoing_msg_id'])){
              ($outgoing_id == $row2['outgoing_msg_id']) ? $you = "Ti: " : $you = "";
          }else{
              $you = "";
          }//end if else


          ($outgoing_id == $row['idKorisnici']) ? $hid_me = "hide" : $hid_me = "";

          $output .= '<a href="chat.php?username='. urlencode($row['username']) .'&user_id='. $row['idKorisnici'] .'">

                      <div class="content">
                      <img src="../images/profilne/'. $row['profilnaSlika'] .'" alt="">
                      <div class="details">
                          <span>'. $row['username'] .'</span>
                          <p>'. $you . $msg .'</p>
                      </div>
                      </div>
                      <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                  </a>';
      }//end while
  }//end elseif
  echo $output;
}//end function aktivniRazgovori()

//--------------------------------------------------------------------------------------------------------------------------------


/********************************** Osnovno čišćenje teksta poruka *********************************************/
function cleanMessage($msg) {
    // ukloni praznine sa početka i kraja
    $msg = trim($msg);

    // izbaci HTML tagove (ako želiš da dozvoliš <b>, <i>, možeš dodati drugi parametar u strip_tags)
    $msg = strip_tags($msg);

    // ograniči dužinu na 1000 karaktera da neko ne pošalje previše podataka
    $msg = mb_substr($msg, 0, 1000);

    return $msg;
}//end function cleanMessage()

//--------------------------------------------------------------------------------------------------------------------------------

/********************************** Funkcija koja vrši podebljavanje linkova u tekstu *********************************************/
function chatFormatMessageBoldLinks(string $text): string
{
    $pattern = '/\b((https?:\/\/|www\.)[^\s]+)/i';
    $replace = '<strong>$1</strong>';

    return preg_replace($pattern, $replace, $text);
}
/********************************** Pozvana u fajlu get-chat.php *********************************************/

?>
