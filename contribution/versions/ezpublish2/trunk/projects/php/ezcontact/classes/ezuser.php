<?
class eZUser
{    
    /*!
      Constructor.
    */    
    function eZUser(  )      
    {      
    }

    /*!
      Constructor.
    */    
    

    /*!
      Validerer  brukernavn og passord. Returnerer 1 dersom
      brukernavnet og passordet er gyldig. 0 hvis ikke.
    */
    function validate()
    {
        $this->dbInit();
        $ret = 0;

        $result = query(  "SELECT * FROM eZContact_Usr WHERE Login='$this->User' AND Pwd=PASSWORD('$this->Password')" );

        if ( mysql_num_rows( $result )  == 1 )
        {
            $ret = 1;
            $this->ID = mysql_result( $result, 0, "ID" );
        }
        return $ret;
    }

    /*
      Lagrer en ny bruker i databasen.
    */  
    function store()
    {
        $this->dbInit();
        query( "INSERT INTO eZContact_Usr set Login='$this->User', Pwd=PASSWORD('$this->Password'), Grp='$this->Group'" );
        return mysql_insert_id();
    }

    /*
      Oppdaterer bruker i databasen.
    */  
    function update()
    {
        $this->dbInit();
        query( "Update eZContact_Usr set Login='$this->User', Grp='$this->Group' WHERE ID='$this->ID'" );

        if ( $this->Password != "" )
        {
            query( "Update eZContact_Usr set Pwd=PASSWORD('$this->Password')  WHERE ID='$this->ID'" );
        }
        
    }
  
    /*
      Sletter bruker i databasen.
    */  
    function delete()
    {
        $this->dbInit();
        query( "DELETE FROM eZContact_Usr WHERE ID='$this->ID'" );
    }

    /*!
      Henter ut en bruker med ID=$id fra databasen.    
    */
    function get( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            array_query( $user_array, "SELECT * FROM eZContact_Usr WHERE ID='$id'" );
            if ( count( $user_array ) > 1 )
            {
                die( "Feil: Flere userer med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $user_array ) == 1 )
            {
                $this->ID = $user_array[ 0 ][ "ID" ];
                $this->User = $user_array[ 0 ][ "Login" ];
                $this->Group = $user_array[ 0 ][ "Grp" ];
            }
        }
    }

    /*
      Henter ut alle brukerene lagret i databasen.
    */
    function getAll( )
    {
        $this->dbInit();    
        $user_array = 0;
    
        array_query( $user_array, "SELECT * FROM eZContact_Usr ORDER BY Login" );
    
        return $user_array;
    }    

    /*!
      Setter brukergruppe.
    */
    function setGroup( $value )
    {
        $this->Group = $value;    
    }
    /*!
      Setter Login.
    */
    function setLogin( $value )
    {
        $this->User = $value;    
    }
    
    /*!
      Setter Passord.
    */
    function setPassword( $value )
    {
        $this->Password = $value;    
    }


    /*
      Returnerer ID'en til brukeren.
    */
    function id()
    {
        return $this->ID;
    }
  
    /*!
      Returnerer brukernavnet.
    */
    function login()
    {
        return $this->User;
    }

    /*!
      Returnerer gruppenavnet.
    */
    function group()
    {
        return $this->Group;
    }


    /*!
      Initiering av database.
    */
    function dbInit()
    {
        require "ezcontact_ce/dbsettings.php";
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }

    var $ID;
    var $User;
    var $Password;
    var $Group;
}
?>
