<?

class eZLink
{
    /*
      Constructor
    */
    function eZLink( )
    {

    }

    /*
      Lagrer link i databasen
    */
    function store()
    {
        $this->dbInit();
        query( "INSERT INTO Link SET
                ID='$this->ID',
                Title='$this->Title',
                Description='$this->Description',
                LinkGroup='$this->LinkGroup',
                KeyWords='$this->KeyWords',
                Created='$this->Created',
                Url='$this->Url',
                Accepted='$this->Accepted'" );
    }


    /*
      Oppgraderer databasen
    */

    function update()
    {
        $this->dbInit();
        query( "UPDATE Link SET
                Title='$this->Title',
                LinkGroup='$this->LinkGroup',
                KeyWords='$this->KeyWords',
                Created='$this->Created',
                Url='$this->Url',
                Accepted='$this->Accepted'
                WHERE ID='$this->ID'" );
    }

    /*
      Sletter fra databasen
    */

    function delete( )
    {
        $this->dbInit();
        query( "DELETE FROM Link WHERE ID='$this->ID'" );
    }

    /*
      Henter ut informasjon fra databasen hvor ID=$id
    */
    function get ( $id )
    {
        $this->dbInit();
        if ( $id != "" )
        {
            array_query( $link_array, "SELECT * FROM Link WHERE ID='$id'" );
            if ( count( $link_array ) > 1 )
            {
                die( "Feil: flere linker med samme ID ble funnet i databasen, dette skal ikke være mulig." );
            }
            else if ( count( $link_array ) == 1 )
            {
                $this->ID = $link_array[ 0 ][ "ID" ];
                $this->Title = $link_array[ 0 ][ "Title" ];
                $this->Description = $link_array[ 0 ][ "Description" ];
                $this->LinkGroup = $link_array[ 0 ][ "LinkGroup" ];
                $this->KeyWords = $link_array[ 0 ][ "KeyWords" ];
                $this->Created = $link_array[ 0 ][ "Created" ];
                $this->Modified = $link_array[ 0 ][ "Modified" ];
                $this->Accepted = $link_array[ 0 ][ "Accepted" ];
                $this->Url = $link_array[ 0 ][ "Url" ];

            }
        }
    }

    /*
      Henter ut en link gruppe med linkgroup=$linkgroup. Henter kun ut akseptere linker.
    */
    function getByGroup( $id )
    {
        $this->dbInit();
        $link_array = 0;
        
        array_query( $link_array, "SELECT * FROM Link WHERE LinkGroup='$id' AND Accepted='Y'" );

        return $link_array;
    }

        /*
      Henter ut alle linkene i gruppe med linkgroup=$linkgroup. 
    */
    function getByGroup( $id )
    {
        $this->dbInit();
        $link_array = 0;
        
        array_query( $link_array, "SELECT * FROM Link WHERE LinkGroup='$id' AND Accepted='Y'" );

        return $link_array;
    }

            /*
      Henter ut alle linkene i gruppe med linkgroup=$linkgroup. 
    */
    function getNotAccepted( )
    {
        $this->dbInit();
        $link_array = 0;
        
        array_query( $link_array, "SELECT * FROM Link WHERE  Accepted='N'" );

        return $link_array;
    }



    /*
      Henter ut alt fra Link
    */
    function getAll()
    {
        $this->dbInit();
        $group_array = 0;

        array_query( $group_array, "SELECT * FROM Link ORDER BY Title" );

        return $group_array;
    }
    

    /*
      Setter tittel
    */
    function setTitle( $value )
    {
        $this->Title = $value;
    }

    /*
      Setter description
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*
      Setter LinkGroup
    */
    function setLinkGroup( $value )
    {
        $this->LinkGroup = ( $value );
    }

    /*
      Setter KeyWords
    */
    
    function setKeyWords( $value )
    {
        $this->KeyWords = ( $value );
    }

    /*
      Dato når linken ble født
    */
    function setCreated( $value )
    {
        $this->Created = ( $value );
    }

    /*
       Dato på endring
    */
    function setModified( $value )
    {
        $this->Modified = ( $value );
    }

    /*
      Setter om linken er akseptert
    */
    function setAccepted( $value )
    {
        $this->Accepted = ( $value );
    }

    /*
      Setter url
    */
    function setUrl( $value )
    {
        $this->Url = ( $value );
    }

    /*
      Returnerer tittel
    */
    function title()
    {
        return $this->Title;
    }


    /*
      Returnerer description
    */
    function description()
    {
        return $this->Description;
    }

    /*
      Returnerer linkGroup
    */
    function linkGroup()
    {
        return $this->LinkGroup;
    }

    /*
      Retunerer keyWord
    */
    function keyWords()
    {
        return $this->KeyWords;
    }

    /*
      Returnerer Created
    */
    function created()
    {
        return $this->Created;
    }

    /*
      Returnerer Modified
    */
    function modified()
    {
        return $this->Modified;
    }

    /*
      Returnerer Accepted
    */
    function accepted()
    {
        return $this->Accepted;
    }

    /*
      returnerer url
    */
    function url()
    {
        return $this->Url;
    }

    /*
      Returnerer ID
    */
    function id()
    {
        return $this->ID;
    }

    /*
      Initiering av database
    */
    function dbInit()
    {
        require "ezlink/dbsettings.php";
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }

    var $ID;
    var $Title;
    var $Description;
    var $LinkGroup;
    var $KeyWords;
    var $Created;
    var $Modified;
    var $Accepted;
    var $Url;
}


?>
