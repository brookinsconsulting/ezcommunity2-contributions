<?
/*
  Editerer en kontakt type
*/
include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezsession.php" );
include_once( "classes/ezusergroup.php" );
include_once( "common/ezphputils.php" );

include_once( "../ezcontact/classes/ezphonetype.php" );

// Sjekker rettigheter
$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 0 )
{
    if ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_Read" ) == 1 )
    {
        // Legge til
        if ( $Action == "insert" && ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_AdminAdd" ) == 1 ) )
        {
        
            $type = new eZPhoneType();
            $type->setName( $PhoneTypeName );
            $type->store();

            Header( "Location: index.php?page=" . $DOC_ROOT . "phonetypelist.php" ); 
        }

        // Oppdatere
        if ( $Action == "update" && ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_AdminEdit" ) == 1 ) )
        {
            $type = new eZPhoneType();
            $type->get( $PID );
            print ( "$PID" );
            $type->setName( $PhoneTypeName );
            $type->update();

            Header( "Location: index.php?page=" . $DOC_ROOT . "phonetypelist.php" ); 
        }

        // Slette
        if ( $Action == "delete" && ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_AdminDelete" )  == 1 ) )
        {
            $type = new eZPhoneType();
            $type->get( $PID );
            $type->delete( );

            Header( "Location: index.php?page=" . $DOC_ROOT . "phonetypelist.php" ); 
        }

        $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "phonetypeedit.php" );
        $t->setAllStrings();

        $t->set_file( array(
            "phone_type_edit_page" => "phonetypeedit.tpl"
            ) );    

        $t->set_var( "submit_text", "Legg til" );
        $t->set_var( "action_value", "insert" );
        $t->set_var( "phone_type_id", "" );
        $t->set_var( "head_line", "Legg til nytt kontaktmedium" );

        // Editere
        if ( $Action == "edit" )
        {
            $type = new eZPhoneType();
            $type->get( $PID );
            $type->name( $PhoneTypeName );

            $t->set_var( "submit_text", "Lagre endringer" );
            $t->set_var( "action_value", "update" );
            $t->set_var( "phone_type_id", $PID  );
            $t->set_var( "head_line", "Rediger kontaktmedium" );

            $PhoneTypeName = $type->name();
        }

        // Sette template variabler
        $t->set_var( "document_root", $DOC_ROOT );
        $t->set_var( "phone_type_name", $PhoneTypeName );

        $t->pparse( "output", "phone_type_edit_page" );
    }
    else
    {
        print( "\nDu har ikke rettigheter\n" );
    }
}
else
{
    Header( "Location: index.php?page=common/error.php" );
}


?>
