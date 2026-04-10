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
			izvodjaciAbecedno();
		?>
   </div><!-- end .bojaTekstovi -->
	<?php
include "footer.php";
footerPutanja();