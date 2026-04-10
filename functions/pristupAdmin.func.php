<?php
//FUNKCIJE SADRŽANE U OVOM FAJLU
//pristupAdmin (daje pristup admin panelu)
//tipKorisnika (prikazuje tip korisnika)

//********************************* Metoda koja validnom korisniku daje prikaz admin panela   *********************************//
function pristupAdmin()
{
    ?>
    <a href="admin/indexadmin.php" class="btn btn-warning d-flex align-items-center hover-shadow sredina">Panel za uređivanje</a><hr>
    <?php
}//end pristupAdmin()
//********************************* Pozvana metoda u detailsUser.func.php  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Metoda koja prikazuje tip korinsika  *********************************//
function tipKorisnika($sesId)
{
    global $conn;
    $q= "SELECT * FROM korisnici JOIN status_korisnika ON statusKorisnika=idStatusKorisnika WHERE idKorisnici='{$sesId}'";
    $select_korisnik= mysqli_query($conn, $q);

    $tipKorisnika= "";
    while($row= mysqli_fetch_array($select_korisnik))
    {
        $tipKorisnika = $row["nazivStatusaKorisnika"];
    }//end while
    echo "<small>Tip profila: <span class='user-role' data-role=$tipKorisnika>$tipKorisnika</span> </small>";
    ?>
    <style>
        .role-admin { color: aliceblue; }
        .role-moderator { color: #f3db7b; }
        .role-Izvođač { color: #01cee5; }
        .role-Izdavač\/Label { color: #6d6dff; }
        .role-Blokiran { color: red; }
        .role-default { color: gray; }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var roleElements = document.querySelectorAll('.user-role');
            roleElements.forEach(function(element) {
                var role = element.getAttribute('data-role');
                element.className = 'role role-' + (role ? role : 'default');
            });
        })
    </script>
    <?php
}//end tipKorisnika()
/********************************* 
Metoda pozvana u fajlu insertDataPanel.class.php u metodi leftSideAdminInsertAlbum, 
*********************************/

//--------------------------------------------------------------------------------------------------------------------------------