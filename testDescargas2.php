<?php
$url = "http://127.0.0.1:8090/minka_siat_ibno/formatoFacturaOnLine.php?codVenta=172";
//Get content as a string. You can get local content or download it from the web.
$downloadedFile = file_get_contents($url);
//Save content from string to .html file.
file_put_contents("testPDF.pdf", $downloadedFile);

?>