<?
include_once( "class.INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

include_once( "../classes/eztemplate.php" );
include_once( "../classes/ezsession.php" );
include_once( "../classes/ezusergroup.php" );
include_once( "ezphputils.php" );

include_once( "ezcontact/classes/ezaddresstype.php" );

$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 0 )

{
    
    // Legge til
    if ( $Action == "insert" && ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_AdminAdd" ) == 1 ) ) 
    {
        $type = new eZAddressType();
        $type->setName( $AddressTypeName );
        $type->store();    

        Header( "Location: index.php?page=" . $DOC_ROOT . "addresstypelist.php" );
    }

    // Oppdatere
    if ( $Action == "update" && ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_AdminEdit" ) == 1 ) ) 
    {
        $type = new eZAddressType();
        $type->get( $AID );
        $type->setName( $AddressTypeName );
        $type->update();

        Header( "Location: index.php?page=" . $DOC_ROOT . "addresstypelist.php" );
    }

    // Slette
    if ( $Action == "delete" && ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_AdminDelete" ) == 1 ) )
    {
        $type = new eZAddressType();
        $type->get( $AID );
        $type->delete( );

        Header( "Location: index.php?page=" . $DOC_ROOT . "addresstypelist.php" ); 
    }

    $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "addresstypeedit.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "address_type_edit_page" =>  "addresstypeedit.tpl"
        ) );    

    $t->set_var( "submit_text", "Legg til" );
    $t->set_var( "action_value", "insert" );
    $t->set_var( "address_type_id", "" );
    $t->set_var( "head_line", "Legg til ny addressetype" );

    // Editere
    if ( $Action == "edit" && ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_AdminEdit" ) == 1 ) )
    {
        $type = new eZAddressType();
        $type->get( $AID );
        $type->name( $AddressTypeName );
    
        $t->set_var( "submit_text", "Lagre endringer" );
        $t->set_var( "action_value", "update" );
        $t->set_var( "address_type_id", $AID  );  
        $t->set_var( "head_line", "Rediger addressetype");

        $AddressTypeName = $type->name();
    }

    // Sette template variabler
    $t->set_var( "document_root", $DOC_ROOT );
    $t->set_var( "address_type_name", $AddressTypeName );

    $t->pparse( "output", "address_type_edit_page" );
}

else
{
    Header( "Location: index.php?page=" . $DOC_ROOT . "error.php" );
}
?>
