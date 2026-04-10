<?php
require_once "config/bootstrap.php";
include "header.php";
include "classes/singlovi.class.php";
include_once "classes/streams.class.php";
include_once "functions/master.func.php";

headerPutanja();
?>
<div id="wrapper">
	<div class="bojaTekstovi">
		<?php 
		$singlId= $_GET["singl"];
		
		$detPj= new singlovi();
		
		if(is_numeric($singlId)){
			$detPj->prikazIzabranogSingla($singlId);

			$strm= new streaming();
			$strm->streamoviSingloviPrikaz($singlId);


			$detPj->singlPjesme($singlId);
		}else{
           	zabranjenPristup2("gold", "Parametar nije validan!");
        }
		?>
   </div><!-- end .bojaTekstovi -->
	<?php
include "footer.php";
footerPutanja();