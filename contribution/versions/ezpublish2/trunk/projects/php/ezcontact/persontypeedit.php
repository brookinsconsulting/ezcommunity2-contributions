<?
/*
  Redigerer person typer.
*/

include_once( "class.INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezsession.php" );
include_once( "classes/ezusergroup.php" );
include_once( "ezphputils.php" );

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezpersontype.php" );

// Sjekker rettigheter
$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 0 )
{
    if ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_Read" ) == 1 )
    {


// Legge til
        if ( $Action == "insert" && ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_AdminAdd" ) == 1 ) )
        {
            $type = new eZPersonType();
            $type->setName( $PersonTypeName );
            $type->setDescription( $PersonTypeDescription );
            $type->store();

            Header( "Location: index.php?page=" . $DOC_ROOT . "persontypelist.php" ); 
        }

        // Oppdatere
        if ( $Action == "update" && ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_AdminEdit" ) == 1 ) )
        {
            $type = new eZPersonType();
            $type->get( $PID );
            print ( "$PID ..." );

            $type->setName( $PersonTypeName );
            $type->setDescription( $PersonTypeDescription );
            $type->update();

            Header( "Location: index.php?page=" . $DOC_ROOT . "persontypelist.php" ); 
        }

        // Slette
        if ( $Action == "delete" && ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_AdminDelete" ) == 1 ) )
        {
            $type = new eZPersonType();
            $type->get( $PID );
            $type->delete( );
            Header( "Location: index.php?page=" . $DOC_ROOT . "persontypelist.php" ); 
        }

        $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "persontypeedit.php" );
        $t->setAllStrings();

        $t->set_file( array(
            "persontype_edit_page" => "persontypeedit.tpl"
            ) );    

        $t->set_var( "submit_text", "Legg til" );
        $t->set_var( "action_value", "insert" );
        $t->set_var( "persontype_id", "" );
        $t->set_var( "head_line", "Legg til ny persontype" );

        // Editere
        if ( $Action == "edit" && ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_AdminEdit" ) == 1 ) )
        {
            $type = new eZPersonType();
            $type->get( $PID );
  
            $PersonTypeName = $type->name();
            $PersonTypeDescription = $type->description();

            $t->set_var( "submit_text", "Lagre endringer" );
            $t->set_var( "action_value", "update" );
            $t->set_var( "persontype_id", $PID );
            $t->set_var( "head_line", "Rediger persontype" );

        }

        // Sette tempalte variabler
        $t->set_var( "document_root", $DOC_ROOT );
        $t->set_var( "persontype_name", $PersonTypeName );
        $t->set_var( "description", $PersonTypeDescription );

        $t->pparse( "output", "persontype_edit_page" );
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
