<?
class eZPersonConsultDict
{
    /*
      Constructor
    */
    function eZPersonConsultDict()
    {
        
    }

    /*
      Lagrer en person->consult link i databasen.
    */
    function store()
    {
        $this->dbInit();

        query( "INSERT INTO eZContact_PersonConsultDict set PersonID='$this->PersonID', ConsultID='$this->ConsultID'" );
        return mysql_insert_id();
    }

    /*
      Henter ut alle konsultasjoner lagret i databasen hvor PersonID == $id.
    */
    function getByPerson( $id )
    {
        $this->dbInit();

        array_query( $consult_array,"SELECT * FROM eZContact_PersonConsultDict WHERE PersonID='$id'" );

        return $consult_array;
    }

    /*
      Henter ut konsultasjon med ID == $id
    */
    function getByConsult( $id )
    {
        $this->dbInit();
        if ( $id != "" )
        {
            array_query( $dict_array, "SELECT * FROM eZContact_PersonConsultDict WHERE ConsultID='$id'" );
            if ( count( $dict_array ) > 1 )
            {
                die( "Feil: flere dickter med samme id ble funnet" );
            }
            else if ( count( $dict_array ) == 1 )
            {
                $this->ID = $dict_array[ 0 ][ "ID" ];
                $this->PersonID = $dict_array[ 0 ][ "PersonID" ];
                $this->ConsultID = $dict_array[ 0 ][ "ConsultID" ];
            }
        }
    }
    
    /*
      Sletter dict med ID == $id.
    */
    function delete()
    {
        $this->dbInit();

        query( "DELETE FROM eZContact_PersonConsultDict WHERE ID='$this->ID'" );
    }
    
    /*
      Setter personID variablen.
    */
    function setPersonID( $value )
    {
        $this->PersonID = $value;
    }

    /*
      Setter consultID variablen.
    */
    function setConsultID( $value )
    {
        $this->ConsultID = $value;
    }

    /*
      Returnerer personID'en.
    */
    function personID()
    {
        return $this->PersonID;
    }
    /*
      Returnerer consultID'en.
    */
    function consultID()
    {
        return $this->ConsultID;
    }

    /*
      Privat: Initialisering av database.
    */
    function dbInit()
    {
        require "ezcontact_ce/dbsettings.php";
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }


    var $ID;
    var $PersonID;
    var $ConsultID;

}

?>
