<?php
/**
* This file is part of the preMigration Suite for OPUS4
*
* @author Andres Quast 
* @email quast@hbz-nrw.de
* @copyright 2015 - 2017 Hochschulbibliothkszentrum des Landes Nordrhein-Westfalen
* @licence  http://www.gnu.org/licenses/gpl.html General Public License
*
* LICENCE
* This is free software; you can redistribute it and/or modify it under the
* terms of the GNU General Public License as published by the Free Software
* Foundation; either version 2 of the Licence, or any later version.
* preMigration Suite is distributed in the hope that it will be useful, but WITHOUT ANY
* WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
* details. You should have received a copy of the GNU General Public License
* along with preMigration Suite; if not, write to the Free Software Foundation, Inc., 51
* Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
**/

// read in Shell Options
$options = getopt("i:e:x:");

if($options['x']=="check"){

  //prepare XSLProcessor
  $xslProc = new XSLTProcessor;
  $dom = new DOMDocument;
  $dom->load($options['e']);
  //$dom->load("xslt/check.xslt");
  $xslProc->registerPHPFunctions();
  $xslProc->importStyleSheet($dom);

  // Read in XML-Dump
  $xml = new DOMDocument;
  $xml->load($options['i']);

  $result = $xslProc->transformToXML($xml);
        if (strlen($result) > 0) {
	    echo "- Found the following lines need to be skipped for consistency:\n".$result;
            //throw new Exception("XML-Dump-File is not consistent.");
        }
  echo "Finished consistency check\n";
  }

if($options['x']=="validate"){
        libxml_clear_errors();
        libxml_use_internal_errors(true);
        $file = file_get_contents($options['i'], true);
        $xml = simplexml_load_string($file);
        $xmlstr = explode("\n", $file);

        $errors = libxml_get_errors();
        if (count($errors) > 0) {
	  echo "- Found validation problems at line(s):\n";
            foreach ($errors as $error) {
                //$this->displayErrorLine($xmlstr[$error->line - 1], $error);
                echo $error->line."\n";
            }
            libxml_clear_errors();
            //throw new Exception("XML-Dump-File is not well-formed.");
        }
    echo "Finished validation\n";
    
    }
  


        
?>