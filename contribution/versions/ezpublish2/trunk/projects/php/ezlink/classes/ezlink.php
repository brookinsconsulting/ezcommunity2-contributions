<?
/*!
    $Id: ezlink.php,v 1.22 2000/09/07 15:44:45 bf-cvs Exp $

    Author: B�rd Farstad <bf@ez.no>
    
    Created on: 
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

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
        // setter created til tiden p� systemklokken.
        $this->Created = date( "Y-m-d G:i:s" );        
        query( "INSERT INTO eZLink_Link SET
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
        query( "UPDATE eZLink_Link SET
                Title='$this->Title',
                LinkGroup='$this->LinkGroup',
                KeyWords='$this->KeyWords',
                Url='$this->Url',
                Accepted='$this->Accepted'
                WHERE ID='$this->ID'" );
    }

    /*
      Sletter linker og tilh�rende hits.
    */
    function delete( )
    {
        $this->dbInit();
        query( "DELETE FROM eZLink_Hit WHERE Link='$this->ID'" );        
        query( "DELETE FROM eZLink_Link WHERE ID='$this->ID'" );
    }

    /*
      Henter ut informasjon fra databasen hvor ID=$id
    */
    function get ( $id )
    {
        $this->dbInit();
        if ( $id != "" )
        {
            array_query( $link_array, "SELECT * FROM eZLink_Link WHERE ID='$id'" );
            if ( count( $link_array ) > 1 )
            {
                die( "Feil: flere linker med samme ID ble funnet i databasen, dette skal ikke v�re mulig." );
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
        
        array_query( $link_array, "SELECT * FROM eZLink_Link WHERE LinkGroup='$id' AND Accepted='Y' ORDER BY Title" );

        return $link_array;
    }

        /*
      Henter ut alle linkene i gruppe med linkgroup=$linkgroup. 
    */
    function getByGroup( $id )
    {
        $this->dbInit();
        $link_array = 0;
        
        array_query( $link_array, "SELECT * FROM eZLink_Link WHERE LinkGroup='$id' AND Accepted='Y' ORDER BY Title" );

        return $link_array;
    }

    /*
      Henter ut alle linkene i gruppe med linkgroup=$linkgroup. 
    */
    function getNotAccepted( )
    {
        $this->dbInit();
        $link_array = 0;
        
        array_query( $link_array, "SELECT * FROM eZLink_Link WHERE Accepted='N' ORDER BY Title" );

        return $link_array;
    }

        /*
      Henter ut de 10 siste linkene med accepted = yes
    */
    function getLastTenDate( $limit, $offset )
    {
        $this->dbInit();
        $link_array = 0;
        
        array_query( $link_array, "SELECT * FROM eZLink_Link WHERE Accepted='Y' ORDER BY Created DESC LIMIT $offset, $limit" );

        return $link_array;
    }

    /*
      Henter ut de 10 siste linkene med accepted = yes
    */
    function getLastTen( $limit, $offset )
    {
        $this->dbInit();
        $link_array = 0;
        
        array_query( $link_array, "SELECT * FROM eZLink_Link WHERE Accepted='Y' ORDER BY Title DESC LIMIT $offset, $limit" );

        return $link_array;
    }

    /*
      Henter linkene som matcher $query.
    */
    function getQuery( $query, $limit=20, $offset = 0 )
    {
        $this->dbInit();
        $link_array = 0;

        $query = new eZQuery( array( "KeyWords", "Title", "Description" ), $query );
        
        $query_str = "SELECT * FROM eZLink_Link WHERE (" .
             $query->buildQuery()  .
             ") AND Accepted='Y' ORDER BY Title";

        if ( $limit != -1 )
        {
//              $query_str .= "  LIMIT $offset, $limit";
        }

        array_query( $link_array, $query_str );

//          print( "<br>" . $query_str . "<br>". count( $link_array ) );
        
        return $link_array;
    }

    /*
      Henter ut alt fra Link
    */
    function getAll()
    {
        $this->dbInit();
        $group_array = 0;

        array_query( $group_array, "SELECT * FROM eZLink_Link ORDER BY Title" );

        return $group_array;
    }

    /*
      Sjekker om urlen eksisterer
    */
    function checkUrl( $url )
    {
        $this->dbInit();

        array_query( $url_array, "SELECT url FROM eZLink_Link WHERE url='$url'" );

        return count( $url_array );
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
       Dato p� endring
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
        include_once( "classes/INIFile.php" );

        $ini = new INIFile( "site.ini" );
        
        $SERVER = $ini->read_var( "eZLinkMain", "Server" );
        $DATABASE = $ini->read_var( "eZLinkMain", "Database" );
        $USER = $ini->read_var( "eZLinkMain", "User" );
        $PWD = $ini->read_var( "eZLinkMain", "Password" );
        
//        include( "ezlink/dbsettings.php" );
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
    var $url_array;
}


?>
