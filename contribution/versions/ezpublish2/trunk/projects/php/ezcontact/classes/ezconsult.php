<?
class eZConsult
{
    /*
      Constructor.
    */
    function eZConsult()
    {

    }

    /*
      Lagre i databasen.
    */
    function store()
    {
        $this->dbInit();
        query( "INSERT INTO eZContact_Consult SET
                ID='$this->ID',
                Title='$this->Title',
                UserID='$this->UserID',
                Created=NOW(),
                Body='$this->Body' ");
        return mysql_insert_id();
    }

    /*
      Sletter fra databasen.
    */
    function delete()
    {
        $this->dbInit();
        query( "DELETE FROM eZContact_Consult WHERE ID='$this->ID' ");
    }

    /*
      Oppdaterer databasen.
    */
    function update()
    {
        $this->dbInit();
        query( "UPDATE eZContact_Consult SET
                ID='$this->ID',
                Title='$this->Title',
                Body='$this->Body',
                UserID='$this->UserID',
                Modified=NOW()
                WHERE ID='$this->ID' ");
    }

    /*
      Heter ut konsultasjon fra datavasen med ID == $id
    */
    function get( $id )
    {
        $this->dbInit();
        if ( $id != "" )
        {
            array_query( $consult_array, "SELECT * FROM eZContact_Consult WHERE ID='$id'" );
            if ( count( $consult_array ) > 1 )
            {
                die( " Feil: flere konsultasjoner med samme id" );
            }
            else if ( count( $consult_array ) == 1 )
            {
                $this->ID = $consult_array[ 0 ][ "ID" ];
                $this->Title = $consult_array[ 0 ][ "Title" ];
                $this->Body = $consult_array[ 0 ][ "Body" ];
                $this->UserID = $consult_array[ 0 ][ "UserID" ];
                $this->Created = $consult_array[ 0 ][ "Created" ];
                $this->Modified = $consult_array[ 0 ][ "Modified" ];
            }

        }

    }

    /*
      Henter ut alle konsultasjonene i databasen.
    */
    function getAll()
    {
        $this->dbInit();
        $consult_array = 0;

        array_query( $consult_array, "SELECT * FROM eZContact_Consult ORDER BY Title" );

        return $consult_array;
    }

    /*
      Setter tittel.
    */
    function setTitle( $value )
    {
        $this->Title = $value;
    }

    /*
      Setter Body.
    */
    function setBody ( $value )
    {
        $this->Body = $value;
    }

    /*
      Setter bruker.
    */
    function setUserID ( $value )
    {
        $this->UserID = $value;
    }

    /*
      Returnerer bruker.
    */
    function userID()
    {
        return $this->UserID;
    }
    /*
      Returnerer når konsulasjonen ble opprettet.
    */
    function created()
    {
        return $this->Created;
    }
    /*
      Returnerer når konsultasjonen ble sist endret.
    */
    function modified()
    {
        return $this->Modified;
    }

    /*
      Retunerer tittel.
    */
    function title()
    {
        return $this->Title;
    }

    /*
      Returnerer body.
    */
    function body()
    {
        return $this->Body;
    }

    /*
      Returnerer ID
    */
    function id()
    {
        return $this->ID;
    }
        

    /*
      Privat: Initiering av database.
    */
    function dbInit()
    {
        require "ezcontact_ce/dbsettings.php";
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }

    var $ID;
    var $Title;
    var $Body;
    var $UserID;
    var $Created;
    var $Modified;

}

?>
