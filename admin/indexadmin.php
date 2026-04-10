<?php
require_once "../config/bootstrap.php";
include "headerAdmin.php";
include "functions/masterAdmin.func.php";
include "classes/masterClasses.class.php";

@$sesId= $_SESSION["idKorisnici"];
@$sesStatusK= $_SESSION["statusKorisnika"];
@$labelId= $_SESSION["label_Id"];


$adEdPan= new adminEditPanel();
$artEdPan= new artistEditPanel();
$lbEdPan= new labelEditPanel();

if(empty($idKorisnici) || empty($username)){
    zabranjenPristupBezValidacije($sesStatusK);
}else
    {
        $q= "SELECT statusKorisnika FROM korisnici WHERE idKorisnici='{$idKorisnici}'";
        $status_korisnika= mysqli_query($conn, $q);

        while($row= mysqli_fetch_array($status_korisnika))
        {
            $statusKorisnika= $row["statusKorisnika"];

                switch($sesStatusK)
                {
                    case 0; zabranjenPristup($sesStatusK); break;
                    case 1; $adEdPan->prikazAdminEditPanela($idKorisnici, $nazivPromjeneLinka, $sesId); break; //admin
                    case 2; $adEdPan->prikazAdminEditPanela($idKorisnici, $nazivPromjeneLinka, $sesId); break; //moderator
                    case 3; zabranjenPristup($sesStatusK); break; // Slušalac
                    case 4; $artEdPan->prikazArtistEditPanela($idKorisnici, $nazivPromjeneLinka, $sesId, $sesId); break; //izvodjac
                    case 5; $lbEdPan->prikazLabelEditPanela($idKorisnici, $nazivPromjeneLinka, $sesId, $sesStatusK); break; //label
                    default; zabranjenPristup($sesStatusK); break;
                }
        }//end while loop 1
    }//end if else(validacija)
include "footer.php";