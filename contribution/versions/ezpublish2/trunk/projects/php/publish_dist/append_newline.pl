#!/usr/bin/perl
#
# $Id: append_newline.pl,v 1.1 2001/10/19 14:09:02 gl Exp $
#
# Appends a newline to a text file if it doesn't end with one.
#
# Copyright (c) 2001 - eZ systems as
#
# Created on: Created on: <15-May-2001 17:09:11 bf>
# Author: Gunnstein Lye - <gl@ez.no>
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


$VERSION = "0.1";

$Lang = @ARGV[0];


$count = @ARGV;


if ( $count < 1 )
{
    print( "Usage: \n   append_newline.pl [files]\n" );
    exit();
}

foreach $file ( @ARGV )
{
    if ( open( FILE, $file ) )
    {
	while ( <FILE> )
	{
	    $line = $_;
	}
	close FILE;

	if ( $line =~ /\n$/ )
	{
	}
	else
	{
	    if ( open( FILE, ">>$file" ) )
	    {
		print FILE "\n";
		close FILE;
		print( "Appended newline to: " . $file . "\n" );
	    }
	}
    }
}

