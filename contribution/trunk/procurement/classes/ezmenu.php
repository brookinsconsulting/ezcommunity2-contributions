<?
// render menu (site/user/frame)
function renderSilverMenu( $menus , $ret = false ){
  foreach ($menus as $key => $menuArr){

    if($menuArr == "&nbsp;") 
      { $my_menu .= "<div style='height: 13px; overflow: hidden;'>&nbsp;</div>"; continue; }

    if($menuArr == 'br' || $menuArr == "<br>" || $menuArr == "")
      { $my_menu .= '<br />'; continue; }


    /*
    if(!is_array($menuArr) && $menuArr == "&nbsp;") 
      { $my_menu .= '<span>&nbsp;</span>'; continue; }
    
    if(!is_array($menuArr) && $menuArr == "<br>") 
      { $my_menu .= '<br />'; continue; }
    */

    if( is_array($menuArr) ) {
      $name = $menuArr['Name'];
      $href = $menuArr['Link'];
      $target = $menuArr['Target'];

      $my_menu .= "\n<table border='0' style='width: 140px; height: 25px;' class='menuContainer' cellpadding='0' cellspacing='0' >
      <tr><td class='menuContainerCell' valign='top' nowrap><div class='menuContainerItem' onClick=location.href='$href' onMouseOver=\"this.style.backgroundImage = 'url(/design/nsb/images/menubgover.png)'\" onMouseOut=\"this.style.backgroundImage =  'url(/design/nsb/images/menubg.png)'\" >&nbsp;<a target='$target' href='$href' style='color: #333333; text-decoration: none; font-weight: bold;'>$name</a></div></td></tr>
      </table>\n";
    } //end if is_array

    $full_menu .= $my_menu;
    $my_menu = '';
  }

  if( $ret ) {
    return $full_menu;
  } else {
    print($full_menu);
  }
}
?>

