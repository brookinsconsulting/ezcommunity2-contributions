<?
/*
  Redigerer en notat for bruker
*/

include_once( "classes/class.INIFile.php" );

$ini = new INIFile( "site.ini" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezsession.php" );
include_once( "classes/ezuser.php" );
include_once( "classes/ezusergroup.php" );

include_once(  "ezphputils.php" ); 

include_once( "ezcontact/classes/eznote.php" );

include_once( "ezcontact/topmenu.php" );

// Sjekker rettigheter
$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 0 )
{
    if ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_Read" ) == 1 )
    {

        // Legg til.
        if ( ( $Action == "insert" ) && ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_Add" ) == 1 ) )
        {
            print ( "hmm" );
            die();
            $usr = new eZUser();
            $usr->get( $session->userID() );
    
            $note = new eZNote();
            $note->setTitle( $Title );
            $note->setBody( $Body );
            $note->setUserID( $usr->id() );

            $note->store();
            Header( "Location: index.php?page=" . $DOC_ROOT . "noteslist.php" ); 
        }

        // Oppdatere.
        if ( ( $Action == "update" ) && ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_Edit" ) == 1 ))
        {
            $note = new eZNote();
            $note->get( $NID );
            $note->setTitle( $Title );
            $note->setBody( $Body );
            $note->update( );

            Header( "Location: index.php?page=" . $DOC_ROOT . "noteslist.php" ); 
        }

        // Slette.
        if ( ( $Action == "delete" ) && ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_Delete" ) == 1 ))
        {
            $note = new eZNote();
            $note->get( $NID );
            $note->delete( );

            Header( "Location: index.php?page=" . $DOC_ROOT . "noteslist.php" ); 
        }

        // Sette template.
        $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var ( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "noteedit.php" );
        $t->setAllStrings();

        $t->set_file( array( 
            "note_edit" => "noteedit.tpl"
            ) );

        // Template variabler.
        $t->set_var( "message", "Legg til nytt notat" );
        $action = "insert";
        $t->set_var( "submit_text", "legg til" );

        $t->set_var( "title", "" );
        $t->set_var( "body", "" );

        // Editere notat.
        if ( $Action == "edit" && eZUserGroup::verifyCommand( $session->userID(), "eZContact_Edit" ) == 1 )
        {
            $note = new eZNote();
            $note->get( $NID );

            $t->set_var( "note_id", $NID );
            $t->set_var( "title", $note->title() );
            $t->set_var( "body", $note->body() );

            $action = "update";
            $t->set_var( "submit_text", "Lagre endringer" );
            $t->set_var( "message", "Rediger notat" );
        }

        $t->set_var( "action_value", $action );
        $t->set_var( "document_root", $DOC_ROOT );

        $t->pparse( "output", "note_edit" );
        
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
