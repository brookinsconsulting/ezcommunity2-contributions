#!/bin/sh

#
# ezinstaller - version 1.4 - (c) 2001 Kai Dübbert <kai@duebbert.de> - Licence: GPL
# =================================================================================
#
# This shell script will install eZ publish (http://publish.ez.no) on a Linux 
# (or Unix) system.
#
#---------------------------------------------------------------------------
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
#---------------------------------------------------------------------------
#
# 1.4: - fixed to have it work with older Bash
#


############################################################################
# Default values
# 
DEF_INSTDIR=/usr/local/ezpublish
DEF_WWWDIR=/var/www
DEF_OWNER=www-data
DEF_GROUP=www-data
DEF_DBNAME=publish
DEF_DBUSER=publish
DEF_TITLE="eZ publish"
DEF_DBSERVER=localhost
DEF_URLDIR=/

############################################################################
#Let's go
#
echo "ezinstaller.sh - version 1.4 - (c) 2001 Kai Dübbert <kai@duebbert.de>"
echo ""
echo "This tool will help you install eZ publish on your server."
echo ""
echo "Licence: GPL"
echo ""
echo "##################################################################"


############################################################################
# Classic install or new install?
#
function install_q {
	echo ""
	echo "#################################################################"
	echo "Old install mechanism or new install mechanism:"
	echo "You can install eZ publish the old way (virtual hosts, mod_rewrite)"
	echo "or the new way (no virtual hosts needed, no mod_rewrite needed,"
	echo "no root access needed, installation in subdirectories possible)."
	echo ""
	echo -n "Which mechanism do you want to use? (new|old): "
	read Q
	if [ "$Q" = "new" ]; then
		C_INSTALL=new
	elif [ "$Q" = "old" ]; then
		C_INSTALL=old
		if [ `whoami` !=  "root" ]; then
		    echo "For the old install you must be root. (The new install"
			echo "doesn't care as much.)"
		    exit 0
		fi
	else
		echo "No no no, please choose \"new\" or \"old\"."
		install_q
	fi
}
install_q


############################################################################
# Both methods: Ask some configuration questions
#
echo ""
echo "#################################################################"
echo "Configuration questions:"
echo "Now some questions to setup your site.ini file. Have a look at it"
echo "when I am finished, because all I try is to get you a working"
echo "setup!"
echo ""

# Hostname
DEF_HOSTNAME="$(hostname -f)"
echo -n "Hostname of your server [$DEF_HOSTNAME]: "
read C_HOSTNAME
if [ "$C_HOSTNAME" = "" ]; then
	C_HOSTNAME=$DEF_HOSTNAME
fi

# IP-Address
if [ "$C_INSTALL" = "old" ]; then
	echo -n "IP-Address of your server: "
	read C_IPADDRESS
fi

# Title
echo -n "Title of your pages [$DEF_TITLE]: "
read C_TITLE
if [ "$C_TITLE" = "" ]; then
	C_TITLE=$DEF_TITLE
fi

# DB server
echo -n "Database server [$DEF_DBSERVER]: "
read C_DBSERVER
if [ "$C_DBSERVER" = "" ]; then
	C_DBSERVER=$DEF_DBSERVER
fi

# DB name
echo -n "Database name [$DEF_DBNAME]: "
read C_DBNAME
if [ "$C_DBNAME" = "" ]; then
	C_DBNAME=$DEF_DBNAME
fi

# DB user
echo -n "Database user [$DEF_DBUSER]: "
read C_DBUSER
if [ "$C_DBUSER" = "" ]; then
	C_DBUSER=$DEF_DBUSER
fi

# DB pass
echo -n "Password for user \"$C_DBUSER\": "
read C_DBPASS

# For old install
if [ "$C_INSTALL" = "old" ]; then
	# Owner of Webserver
	echo -n "Owner of webserver [$DEF_OWNER]: "
	read C_OWNER
	if [ "$C_OWNER" = "" ]; then
		C_OWNER=$DEF_OWNER
	fi

fi
	
# Group of Webserver
echo -n "Group of webserver [$DEF_GROUP]: "
read C_GROUP
if [ "$C_GROUP" = "" ]; then
	C_GROUP=$DEF_GROUP
