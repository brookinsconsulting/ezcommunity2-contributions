<?

//!! eZContact
//!
/*!

*/

class eZZip
{
    /*
      Constructor.
     */
    function eZZip()
    {

    }

    /*
      
     */
    function store()
    {
        $this->dbInit();
        query( "INSERT INTO eZContact_Person set Code='$this->ID',
		Place='$this->Place' " );
        return mysql_insert_id();
    }

    /*
      Henter ut person med ID == $id
    */  
    function get( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            array_query( $zip_array, "SELECT * FROM eZContact_Zip WHERE Code='$id'" );
            if ( count( $zip_array ) > 1 )
            {
                die( "Feil: Flere ziper med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $zip_array ) == 1 )
            {
                $this->ID = $zip_array[ 0 ][ "Code" ];
                $this->Place = $zip_array[ 0 ][ "Place" ];
            }
        }
    }

    /*!
      Setter stedet.
     */
    function setPlace( $value )
    {
        $this->Place = $value;
    }

    /*!
      Returnerer stedet.
     */
    function place( )
    {
        return $this->Place;
    }

    /*!
      Privat funksjon, skal kun brukes av ezusergroup klassen.
      Funksjon for å åpne databasen.
    */
    function dbInit()
    {
        include_once( "classes/INIFile.php" );

        $ini = new INIFile( "site.ini" );
        
        $SERVER = $ini->read_var( "site", "Server" );
        $DATABASE = $ini->read_var( "site", "Database" );
        $USER = $ini->read_var( "site", "User" );
        $PWD = $ini->read_var( "site", "Password" );
        
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }

    var $ID;
    var $Place;
}

?>
