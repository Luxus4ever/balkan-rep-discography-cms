<?php
require_once "../config/bootstrap.php";
include "headerAdmin.php";
include "functions/masterAdmin.func.php";
@$sesStatusK= $_SESSION["statusKorisnika"];

zabranjenPristup2("gold", "Silence is golden.");

include "footer.php";