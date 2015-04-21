<?php
/*
This file is part of the preMigration Suite for OPUS4

@author: Andres Quast
@email: quast@hbz-nrw.de

*/

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