fi
	
# Web directory
echo -n "Web directory [$DEF_WWWDIR]: "
read C_WWWDIR
if [ "$C_WWWDIR" = "" ]; then
	C_WWWDIR=$DEF_WWWDIR
fi
if [ ! -d "$C_WWWDIR" ]; then
	echo -n "$C_WWWDIR does not exist. Creating it... "
	mkdir -p $C_WWWDIR
	echo "done."
else
	touch $C_WWWDIR/test_delete_me
	if [ ! $? = 0 ]; then
		echo "Don't seem to have the right permissions for $C_WWWDIR!"
		echo "Check and start ezinstall.sh again!"
		exit 1
	else
		rm $C_WWWDIR/test_delete_me
	fi
fi

# New install: Installdir
if [ "$C_INSTALL" = "new" ]; then
	echo "-----------------------------------------------------------------"
	echo "New install: the url to the web directory"
	echo -n "URL directory [$DEF_URLDIR]: "
	read C_URLDIR
	if [ "$C_URLDIR" = "" ]; then
		C_URLDIR=$DEF_URLDIR
	fi
	# add a slash if needed!
	if ! echo $C_URLDIR | grep /$ > /dev/null; then
		C_URLDIR=$C_URLDIR/
		echo "slash added"
	fi
	C_WWWDIR="$C_WWWDIR$C_URLDIR"

	echo "-----------------------------------------------------------------"
	echo "New install: now I need a directory where you want me"
	echo "to put the eZ publish files. This should *NOT* be"
	echo "the same as your public www directory! This is important"
	echo "for security!"
	echo -n "Site directory [$DEF_INSTDIR]: "
	read C_INSTDIR
	if [ "$C_INSTDIR" = "" ]; then
		C_INSTDIR=$DEF_INSTDIR
	fi
	if [ ! -d "$C_INSTDIR" ]; then
		echo -n "$C_INSTDIR does not exist. Creating it... "
		mkdir -p $C_INSTDIR
		echo "done."
	fi
	# add a slash if needed!
	if ! echo $C_INSTDIR | grep /$ > /dev/null; then
		C_INSTDIR=$C_INSTDIR/
		echo "slash added"
	fi
else
	C_INSTDIR=.
fi
# Configuration finished


############################################################################
# Creating database
#
echo ""
echo "#################################################################"
echo "Database:"
echo "If you have already a working database and gave the db name,"
echo "username and password, you *DON'T* want to create the database."
echo "If you do a clean or first install, say yes to these options."
echo ""
echo -n "Shall I setup the database for you (Y/n): "
read Q
if [ "$Q" = "y" ] || [ "$Q" = "Y" ] || [ "$Q" = "" ]; then
	echo "Is the db user \"$C_DBUSER\" allowed to create the"
	echo "database \"$C_DBNAME\"? This will quite certainly"
	echo "not be the case, if you haven't setup this user in"
	echo "mySQL yet."
	echo -n "Can I create and use the database with user \"$C_DBUSER\"? (Y/n)? "
	read Q
	if [ "$Q" = "n" ] || [ "$Q" = "N" ]; then
		echo ""
		echo "Ok, please tell me a user that is allowed to create the database"
		echo -n "and to give the right permissions to user \"$C_DBUSER\" (e.g. root): "
		read C_DBUSER2
		if [ "$C_DBUSER2" = "" ]; then
			echo "ARGH.... you were supposed to give me a name! I will use \"root\""
			echo "for now."
			C_DBUSER2=root
		fi
		echo -n "Password for db user \"$C_DBUSER2\": "
		read C_DBPASS2
		if [ "$C_DBPASS2" = "" ]; then
			echo "Uh-oh, having a powerful user like this and not having a password"
			echo "is *VERY* bad in respect to security. But I will continue..."
			DBOPTIONS="-u$C_DBUSER2"
		else
			DBOPTIONS="-u$C_DBUSER2 -p$C_DBPASS2"
		fi
		
		echo -n "Creating the user \"$C_DBUSER\" to use the database \"$C_DBNAME\"... "
		echo "grant all on ${C_DBNAME}.* to ${C_DBUSER}@localhost identified by \"$C_DBPASS\"" | mysql $DBOPTIONS
		if [ $? = 0 ];then
			echo "done."
		else
			echo "Sorry, but this failed... I have to stop. Please try to find your error (or mine)."
			exit 1
		fi
	else
		if [ "$C_DBPASS" = "" ]; then
			DBOPTIONS="-u$C_DBUSER"
		else
			DBOPTIONS="-u$C_DBUSER -p$C_DBPASS"
		fi
	fi

	# database creation
	echo -n "Creating the database... "
	mysqladmin $DBOPTIONS create $C_DBNAME
	if [ ! $? = 0 ]; then
		echo "Creating the database failed! Abort."
		exit 1
	else
		echo "done."
	fi

	# db structure creation
	echo -n "Creating data structures... "
	mysql $DBOPTIONS $C_DBNAME < sql/publish.sql
	if [ ! $? = 0 ]; then
		echo "Creating the data structures failed! Abort."
		exit 1
	else
		echo "done."
	fi
	
	# example data
	echo -n "Shall I fill the database with an example site (y/N)? "
	read Q
	if [ "$Q" = "y" ] || [ "$Q" = "Y" ]; then
		echo -n "Filling the database... "
		mysql $DBOPTIONS $C_DBNAME < sql/data.sql
		if [ ! $? = 0 ]; then
			echo "Filling the database with example data failed! Aborting."
			exit 1
		else
			echo "done."
		fi
	fi

	# TODO
	mysql $DBOPTIONS $C_DBNAME < upgrade/2_1_to_2_1_1/2_1_to_2_1_1.sql
