<?
include  "template.inc";
require "ezcontact/dbsettings.php";

require  "ezphputils.php";
require $DOCUMENTROOT . "classes/ezperson.php";
require $DOCUMENTROOT . "classes/ezpersontype.php";
require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezuser.php";
require $DOCUMENTROOT . "classes/ezcompany.php";

include( $DOCUMENTROOT . "checksession.php" );

$t = new Template( ".");  

$t->set_file( array(
                   "contact_page" => $DOCUMENTROOT . "templates/contactpage.tpl",
                   "person_item" =>  $DOCUMENTROOT . "templates/personitem.tpl",
                   "company_item" => $DOCUMENTROOT . "templates/companyitem.tpl" ) );


$company = new eZCompany();

if ( $CompanyQuery != ""  )
{
    $company_array = $company->search( $CompanyQuery );
}
else
{
    $company_array = $company->getAll( );
}

for ( $i=0; $i<count( $company_array ); $i++ )
{
  if ( ( $i % 2 ) == 0 )
  {
    $t->set_var( "bg_color", "#eeeeee" );
  }
  else
  {
    $t->set_var( "bg_color", "#dddddd" );
  }
  $cid = $company_array[$i][ "ID" ];
  $t->set_var( "company_id", $cid );
  $t->set_var( "company_name", $company_array[$i][ "Name" ] );
  $t->set_var( "document_root", $DOCUMENTROOT );

  {
      $person = new eZPerson();
      $personType = new eZPersonType();

//        if ( $PersonQuery != ""  )
//        {
//            $person_array = $person->search( $PersonQuery );
//        }
//        else
      {
          unset( $person_array );
          $person_array = $person->getByCompany( $cid );
      }

      for ( $j=0; $j<count( $person_array ); $j++ )
      {
          if ( ( $j % 2 ) == 0 )      
          {
              $t->set_var( "bg_color", "#eeeeee" );
          }
          else
          {
              $t->set_var( "bg_color", "#dddddd" );
          }
  
          $t->set_var( "person_id", $person_array[$j][ "ID" ] );
          $t->set_var( "first_name", $person_array[$j][ "FirstName" ] );
          $t->set_var( "last_name", $person_array[$j][ "LastName" ] );
          $t->set_var( "document_root", $DOCUMENTROOT );
          
          $t->parse( "person_list", "person_item", true );          
      }
      

      
  }
  $t->parse( "company_list", "company_item", true );
  $t->set_var( "person_list", "" );
}


$t->set_var( "document_root", $DOCUMENTROOT );  

$t->pparse( "output", "contact_page");
?>
