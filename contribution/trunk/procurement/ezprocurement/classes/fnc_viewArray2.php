
<?

function extArray($arr)
{
  //      echo '<td>';
  //      echo '<table cellpadding="0" cellspacing="0" border="1">';
  foreach ($arr as $key => $elem) {
    //      echo '<tr>';
    echo ''.$key.'<br />';
    if (is_array($elem)) { extArray($elem); }
    else { echo '<td>'.htmlspecialchars($elem).'&nbsp;</td>'; }
    //      echo '</tr>';
  }
  //      echo '</table>';
  //      echo '</td>';
}


function viewArray($arr)
{
  echo '';
  foreach ($arr as $key1 => $elem1) {
    echo '';
    echo ''.$key1.' ';
    if (is_array($elem1)) { extArray($elem1); }
    else { echo ''.$elem1."\n"; }
    echo '';
  }
  echo "\n\n";
}

?>

