<?php
require_once "config/bootstrap.php";
include "header.php";

headerPutanja();
?>
<div class="slikeAlbumaPregled sredina">
    <fieldset class="border p-5 rounded">
        <legend class="w-auto px-2">Ulogujte se</legend>
        <form method="POST" action="process/login.process.php" name="login">
            <div class="col-auto">
                <div class="form-group">
                    <input type="text" class="form-control form-control-sm" name="username" id="uname" placeholder="Korisničko ime" required="required">
                </div><!-- end .form-group -->
            </div><!-- end .col-auto --><br><br>

            <div class="col-auto">
                <div class="form-group">
                    <input type="password" class="form-control form-control-sm" name="password" id="password" placeholder="Šifra" required="required">
                </div><!-- end .form-group -->
            </div><!-- end .col-auto --><br><br>

            <div class="col-auto">
                <input type="submit" class="btn btn-primary btn-sm" name="ulogujSe" value="Uloguj Se">
            </div><!-- end .col-auto -->

            <br><br>
            <a href="forgot_password.php" class="text-warning">Zaboravljena šifra? Klikni ovde</a>
            
        </form>
    </fieldset>
    
</div><!-- end .slikeAlbumaPregled sredina -->

<?php


include "footer.php";
footerPutanja();