<?php
require_once "config/bootstrap.php";
include "header.php";
include "classes/registracija.class.php";
headerPutanja();

?>
<div id="wrapper" class="">
	<div class="slikeAlbumaPregled sredina">
		<?php 
			$reg= new registracija();
		?>
	</div> <!-- kraj slikeAlbumaPregled sredina -->
</div> <!-- kraj wrapper -->
<?php
include "footer.php";
footerPutanja();
		

