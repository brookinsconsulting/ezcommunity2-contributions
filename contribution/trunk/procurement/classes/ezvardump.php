<?
require_once('Var_Dump.php');

Var_Dump::displayInit(
 array( 'display_mode' => 'HTML4_Table' ),
 array(
       'show_caption'   => FALSE,
       'bordercolor'    => '#DDDDDD',
       'bordersize'     => '2',
       'captioncolor'   => 'white',
       'cellpadding'    => '4',
       'cellspacing'    => '0',
       'color1'         => '#FFFFFF',
       'color2'         => '#F4F4F4',
       'before_num_key' => '',
       'after_num_key'  => '',
       'before_str_key' => '',
       'after_str_key'  => '',
       'before_value'   => '',
       'after_value'    => ''
       )
 );
 
// Var_Dump::display($PhoneTypeID);
// Var_Dump::display($Phone);
?>