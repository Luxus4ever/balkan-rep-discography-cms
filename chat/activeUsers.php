<?php
/********************************** Lista aktivnih korisnika na chatu *********************************************/
function aktivniRazgovori(){
    global $conn;
    session_start();
    include_once "../config/config.php";

    $outgoing_id = $_SESSION['idKorisnici'];

    // Izaberi samo korisnike sa kojima postoji bar jedna razmena poruka
    $sql = "SELECT k.*, MAX(m.vrijemePoruke) AS last_msg_time
        FROM korisnici k
        INNER JOIN messages m 
        ON (m.incoming_msg_id = k.idKorisnici AND m.outgoing_msg_id = {$outgoing_id})
        OR (m.outgoing_msg_id = k.idKorisnici AND m.incoming_msg_id = {$outgoing_id})
        WHERE k.idKorisnici != {$outgoing_id}
        GROUP BY k.idKorisnici
        ORDER BY last_msg_time DESC";
    $query = mysqli_query($conn, $sql);
    $output = "";

    if(mysqli_num_rows($query) == 0){
        $output .= "Nema aktivnih razgovora";
    }else{
        while($row = mysqli_fetch_assoc($query))
        {
            // Zadnja poruka sa ovim korisnikom
            $sql2 = "SELECT * FROM messages 
                     WHERE (incoming_msg_id = {$row['idKorisnici']}
                        OR outgoing_msg_id = {$row['idKorisnici']})
                       AND (outgoing_msg_id = {$outgoing_id} 
                        OR incoming_msg_id = {$outgoing_id}) 
                     ORDER BY msg_id DESC LIMIT 1";
            $query2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($query2);

            if(mysqli_num_rows($query2) > 0){
                $result = $row2['msg'];
            }else{
                $result = "Nema poruka";
            }//end if else

            $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;
            $you = (isset($row2['outgoing_msg_id']) && $outgoing_id == $row2['outgoing_msg_id']) ? "Ti: " : "";

            $now = strtotime(date("Y-m-d H:i:s"));
            $last_activity = $row['last_activity'] ? strtotime($row['last_activity']) : 0;
            $diff = $now - $last_activity;

            $offline = "offline"; // podrazumevano offline

            if(!empty($row['last_activity'])) 
            {
                $lastActivity = strtotime($row['last_activity']);
                $now = time();
                $diff = $now - $lastActivity;

                if ($diff <= 30) { 
                    $offline = ""; // online ako je aktivan u poslednjih 30s
                }
            }//end if()

            $unreadClass = "";
            if(isset($row2['is_read']) && $row2['is_read'] == 0 && $row2['incoming_msg_id'] == $outgoing_id){
                $unreadClass = "unread-msg"; // CSS klasa
            }//end if()
            
            $output .= '<a href="chat.php?username='. urlencode($row['username']) .'&user_id='. $row['idKorisnici'] .'">
                        <div class="content">
                          <img src="../images/profilne/'. $row['profilnaSlika'] .'" alt="" title="'.$row['username'].'">
                          <div class="details">
                            <span>'. $row['username'].'</span>
                            <p class="'.$unreadClass.'">'. $you . $msg .'</p>
                          </div>
                        </div>
                        <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                    </a>';
        }//end while
    }//end if else
    echo $output;
}
aktivniRazgovori();
?>
