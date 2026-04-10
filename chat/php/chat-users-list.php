<?php
while($row = mysqli_fetch_assoc($query))
{
    $sql2 = "SELECT * FROM messages 
            WHERE (incoming_msg_id = {$row['idKorisnici']}
            OR outgoing_msg_id = {$row['idKorisnici']}) 
            AND (outgoing_msg_id = {$outgoing_id} 
            OR incoming_msg_id = {$outgoing_id}) 
            ORDER BY msg_id DESC LIMIT 1";

    $query2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($query2);

    (mysqli_num_rows($query2) > 0) ? $result = $row2['msg'] : $result ="Nema poruka";
    $msg = mb_strlen($result) > 28 ? mb_substr($result,0,28).'...' : $result;

    if(isset($row2['outgoing_msg_id'])){
        ($outgoing_id == $row2['outgoing_msg_id']) ? $you = "Ti: " : $you = "";
    }else{
        $you = "";
    }//end if else

    $now = strtotime(date("Y-m-d H:i:s"));
    $last_activity = strtotime($row['last_activity']);
    $diff = $now - $last_activity;

    $offline = ($diff > 30) ? "offline" : ""; // offline ako nije aktivan > 30s
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
?>
