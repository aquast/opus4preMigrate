#!/bin/bash

##
# This file is part of the preMigration Suite for OPUS4
#
# @author Andres Quast 
# @email quast@hbz-nrw.de
# @copyright 2015 - 2017 Hochschulbibliothkszentrum des Landes Nordrhein-Westfalen
# @licence  http://www.gnu.org/licenses/gpl.html General Public License
#
# LICENCE
# This is free software; you can redistribute it and/or modify it under the
# terms of the GNU General Public License as published by the Free Software
# Foundation; either version 2 of the Licence, or any later version.
# preMigration Suite is distributed in the hope that it will be useful, but WITHOUT ANY
# WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
# FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
# details. You should have received a copy of the GNU General Public License
# along with preMigration Suite; if not, write to the Free Software Foundation, Inc., 51
# Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
##


path=`pwd`
#echo $path
migToolPath=`echo $0 | sed -e 's/bin\/preparemigration.sh//g'`
#echo "migPath: $migToolPath"
stylesheetDir="$path/$migToolPath/xslt"
regexDir="$path/$migToolPath/replacements"

echo
echo "STEP 1: Processing replacements to create valide xml"
infile=$1
i=0
workfile="$infile.$i"
#echo "Workfile: $workfile"
cp $path/$infile $path/$workfile 
## Try to regex almost all utf-8 clatch
find $regexDir -name '*.txt'  -printf "%f\n" | { while read -r regexFile
do
  let i=$i+1
  echo "- Process replacement file: $regexFile"
  sed -f $regexDir/$regexFile $workfile > $infile.$i
  workfile=$infile.$i
  #echo "created new workfile $workfile"
  let j=$i-1
  rm $path/$infile.$j
done

cp $path/$workfile $path/"$infile.sed.xml"
rm $path/$workfile
}

echo "Finished replacements for valide xml"
echo
echo "STEP 2: Validate xml file"
php $path/$migToolPath/bin/preValidator.php -i $path/$infile.sed.xml -x validate

echo "STEP 3: Check consistency of xml file"
php $path/$migToolPath/bin/preValidator.php -e $stylesheetDir/language.xslt -i $path/$infile.sed.xml -x check


#echo "result institutions"
#php $path/bin/preValidator.php -e $stylesheetDir/institute_structure.xslt -i $path/$infile
#echo

#find $stylesheetDir -name *.xslt -printf "%f\n" | while read -r xsltFile;
#do
#php $path/bin/preValidator.php -e $stylesheetDir/$xsltFile -i $path/$infile
#done