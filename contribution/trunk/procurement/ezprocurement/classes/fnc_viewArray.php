<?
if (!function_exists(extArray)) {
        function extArray($arr)
        {
//      echo '<td>';
//      echo '<table cellpadding="0" cellspacing="0" border="1">';
        foreach ($arr as $key => $elem) {
//      echo '<tr>';
        echo ''.$key.'<br />';
        if (is_array($elem)) { extArray($elem); }
        else { echo ''.htmlspecialchars($elem).'<br />'; }
//      echo '</tr>';
        }
//      echo '</table>';
//      echo '</td>';
        }
} // ends function_exists
if (!function_exists(viewArray)) {
        function viewArray($arr)
        {
           echo '';
	
//           foreach ($arr as $key1 => $elem1) {
if (is_array($arr)) {
           foreach ($arr as $key1 => $elem1) {
               echo '';
               echo ''.$key1.' ';
               if (is_array($elem1)) { extArray($elem1); }
               else { echo ''.$elem1."\n"; }
               echo '';
           }
}else{
	echo $arr;
}
           echo "\n\n";
        }

} // ends function_exists
	?>
	