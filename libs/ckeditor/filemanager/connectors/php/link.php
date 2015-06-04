<?php
echo dirname(__FILE__);;
echo "<br >";
echo $a = str_ireplace("\\libs\\ckeditor\\filemanager\\connectors\\php","",dirname(__FILE__));

echo "<br >";
echo str_ireplace("\\","\\\\",$a)."/media/";
?>