<?

include_once( "ezcontact/classes/ezconsultationtype.php" );

$language_file = "consultationtype.php";
$page_path = "/contact/consultationtype";
$item_type_array = eZConsultationType::findTypes();
$move_item = true;

include( "ezcontact/admin/typelist.php" );

//  $item_error = true;

//  if( empty( $HTTP_REFERER ) )
//  {
//      if( empty( $BackUrl ) )
//      {
//          $back_command = "$page_path/list";
//      }
//      else
//      {
//          $back_command = $BackUrl;
//      }
//  }
//  else
//  {
//      $back_command = $HTTP_REFERER;
//  }

//  $t->set_file( array(
//      "list_page" =>  "typelist.tpl",
//      ) );
//  $t->set_block( "list_page", "list_item_tpl", "list_item" );
//  $t->set_block( "list_item_tpl", "line_item_tpl", "line_item" );
//  $t->set_block( "list_page", "no_line_item_tpl", "no_line_item" );

//  $t->set_block( "line_item_tpl", "item_move_up_tpl", "item_move_up" );
//  $t->set_block( "line_item_tpl", "item_separator_tpl", "item_separator" );
//  $t->set_block( "line_item_tpl", "item_move_down_tpl", "item_move_down" );
//  $t->set_block( "line_item_tpl", "no_item_move_up_tpl", "no_item_move_up" );
//  $t->set_block( "line_item_tpl", "no_item_separator_tpl", "no_item_separator" );
//  $t->set_block( "line_item_tpl", "no_item_move_down_tpl", "no_item_move_down" );

//  $t->set_var( "no_line_item", "" );    
//  $t->set_var( "line_item", "" );    
//  $t->set_var( "list_item", "" );    

//  $t->set_var( "item_up_command", "$page_path/up" );
//  $t->set_var( "item_down_command", "$page_path/down" );
//  $t->set_var( "item_edit_command", "$page_path/edit" );
//  $t->set_var( "item_delete_command", "$page_path/delete" );
//  $t->set_var( "item_view_command", "$page_path/view" );
//  $t->set_var( "item_list_command", "$page_path/list" );
//  $t->set_var( "item_new_command", "$page_path/new" );
//  $t->set_var( "item_id", $ItemID );
//  $t->set_var( "item_name", $ItemName );
//  $t->set_var( "back_url", $back_command );
//  $t->set_var( "item_back_command", $back_command );

//  $count = count( $item_type_array );

//  $i = 0;
//  foreach( $item_type_array as $item )
//  {
//      $t->set_var( "item_move_up", "" );
//      $t->set_var( "no_item_move_up", "" );
//      $t->set_var( "item_move_down", "" );
//      $t->set_var( "no_item_move_down", "" );
//      $t->set_var( "item_separator", "" );
//      $t->set_var( "no_item_separator", "" );

//      if ( ( $i %2 ) == 0 )
//          $t->set_var( "bg_color", "bglight" );
//      else
//          $t->set_var( "bg_color", "bgdark" );

//      $t->set_var( "item_id", $item->id() );
//      $t->set_var( "item_name", $item->name() );

//      if ( $i > 0 && $move_item )
//      {
//          $t->parse( "item_move_up", "item_move_up_tpl" );
//      }
//      else
//      {
//          $t->parse( "no_item_move_up", "no_item_move_up_tpl" );
//      }

//      if ( $i > 0 && $i < $count - 1 && $move_item )
//      {
//          $t->parse( "item_separator", "item_separator_tpl" );
//      }
//      else
//      {
//          $t->parse( "no_item_separator", "no_item_separator_tpl" );
//      }

//      if ( $i < $count - 1 && $move_item )
//      {
//          $t->parse( "item_move_down", "item_move_down_tpl" );
//      }
//      else
//      {
//          $t->parse( "no_item_move_down", "no_item_move_down_tpl" );
//      }

//      $t->parse( "line_item", "line_item_tpl", true );

//      $i++;
//  } 

//  if( $count < 1 )
//  {
//      $t->parse( "no_line_item", "no_line_item_tpl" );
//  }
//  else
//  {
//      $t->parse( "list_item", "list_item_tpl" );
//  }

//  $t->pparse( "output", "list_page" );
?>
