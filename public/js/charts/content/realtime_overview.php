<?php
session_start();
?>
<chart>
<chart_type>bar</chart_type>
   <chart_data>
      <row>
         <null/>
	<?php foreach ($_SESSION['code'] as $value) print "<string>{$value}</string>"; ?>
      </row>
      <row>
         <string>bedragen</string>
	<?php foreach ($_SESSION['amounts'] as $value) print "<number>{$value}</number>"; ?>
      </row>
   </chart_data>
</chart>

