<?php

if (isset($_ENV["PHYSICAL_PATH"]))
    $phys = $_ENV["PHYSICAL_PATH"];
else
    $phys = $_ENV["DOCUMENT_ROOT"] . $_ENV["REQUEST_URI"];

if (!file_exists($phys)) {
    $f = fopen($phys, "w");
    fwrite($f,
"<html>
    <head><title>Cached page</title></head>
    <body>" . $_ENV["REQUEST_URI"] . "</body>
</html>");
    fclose($f);
}

?>