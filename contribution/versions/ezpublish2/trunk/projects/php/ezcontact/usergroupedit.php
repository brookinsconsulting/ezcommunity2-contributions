<?
/*
  Editere en gruppe.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" );

$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "../classes/eztemplate.php" );

include_once(  "ezphputils.php" ); 

include_once( "ezcontact/classes/ezsession.php" );
include_once( "ezcontact/classes/ezuser.php" );
include_once( "ezcontact/classes/ezusergroup.php" ); 

// Slette gruppe.
if ( $Action == "delete" )
{
    $group =  new eZUserGroup();
    $group->get( $UGID );
    $group->delete( );
    print ( $UGID );

    Header( "Location: index.php?page=" . $DOC_ROOT . "usergrouplist.php" ); 
}

// Legge til gruppe.
if ( $Action == "insert" )
{
    $group = new eZUserGroup();
    $group->setName( $Name );
    $group->setDescription( $Description );

    if ( $PersonDelete == "on" )    
    {
        $group->setPersonDelete( "Y" );    
    }
  
    if ( $CompanyDelete == "on" )    
    {
        $group->setCompanyDelete( "Y" );    
    }
  
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
    Header( "Location: index.php?page=" . $DOC_ROOT . "usergrouplist.php" ); 
}

// Oppdatere gruppe.
if ( $Action == "update" )
{
    $group = new eZUserGroup();
    $group->get( $UGID );
  
    $group->setName( $Name );
    $group->setDescription( $Description );
  
    if ( $PersonDelete == "on" )    
    {
        $group->setPersonDelete( "Y" );    
    }
    else
    {
        $group->setPersonDelete( "N" );
    }
  
    if ( $CompanyDelete == "on" )    
    {
        $group->setCompanyDelete( "Y" );    
    }
    else
    {
        $group->setCompanyDelete( "N" );
    }

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
    Header( "Location: index.php?page=" . $DOC_ROOT . "usergrouplist.php" ); 
}

// Sjekke session.
{
    include(  $DOC_ROOT . "checksession.php" );
}


// Hente ut rettigheter.
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

// Vise feilmelding dersom brukeren ikke har rettigheter.
if ( $usrGroup->userGroupAdmin() == 'N' )
{    
    $t = new Template( "." );
    $t->set_file( array(
        "error_page" => $DOC_ROOT . "templates/errorpage.tpl"
        ) );

    $t->set_var( "error_message", "Du har ikke rettiheter til dette." );
    $t->pparse( "output", "error_page" );
}
else
{
    // Sette template.
    $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var ( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "usergroupedit.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "user_page" =>  "usergroupedit.tpl"
        ) );   

    $t->set_var( "submit_text", "Legg til" );
    $t->set_var( "action_value", "insert" );
    $t->set_var( "user_group_id", "" );
    $t->set_var( "head_line", "Legg til ny brukergruppe" );

    // Editere gruppe.
    if ( $Action == "edit" )
    {
        $group = new eZUserGroup();
        $group->get( $UGID );

        $Name = $group->Name();
        $Description = $group->Description();

        if ( $group->personDelete() == "Y" )
        {
            $PersonDelete = "checked";
        }
        
        if ( $group->companyDelete() == "Y" )
        {
            $CompanyDelete = "checked";
        }

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
        $t->set_var( "head_line", "Rediger brukergruppe" );
    }
    
    // Sette template variabler.
    $t->set_var( "user_group_name", $Name );
    $t->set_var( "user_group_description", $Description );

    $t->set_var( "person_delete_checked", $PersonDelete );
    $t->set_var( "company_delete_checked", $CompanyDelete );
    $t->set_var( "user_checked", $UserAdmin );
    $t->set_var( "user_group_checked", $UserGroupAdmin );
    $t->set_var( "person_type_checked", $PersonTypeAdmin );
    $t->set_var( "company_type_checked", $CompanyTypeAdmin );

    $t->set_var( "phone_type_checked", $PhoneTypeAdmin );
    $t->set_var( "address_type_checked", $AddressTypeAdmin );

    $t->set_var( "document_root", $DOC_ROOT );
    $t->pparse( "output", "user_page" );
}
?>
