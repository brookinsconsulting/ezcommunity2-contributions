#!/usr/bin/perl
#
# $Id: check_lang.pl,v 1.1.2.1 2001/11/15 16:32:35 bf Exp $
#
# This script checks the eZ publish distribution for missing
# language files. en_GB is used as reference language.
#
# Copyright (c) 2001 - eZ systems as
#
# Created on: Created on: <15-May-2001 17:09:11 bf>
# Author: Bård Farstad - <bf@ez.no>
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License as
# published by the Free Software Foundation; either version 2 of
# the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
#


use Fcntl;
use File::Find;
use File::Path;
use File::Basename;
use File::Copy;
use Getopt::Std;
use Cwd; 


$VERSION = "0.5";

$Lang = @ARGV[0];


$count = @ARGV;


if ( $count != 1 )
{
    print( "Usage: \n   check_lang.pl [iso_code]\nE.g.\n   check_lang.pl no_NO\n" );
    exit();
}

opendir( D, ".");
foreach $module ( readdir(D) )
{
    if ( $module =~ /^ez[^\.]+$/ )
    {
	print( "\nModule: " . $module . "\n\n" );
	checkModule( "user" );
	checkModule( "admin" );

    }
}
close(D);


sub checkModule
{
    $part = @_[0];

    opendir( Intl, $module . "/$part/intl/en_GB/" );

    # check language files
    foreach $intl_file ( readdir(Intl) )
    {
	if ( $intl_file =~ /^[^\.].*\.php\.ini$/ )
	{
#		print( "    " . $intl_file . "\n" );
	    
	    # check if the language file exists
	    open ( Reference, $module . "/$part/intl/en_GB/" . $intl_file ) or die "Can't open language file";

	    if ( open ( Check, $module . "/$part/intl/". $Lang . "/" . $intl_file )  )
	    {		    
		@ref_lines = <Reference>; 
		@check_lines = <Check>; 

		@ref_array = ();
		@check_array = ();

		$i = 0;
		foreach $line ( @ref_lines )
		{
		    if ( $i > 0 )
		    {
			$tmpLine = $line;
			$tmpLine =~ s/^(.*?)=.*$/$1/;

#			    print( $tmpLine ."\n" );
			push( @ref_array, $tmpLine );
		    }
		    $i++;
		}
		push( @ref_array, "\n" );


		$i = 0;
		foreach $line ( @check_lines )
		{
		    if ( $i > 0 )
		    {
			$tmpLine = $line;
			$tmpLine =~ s/^(.*?)=.*$/$1/;

			if ( $tmpLine =~ /\S/ )    # if the line has non-whitespace characters
			{
			    $dupl = 0;
			    foreach $checkItem ( @check_array )    # check for duplicate entries
			    {
				if ( $tmpLine eq $checkItem )
				{
				    chomp $checkItem;
				    print( "Duplicate key : " . $module . "/$part/intl/". $Lang . "/" . $intl_file . " :: " . $checkItem . "\n"  );
				    $dupl = 1;
				}
			    }
			    if ( $dupl == 0 )
			    {
				push( @check_array, $tmpLine );
			    }
#			    print( $i . " " . $line );
			}
		    }
		    $i++;
		}
		push( @check_array, "\n" );

		foreach $refItem ( @ref_array ) 
		{
		    $found = 0;
		    foreach $checkItem ( @check_array ) 
		    {
			if ( $refItem eq $checkItem )
			{
			    $found = 1;
			}
		    }
		    # don't check whitespace strings and comments
		    if ( $found == 0 && $refItem =~ /\S/ && $refItem =~ /^[^\#]/ )
		    {
			chomp $refItem;
			print( "Missing key : " . $module . "/$part/intl/". $Lang . "/" . $intl_file . " :: " . $refItem . "\n"  );
		    }
		}

#		print( "       Checking strings: "  );
	    }
	    else
	    {
		print( "Missing file : " . $module . "/$part/intl/" . $Lang . "/" . $intl_file . "\n" );
	    }
	}
    }

    close(Intl);

}
