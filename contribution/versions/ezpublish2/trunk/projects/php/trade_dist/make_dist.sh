#!/bin/bash

cd ezcontact
rm -f *.php
rm -rf admin
rm -rf templates
rm -rf sql
rm -rf intl
rm -rf images
rm -rf ez.css
cd ..

file_a=ezaddress.php
file_b=ezcountry.php
file_c=ezaddresstype.php

cd ezcontact
cd classes

for i in *; do
    if [ "$i" != "$file_a" ]; then
	if [ "$i" != "$file_b" ]; then
	    if [ "$i" != "$file_c" ]; then
		echo "deleting $i"
		rm -f "$i";
	    fi
	fi
    fi
done

cd ..
cd ..
pwd
rm make_dist.sh