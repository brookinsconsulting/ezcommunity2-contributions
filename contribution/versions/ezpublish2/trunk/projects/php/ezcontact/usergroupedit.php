<?
include  "template.inc";
require "ezphputils.php";
require "ezcontact/dbsettings.php";

require  $DOCUMENTROOT . "classes/ezsession.php";
require  $DOCUMENTROOT . "classes/ezuser.php";
require  $DOCUMENTROOT . "classes/ezusergroup.php";

// Slette
if ( $Action == "delete" )
{
    $group =  new eZUserGroup();
    $group->get( $UGID );
    $group->delete( );
    print ( $UGID );

    printRedirect( "../index.php?page=" . $DOCUMENTROOT . "usergrouplist.php" );
}

// Legge til
if ( $Action == "insert" )
{
  $group = new eZUserGroup();
  $group->setName( $Name );
  $group->setDescription( $Description );
  
  if ( $UserAdmin == "on" )    
  {
    $group->setUserAdmin( "Y" );    
  }

  if ( $UserGroupAdmin == "on" )    
  {
    $group->setUserGroupAdmin( "Y" );    
  }

  if ( $PersonTypeAdmin == "on" )    
  {
    $group->setPersonTypeAdmin( "Y" );    
  }

  if ( $CompanyTypeAdmin == "on" )    
  {
    $group->setCompanyTypeAdmin( "Y" );    
  }

  if ( $PhoneTypeAdmin == "on" )    
  {
    $group->setPhoneTypeAdmin( "Y" );    
  }

  if ( $AddressTypeAdmin == "on" )    
  {
    $group->setAddressTypeAdmin( "Y" );    
  }
  
  $group->store();
  printRedirect( "../index.php?page=" . $DOCUMENTROOT . "usergrouplist.php" );
}

if ( $Action == "update" )
{
  $group = new eZUserGroup();
  $group->get( $UGID );
  
  $group->setName( $Name );
  $group->setDescription( $Description );
  
  if ( $UserAdmin == "on" )    
  {
    $group->setUserAdmin( "Y" );    
  }
  else
  {
    $group->setUserAdmin( "N" );
  }

  if ( $UserGroupAdmin == "on" )    
  {
    $group->setUserGroupAdmin( "Y" );    
  }
  else
  {
    $group->setUserGroupAdmin( "N" );
  }

  if ( $PersonTypeAdmin == "on" )    
  {
    $group->setPersonTypeAdmin( "Y" );    
  }
  else
  {
    $group->setPersonTypeAdmin( "N" );
  }

  if ( $CompanyTypeAdmin == "on" )    
  {
    $group->setCompanyTypeAdmin( "Y" );    
  }
  else
  {
    $group->setCompanyTypeAdmin( "N" );
  }

  if ( $PhoneTypeAdmin == "on" )    
  {
    $group->setPhoneTypeAdmin( "Y" );    
  }
  else
  {
    $group->setPhoneTypeAdmin( "N" );
  }

  if ( $AddressTypeAdmin == "on" )    
  {
    $group->setAddressTypeAdmin( "Y" );    
  }
  else
  {
    $group->setAddressTypeAdmin( "N" );
  }
  
  $group->update();
  printRedirect( "../index.php?page=" . $DOCUMENTROOT . "usergrouplist.php" );
}

// sjekke session
{
  include(  $DOCUMENTROOT . "checksession.php" );
}


// hente ut rettigheter
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

// vise feilmelding dersom brukeren ikke har rettigheter.
if ( $usrGroup->userGroupAdmin() == 'N' )
{    
    $t = new Template( "." );
    $t->set_file( array(
        "error_page" => $DOCUMENTROOT . "templates/errorpage.tpl"
        ) );

    $t->set_var( "error_message", "Du har ikke rettiheter til dette." );
    $t->pparse( "output", "error_page" );
}
else
{
    $t = new Template( "." );
    $t->set_file( array( "user_page" =>  $DOCUMENTROOT . "templates/usergroupedit.tpl" ) );   

    $t->set_var( "submit_text", "Legg til" );
    $t->set_var( "action_value", "insert" );
    $t->set_var( "user_group_id", "" );


    if ( $Action == "edit" )
    {
        $group = new eZUserGroup();
        $group->get( $UGID );

        $Name = $group->Name();
        $Description = $group->Description();

        if ( $group->userAdmin() == "Y" )
        {
            $UserAdmin = "checked";
        }

        if ( $group->userGroupAdmin() == "Y" )
        {
            $UserGroupAdmin = "checked";
        }

        if ( $group->personTypeAdmin() == "Y" )
        {
            $PersonTypeAdmin = "checked";
        }

        if ( $group->companyTypeAdmin() == "Y" )
        {
            $CompanyTypeAdmin = "checked";
        }

        if ( $group->phoneTypeAdmin() == "Y" )
        {
            $PhoneTypeAdmin = "checked";
        }
  
        if ( $group->addressTypeAdmin() == "Y" )
        {
            $AddressTypeAdmin = "checked";
        }
  
        $t->set_var( "submit_text", "Lagre endringer" );  
        $t->set_var( "action_value", "update" );
        $t->set_var( "user_group_id", $UGID  );  
    }


    $t->set_var( "user_group_name", $Name );
    $t->set_var( "user_group_description", $Description );

    $t->set_var( "user_checked", $UserAdmin );
    $t->set_var( "user_group_checked", $UserGroupAdmin );
    $t->set_var( "person_type_checked", $PersonTypeAdmin );
    $t->set_var( "company_type_checked", $CompanyTypeAdmin );

    $t->set_var( "phone_type_checked", $PhoneTypeAdmin );
    $t->set_var( "address_type_checked", $AddressTypeAdmin );

    $t->set_var( "document_root", $DOCUMENTROOT );
    $t->pparse( "output", "user_page" );
}
?>
