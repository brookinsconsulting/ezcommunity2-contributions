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

cd ezcontact
cd classes

for i in *; do
    if [ "$i" != "$file_a" ]; then
	if [ "$i" != "$file_b" ]; then
	    echo "deleting $i"
	    rm -f "$i";
	fi
    fi
done

cd ..
cd ..
rm -rf eztrade/
pwd
rm make_dist.sh
