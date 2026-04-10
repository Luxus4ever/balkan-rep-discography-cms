<?php
require_once "config/bootstrap.php";
include "header.php";
include "classes/pjesme.class.php";
include_once "functions/master.func.php";

headerPutanja();
?>
<div id="wrapper">
	<div class="bojaTekstovi">
		<?php 
		$tekstId= $_GET["tekst"];
		
		$detPj= new pjesme();
		
		if(is_numeric($tekstId)){
			$detPj->tekstPjesme($tekstId);
		}else{
           	zabranjenPristup2("gold", "Parametar nije validan!");
        }
		?>
   </div><!-- end .bojaTekstovi -->
	<?php
include "footer.php";
footerPutanja();