fi

############################################################################
# Extracting the examples
#
echo -n "Extracting the data for the example site... "
tar xzfp data.tar.gz
if [ ! $? = 0 ]; then
	echo "Failed to extract the example data (data.tar.gz)! Aborting."
	exit 1
else
	echo "done."
fi


############################################################################
# Old install: using modfix_secure
#
if [ "$C_INSTALL" = "old" ]; then
	echo -n "Executing secure_modfix.sh... "
	./secure_modfix.sh $C_OWNER $C_GROUP
	if [ -d ezimagecatalogue/catalogue ]; then
		chown -R $C_OWNER ezimagecatalogue/catalogue
		chgrp -R $C_GROUP ezimagecatalogue/catalogue
	fi
	if [ $? = 0 ]; then
		echo "done."
	else
		echo "FAILED! You might have problems with permissions. Do it yourself."
	fi
else
	echo "Creating the needed cache directories and files... "
	touch error.log
	chmod 660 error.log

	dirs="
	admin/tmp
	ezad/admin/cache
	ezaddress/admin/cache
	ezarticle/admin/cache
	ezarticle/cache
	ezbug/user/cache
	ezbug/admin/cache
	ezcalendar/admin/cache
	ezcalendar/user/cache
	ezcontact/admin/cache
	ezexample/admin/cache
	ezfilemanager/files
	ezforum/admin/cache
	ezforum/cache
	ezimagecatalogue/catalogue
	ezimagecatalogue/catalogue/variations
	ezlink/admin/cache
	ezlink/cache
	eznewsfeed/admin/cache
	eznewsfeed/cache
	ezpoll/admin/cache
	ezpoll/cache
	ezstats/admin/cache
	eztodo/admin/cache
	eztrade/admin/cache
	eztrade/cache
	ezuser/admin/cache
	ezfilemanager/admin/cache
	ezimagecatalogue/admin/cache
	ezbulkmail/admin/cache
	classes/cache
	ezsysinfo/admin/cache
	ezurltranslator/admin/cache"

	for dir in $dirs; do
		mkdir -p $dir
		chmod 770 $dir
		chgrp $C_GROUP $dir
	done
	
fi

############################################################################
# Fix the owners and permissions. We don't have to be too picky with the new install.
#
if [ "$C_INSTALL" = "new" ]; then
	chmod 640 site.ini
fi


############################################################################
# cleaning and securing the cache
#
echo -n "Executing secure_clearcache.sh... "
./secure_clearcache.sh
if [ $? = 0 ]; then
	echo "done."
else
	echo "FAILED! You might have problems with caching. Check it yourself."
fi

