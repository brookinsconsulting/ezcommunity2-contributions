<?
include  "template.inc";
require "ezcontact/dbsettings.php";

require  "ezphputils.php";
require $DOCUMENTROOT . "classes/ezperson.php";
require $DOCUMENTROOT . "classes/ezpersontype.php";
require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezuser.php";
require $DOCUMENTROOT . "classes/ezusergroup.php";
require $DOCUMENTROOT . "classes/ezcompany.php";

include( $DOCUMENTROOT . "checksession.php" );

$t = new Template( ".");  

$t->set_file( array(
                   "contact_page" => $DOCUMENTROOT . "templates/contactpage.tpl",
                   "person_item" =>  $DOCUMENTROOT . "templates/personitem.tpl",
                   "company_item" => $DOCUMENTROOT . "templates/companyitem.tpl" ) );


$company = new eZCompany();

if ( $Query != ""  )
{
    $company_array = $company->searchByPerson( $Query );
}
else
{
    $company_array = $company->getAll( );
}

if ( count( $company_array ) == 0 )
    $t->set_var( "company_list", "<h2>Ingen treff.</h2>", true );

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
    
  if ( ( $i % 2 ) == 0 )
  {
    $t->set_var( "bg_color", "#eeeedd" );
  }
  else
  {
    $t->set_var( "bg_color", "#ddddcc" );
  }
  $cid = $company_array[$i][ "ID" ];
  $t->set_var( "company_id", $cid );
  $t->set_var( "company_name", $company_array[$i][ "Name" ] );
  $t->set_var( "document_root", $DOCUMENTROOT );

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
          if ( ( $j % 2 ) == 0 )      
          {
              $t->set_var( "person_bg_color", "#ddeeee" );
          }
          else
          {
              $t->set_var( "person_bg_color", "#ccdddd" );
          }
  
          $t->set_var( "person_id", $person_array[$j][ "ID" ] );
          $t->set_var( "first_name", $person_array[$j][ "FirstName" ] );
          $t->set_var( "last_name", $person_array[$j][ "LastName" ] );
          $t->set_var( "document_root", $DOCUMENTROOT );

          // utøve rettigheter
          if ( $usrGroup->personDelete() == 'Y' )
          {
              $t->set_var( "delete_person", "<a href=\"#\" onClick=\"verify( 'Slette kontakt person?', 'index.php?prePage={document_root}personedit.php&Action=delete&PID={person_id}'); return false;\">Slette person</a>" );
          }
          else
          {
              $t->set_var( "delete_person", "" );
          }
          
          
          $t->parse( "person_list", "person_item", true );          
      }
  }

  // utøve rettigheter
  if ( $usrGroup->companyDelete() == 'Y' )
  {
      $t->set_var( "delete_company", "<a href=\"#\" onClick=\"verify( 'Slette firma?', 'index.php?prePage={document_root}companyedit.php&Action=delete&CID={company_id}'); return false;\">Slette firma</a>" );
  }
  else
  {
      $t->set_var( "delete_company", "" );
  }

  $t->parse( "company_list", "company_item", true );
  $t->set_var( "person_list", "" );
}


$t->set_var( "document_root", $DOCUMENTROOT );  

$t->pparse( "output", "contact_page");
?>
