<?php
/********************************** Profilne slike i chatovi osobe sa kojom razgovaram trenutno *********************************************/
session_start();
if(isset($_SESSION['idKorisnici'])){
    include_once "../config/config.php";
    include_once "functions.php";
    $outgoing_id = $_SESSION['idKorisnici'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $output = "";

    $sql = "SELECT * FROM messages 
            LEFT JOIN korisnici ON korisnici.idKorisnici = messages.outgoing_msg_id
            WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
            OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) 
            ORDER BY msg_id";
    $query = mysqli_query($conn, $sql);
    if(!$query){
        die("SQL greška: " . mysqli_error($conn));
    }//end if else

    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query))
        {
            $datumPoruke= strtotime($row['vrijemePoruke']);
            $vrijemePoruke= date("H:i:s - d.m.Y.", $datumPoruke);

            // priprema sadržaja poruke
            $poruka = "";
            if(!empty($row['msg'])){
                $poruka .= htmlspecialchars($row['msg']);
            }
            if(!empty($row['imageChat'])){
                $poruka .= "<img src='images/".htmlspecialchars($row['imageChat'])."' class='chat-image' alt='slika'>";
            }
            if(!empty($row['videoChat'])){
                $videoPath = "videos/".htmlspecialchars($row['videoChat']);
                $poruka .= "<a href='#' class='chat-video-link' data-video='{$videoPath}'>📹 Pogledaj video</a>";
            }

            if($row['outgoing_msg_id'] === $outgoing_id){
                // samo za poruke koje sam ja poslao
                $seenText = $row['is_seen'] ? "✔✔" : "✔"; // poslato ili viđeno
                $output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>'. $poruka .'<br>
                                    <span class="dateTime">'. $vrijemePoruke .' '.$seenText.'</span></p>
                                </div>
                                <img src="../images/profilne/'.$row['profilnaSlika'].'" alt="" title="'.$row['username'].'">
                            </div>';
            }else{
                // ovde ne treba seenText jer se radi o porukama koje sam ja primio
                $output .= '<div class="chat incoming">
                                <img src="../images/profilne/'.$row['profilnaSlika'].'" alt="" title="'.$row['username'].'">
                                <div class="details">
                                    <p>'. $poruka .'<br>
                                    <span class="dateTime">'. $vrijemePoruke .'</span></p>
                                </div>
                            </div>';
            }//end if else

        }//end while
    }else{
        $output .= '<div class="text">Nema poruka. Kada pošaljete poruku, pojaviće se ovdje.</div>';
    }//end if else
    echo chatFormatMessageBoldLinks($output);
}else{
    header("location: ../login.php");
}//end if else

// Označi sve poruke od sagovornika kao viđene
$updateSeen = "UPDATE messages 
               SET is_seen = 1 
               WHERE incoming_msg_id = {$outgoing_id} 
               AND outgoing_msg_id = {$incoming_id} 
               AND is_seen = 0";
mysqli_query($conn, $updateSeen);

//Označi sve poruke da su pročitane?
$updateRead = "UPDATE messages 
               SET is_read = 1 
               WHERE incoming_msg_id = {$outgoing_id} 
               AND outgoing_msg_id = {$incoming_id} 
               AND is_read = 0";
mysqli_query($conn, $updateRead);

?>
