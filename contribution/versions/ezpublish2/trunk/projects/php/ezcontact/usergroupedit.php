<?
include  "template.inc";
require "ezphputils.php";
require "ezsession.php";
require "ezuser.php";
require "ezusergroup.php";


// sjekke session
{
  include( "checksession.php" );
}

$menuTemplate = new Template( "." );
$menuTemplate->set_file( "user_page", "templates/usergroupedit.tpl" );               

$menuTemplate->set_var( "submit_text", "Legg til" );
$menuTemplate->set_var( "action_value", "insert" );
$menuTemplate->set_var( "user_group_id", "" );

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
}

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
  
  $menuTemplate->set_var( "submit_text", "Lagre endringer" );  
  $menuTemplate->set_var( "action_value", "update" );
  $menuTemplate->set_var( "user_group_id", $UGID  );  
}

$menuTemplate->set_var( "user_group_name", $Name );
$menuTemplate->set_var( "user_group_description", $Description );

$menuTemplate->set_var( "user_checked", $UserAdmin );
$menuTemplate->set_var( "user_group_checked", $UserGroupAdmin );
$menuTemplate->set_var( "person_type_checked", $PersonTypeAdmin );
$menuTemplate->set_var( "company_type_checked", $CompanyTypeAdmin );

$menuTemplate->set_var( "phone_type_checked", $PhoneTypeAdmin );
$menuTemplate->set_var( "address_type_checked", $AddressTypeAdmin );

$menuTemplate->pparse( "output", "user_page" );
?>
