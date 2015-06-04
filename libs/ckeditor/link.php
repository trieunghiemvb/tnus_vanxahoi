<?php
echo dirname(__FILE__);;
echo "<br >";
$a = str_ireplace("\libs\ckeditor","",dirname(__FILE__));
echo str_ireplace("\\","\\\\",$a)."/media/";
?>