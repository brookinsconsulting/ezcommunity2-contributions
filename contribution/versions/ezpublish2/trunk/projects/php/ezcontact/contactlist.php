<?
/*
  Viser en liste over alle kontaktene.
*/
include_once( "class.INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

include_once( "../classes/eztemplate.php" );
include_once( "ezphputils.php" );

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezpersontype.php" );
include_once( "ezcontact/classes/ezsession.php" );
include_once( "ezcontact/classes/ezuser.php" );
include_once( "ezcontact/classes/ezusergroup.php" );
include_once( "ezcontact/classes/ezcompany.php" );
include_once( "ezcontact/checksession.php" );

// Setter template.
$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "contactpage.php" );
$t->setAllStrings();

$t->set_file( array(
                   "contact_page" => "contactpage.tpl",
                   "person_item" =>  "personitem.tpl",
                   "delete_person_item" =>  "persondeleteitem.tpl",
                   "delete_company_item" =>  "companydeleteitem.tpl",
                   "company_item" => "companyitem.tpl" ) );

$company = new eZCompany();

// Søker etter kontakter.
if ( $Query != ""  )
{
    $company_array = $company->searchByPerson( $Query );
}
else
{
    $company_array = $company->getAll( );
}

// Viser liste over kontakter.
if ( count( $company_array ) == 0 )
    $t->set_var( "company_list", "<h2>Ingen treff.</h2>", true );

$color_count = 0;
for ( $i=0; $i<count( $company_array ); $i++ )
{
 // sjekke rettigheter for sletting av firma og person
  {    
      $session = new eZSession();
    
      if ( !$session->get( $AuthenticatedSession ) )
      {
          die( "Du må logge deg på." );    
      }        
    
      $usr = new eZUser();
      $usr->get( $session->userID() );

      $usrGroup = new eZUserGroup();
      $usrGroup->get( $usr->group() );
  }
    
  if ( ( $color_count % 2 ) == 0 )
  {
    $t->set_var( "bg_color", "#F0F0F0" );
  }
  else
  {
    $t->set_var( "bg_color", "#DCDCDC" );
  }
  $cid = $company_array[$i][ "ID" ];
  $t->set_var( "company_id", $cid );
  $t->set_var( "company_name", $company_array[$i][ "Name" ] );
  $t->set_var( "document_root", $DOC_ROOT );

  {
      $person = new eZPerson();
      $personType = new eZPersonType();

      if ( $Query != ""  )
      {
          $person_array = array();
          $person_array = $person->searchByCompanyAndName( $cid, $Query );
      }
      else
      {
          $person_array = $person->getByCompany( $cid );
      }

      
      for ( $j=0; $j<count( $person_array ); $j++ )
      {
          $color_count++;
          if ( ( $color_count % 2 ) == 0 )      
          {
              $t->set_var( "person_bg_color", "#F0F0F0" );
          }
          else
          {
              $t->set_var( "person_bg_color", "#DCDCDC" );
          }
  
          $t->set_var( "person_id", $person_array[$j][ "ID" ] );
          $t->set_var( "first_name", $person_array[$j][ "FirstName" ] );
          $t->set_var( "last_name", $person_array[$j][ "LastName" ] );
          $t->set_var( "document_root", $DOC_ROOT );

          // Utøve rettigheter.
          if ( $usrGroup->personDelete() == 'Y' )
          {
              $t->parse( "delete_person", "delete_person_item" );
              
//                $t->set_var( "delete_person",
//                             "<a href=\"#\" onClick=\"verify( 'Slette kontakt person?', 'index.php?prePage=" . $DOC_ROOT . "personedit.php&Action=delete&PID=" .  $person_array[$j][ "ID" ] . "'); return false;\"><img src=\"" . $DOC_ROOT ."images/slettmini.gif\" width=\"16\" height=\"16\"  border=\"0\"></a>" );
          }
          else
          {
              $t->set_var( "delete_person", "" );
          }

          $t->parse( "person_list", "person_item", true );          
      }
  }

  // Utøve rettigheter.
  if ( $usrGroup->companyDelete() == 'Y' )
  {
      $t->parse( "delete_company", "delete_company_item" );
      
//    	$t->set_var( "delete_company", "<a href=\"#\" onClick=\"verify( 'Slette firma?', 'index.php?prePage=" . $DOC_ROOT .  "companyedit.php&Action=delete&CID=" . $cid . "'); return false;\"><img src=\"" . $DOC_ROOT ."images/slettmini.gif\" width=\"16\" height=\"16\"  border=\"0\" align=\"top\"></a>" );
  }
  else
  {
      $t->set_var( "delete_company", "" );
  }

  $color_count++;

  $t->parse( "company_list", "company_item", true );
  $t->set_var( "person_list", "" );
}


$t->set_var( "document_root", $DOC_ROOT );  

$t->pparse( "output", "contact_page");
?>