############################################################################
# Moving files to wwwdir
#
if [ "$C_INSTALL" = "new" ]; then
	echo ""
	echo "#################################################################"
	echo "Moving the publicly needed files to $C_WWWDIR... "

	echo -n "Moving index*.php to $C_WWWDIR... "
	mv  index*.php $C_WWWDIR
	echo "done."

	echo -n "Moving the ez* image files to $C_WWWDIR... "
	for i in ez*; do
		MODULE=`basename $i`
		for j in admin user; do
			if [ -d "$i/$j/images" ]; then
				if [ ! -d "$C_WWWDIR/$MODULE/$j" ]; then
					mkdir -p "$C_WWWDIR/$MODULE/$j"
				fi
				mv "$i/$j/images" "$C_WWWDIR/$MODULE/$j"
			fi
		done
		if [ -d "$i/images" ]; then
			if [ ! -d "$C_WWWDIR/$MODULE" ]; then
				mkdir "$C_WWWDIR/$MODULE"
			fi
		fi
		if [ -d "$i/images" ]; then
			mv "$i/images" "$C_WWWDIR/$MODULE"
		fi
	done
	echo "done."

	if [ ! -d "$C_WWWDIR/admin" ]; then
		mkdir "$C_WWWDIR/admin"
	fi

	echo -n "Moving admin/images to $C_WWWDIR/admin... "
	mv "admin/images" "$C_WWWDIR/admin"
	echo "done."

	echo -n "Moving images to $C_WWWDIR... "
	mv "images" "$C_WWWDIR"
	echo "done."

	echo -n "Moving admin/templates/*/* to $C_WWWDIR... "
	if [ ! -d "admin/templates" ]; then
		mkdir -p admin/templates
	fi
	for i in admin/templates/*; do
		if [ ! "$(basename $i)" = "CVS" ]; then
			mkdir -p admin/templates/$(basename $i)
			if [ -e $i/*.css ]; then
				mv $i/*.css admin/templates/$(basename $i)
			fi
		fi
	done
	echo "done."

	echo -n "Moving sitedesign/*/*.css to sitedesign/*... "
	for i in sitedesign/*; do
		SDNAME=`basename $i`
		if [ ! "$SDNAME" = "CVS" ]; then
			mkdir -p "$C_WWWDIR/sitedesign/$SDNAME"
			if [ -e $i/images ]; then
				mv "$i/images" "$C_WWWDIR/sitedesign/$SDNAME"
			fi
			if [ -e $i/*.css ]; then
				mv $i/*.css "$C_WWWDIR/sitedesign/$SDNAME"
			fi
		fi
	done
	echo "done."

	echo -n "Moving sitedir.ini to $C_WWWDIR... "
	mv "sitedir.ini" "$C_WWWDIR"
	echo "done."

	#
	# change sitedir.ini
	#
	echo -n "Adjusting $C_WWWDIR/sitedir.ini... "
	sed s:"siteDir = \"\"":"siteDir = \"$C_INSTDIR\"": $C_WWWDIR/sitedir.ini > $C_WWWDIR/sitedir.ini.tmp && mv $C_WWWDIR/sitedir.ini.tmp $C_WWWDIR/sitedir.ini
	echo "done."
fi

############################################################################
# Move the files
#
if [ "$C_INSTALL" = "new" ]; then
	echo ""
	echo "#################################################################"
	echo "Moving the files:"
	echo "Now I will move the files to the installation dir."
	echo -n "Moving files... "
	mv * $C_INSTDIR
	if [ ! $? = 0 ]; then
		echo "Moving of files failed! Aborting."
		exit 1
	else
		echo "done."
	fi
	mv .cvsignore $C_INSTDIR

	# 
	# Links for ezimagecatalogue!
	#
	if [ -d "$C_INSTDIR/ezimagecatalogue/catalogue" ]; then
		echo -n "Linking $C_INSTDIR/ezimagecatalogue/catalogue to $C_WWWDIR/ezimagecatalogue... "
		if [ ! -d "$C_WWWDIR/ezimagecatalogue" ]; then
			mkdir "$C_WWWDIR/ezimagecatalogue"
		fi
		ln -s "$C_INSTDIR/ezimagecatalogue/catalogue" "$C_WWWDIR/ezimagecatalogue"
		echo "done."
	fi

fi

############################################################################
# Now we want to change site.ini
#
echo ""
echo "#################################################################"
echo "Changing site.ini with our values."
echo -n "Adjusting site.ini... "
echo -n "SiteURL"; sed s:SiteURL=ez.no:"SiteURL=$C_HOSTNAME": $C_INSTDIR/site.ini > $C_INSTDIR/site.ini.tmp && mv $C_INSTDIR/site.ini.tmp $C_INSTDIR/site.ini
echo -n " SiteTitle"; sed s:'SiteTitle=eZ Systems':"SiteTitle=$C_TITLE": $C_INSTDIR/site.ini > $C_INSTDIR/site.ini.tmp && mv $C_INSTDIR/site.ini.tmp $C_INSTDIR/site.ini
echo -n " Server"; sed s:Server=localhost:"Server=$C_DBSERVER": $C_INSTDIR/site.ini > $C_INSTDIR/site.ini.tmp && mv $C_INSTDIR/site.ini.tmp $C_INSTDIR/site.ini
echo -n " Database"; sed s:Database=publish:"Database=$C_DBNAME": $C_INSTDIR/site.ini > $C_INSTDIR/site.ini.tmp && mv $C_INSTDIR/site.ini.tmp $C_INSTDIR/site.ini
echo -n " User"; sed s:User=publish:"User=$C_DBUSER": $C_INSTDIR/site.ini > $C_INSTDIR/site.ini.tmp && mv $C_INSTDIR/site.ini.tmp $C_INSTDIR/site.ini
echo -n " Password"; sed s:Password=publish:"Password=$C_DBPASS": $C_INSTDIR/site.ini > $C_INSTDIR/site.ini.tmp && mv $C_INSTDIR/site.ini.tmp $C_INSTDIR/site.ini
echo " ...done."


############################################################################
# Try to do the rest for the old install
#
if [ "$C_INSTALL" = "old" ]; then
	echo "
NameVirtualHost $C_IPADDRESS
<VirtualHost $C_HOSTNAME>
  <Directory $C_WWWDIR>
		Options FollowSymLinks Indexes ExecCGI
		AllowOverride None 
   </Directory>
 
   RewriteEngine On 
   RewriteRule ^/stats/store/(.*).gif\$  $C_WWWDIR/ezstats/user/storestats.php [S=2]
   RewriteRule ^/filemanager/filedownload/([^/]+)/(.*)\$  $C_WWWDIR/ezfilemanager/files/\$1 [T="application/oct-stream",S=1]
   RewriteRule !\.(gif|css|jpg|png)\$ $C_WWWDIR/index.php
 
   ServerAdmin webmaster@$C_HOSTNAME
   DocumentRoot $C_WWWDIR
   ServerName $C_HOSTNAME
</VirtualHost>
 
<VirtualHost admin.$C_HOSTNAME>
  <Directory $C_WWWDIR>
		Options FollowSymLinks Indexes ExecCGI
		AllowOverride None 
		RewriteEngine On
		RewriteRule     !\.(gif|css|jpg) $C_WWWDIR/index_admin.php
  </Directory>
 
  ServerAdmin webmaster@$C_HOSTNAME
  DocumentRoot $C_WWWDIR
  ServerName admin.$C_HOSTNAME
</VirtualHost>
	" > $C_INSTDIR/apache.ini

	echo -n "Moving files to $C_WWWDIR... "
	mv * $C_WWWDIR
	if [ $? = 0 ]; then
		echo "done."
	else
		echo "FAILED!"
	fi

	echo ""
	echo "#################################################################"
	echo "In $C_WWWDIR/apache.ini you find the finished virtualhost setup."
	echo "Append that to your Apache config file and restart apache."
	echo "Then go to http://$C_HOSTNAME and if you're lucky it works. ;-)"
else
	echo ""
	echo "#################################################################"
	echo "Everything should be finished now. Go to http://$C_HOSTNAME$C_URLDIR"
	echo "and see if it works."
fi
	

echo ""
echo ""
echo "Have fun!"
echo ""
echo "Kai Dübbert <kai@duebbert.de> - 25th July 2001"
