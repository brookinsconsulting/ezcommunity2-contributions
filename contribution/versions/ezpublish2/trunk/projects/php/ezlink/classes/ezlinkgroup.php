<?
class eZLinkGroup
{
    /*
      Counstructor
    */
    function eZLinkGroup( )
    {

    }

    /*
      Lager linkgruppe i databasen
    */
    function store()
    {
        $this->dbInit();
        query( "INSERT INTO eZLink_LinkGroup SET
                ID='$this->ID',
                Title='$this->Title',
                Parent='$this->Parent'" );
    }

    /*
      Oppgraderer databasen
    */
    function update()
    {
        $this->dbInit();
        query( "UPDATE eZLink_LinkGroup SET 
                Title='$this->Title',
                Parent='$this->Parent'
                WHERE ID='$this->ID'" );
    }

    /*
      Slette fra databasen
    */
    function delete( )
    {
        $this->dbInit();
        query( "DELETE FROM eZLink_LinkGroup WHERE ID='$this->ID'" );
    }

    /*
      Henter ut alle gruppene fra databasen.
    */
    function get( $id )
    {
        $this->dbInit();
        array_query( $linkgroup_array,  "SELECT * FROM eZLink_LinkGroup WHERE ID='$id'" );
        if ( count( $linkgroup_array ) > 1 )
        {
            die( "feil, flere grupper med samme id" );
        }
        else if ( count( $linkgroup_array ) == 1 )
        {
            $this->ID = $linkgroup_array[ 0 ][ "ID" ];
            $this->Title = $linkgroup_array[ 0 ][ "Title" ];
            $this->Parent = $linkgroup_array[ 0 ][ "Parent" ];
        }
    }

    /*
      Rekursiv funksjon, skriver ut hele pathen til gruppen.
    */

    function printPath( $id, $url )
    {
        $lg = new eZLinkGroup();
        $lg->get( $id );

        $path = "";

        if ( $lg->parent() != 0 )
        {
            $path .= $this->printPath( $lg->parent(),  $url );
        }
        else
        {
            $path .= "<img src=\"ezlink/images/pil.gif\" border=\"0\" height=\"10\" width=\"10\"> <a href=\"index.php?page=$url&LGID=0\">" . "kategorier" . "</a>";
        }
        $path .= " <img src=\"ezlink/images/pil.gif\" border=\"0\" height=\"10\" width=\"10\"> <a href=\"index.php?page=$url&LGID=$id\">" . $lg->title() . "</a>";
        return $path;
    }


    /*
      Henter ut parent
    */

    function getByParent( $id )
    {
        $this->dbInit();
        $parent_array = 0;

        array_query( $parent_array, "SELECT * FROM eZLink_LinkGroup WHERE Parent='$id'" );

        return $parent_array;
    }

    /*
      Returnerer antall linker i alle underkategoriene.
     */
    function getTotalSubLinks( $id, $start_id )
    {
        $count = 0;
        $sibling_array = $this->getByParent( $id );

        if ( $id == $start_id )
        {
            array_query( $link_count, "SELECT COUNT(ID) AS LinkCount FROM eZLink_Link WHERE LinkGroup='$id' AND Accepted='Y'" );
            $count += $link_count[0][ "LinkCount" ];
        }
        
        for ( $i=0; $i<count( $sibling_array ); $i++ )
        {
            $group_id =  $sibling_array[ $i][ "ID" ];
            $count += $this->getTotalSubLinks( $group_id, $start_id );
            array_query( $link_count, "SELECT COUNT(ID) AS LinkCount FROM eZLink_Link WHERE LinkGroup='$group_id' AND Accepted='Y'" );
            $count += $link_count[0][ "LinkCount" ];            
        }
        return $count;
    }

    /*
      Returnerer antall nye linker i alle underkategoriene.
      Alle linker som er nyere enn $new_limit antall dager blir regnet som nye.
     */
    function getNewSubLinks( $id, $start_id, $new_limit )
    {

        $count = 0;
        $sibling_array = $this->getByParent( $id );

        if ( $id == $start_id )
        {
            array_query( $link_count, "SELECT COUNT( ID ) AS LinkCount from eZLink_Link WHERE LinkGroup='$id' AND Accepted='Y' AND ( TO_DAYS( Now() ) - TO_DAYS( Created ) ) <= $new_limit  ORDER BY Title" );
            $count += $link_count[0][ "LinkCount" ];
        }
        
        for ( $i=0; $i<count( $sibling_array ); $i++ )
        {
            $group_id =  $sibling_array[ $i][ "ID" ];
            $count += $this->getNewSubLinks( $group_id, $start_id, $new_limit );
            array_query( $link_count, "SELECT COUNT( ID ) AS LinkCount  from eZLink_Link WHERE LinkGroup='$group_id' AND Accepted='Y' AND ( To_DAYS( Now() ) - TO_DAYS( Created ) ) <= $new_limit  ORDER BY Title" );
            $count += $link_count[0][ "LinkCount" ];            
        }
        return $count;
    }

    /*
      Returnerer antall i incoming.
     */
    function getTotalIncomingLinks()
    {
        $count = 0;
        array_query( $link_count, "SELECT COUNT(ID) AS LinkCount FROM eZLink_Link WHERE Accepted='N'" );
        $count = $link_count[0][ "LinkCount" ];
        return $count;
    }
    
    /*
      Henter ut _alt_
    */
    function getAll()
    {
        $this->dbInit();
        $parnet_array = 0;

        array_query( $parent_array, "SELECT * FROM eZLink_LinkGroup ORDER BY Title" );

        return $parent_array;
    }

    /*
      Setter navn.
    */

    function setTitle( $value )
    {
        $this->Title = ( $value );
    }

    /*
      Setter parent.
    */
    function setParent( $value )
    {
        $this->Parent = ( $value );
    }

    /*
      returnerer navn.
    */

    function Title()
    {
        return $this->Title;
    }

    /*
      returnerer parent.
    */
    function parent()
    {
        return $this->Parent;

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
var $Parent;


}

?>


