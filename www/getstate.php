<?php
// Author: Emeka Okoye
// Website: http://linkedopendatang.blogspot.com
// Demo: http://linkedopendatang.com/entity/state/getstate.php?f=rdf


$get_var = empty($_GET['f']) ? 'json' : $_GET['f'];
$return_type = 'json';
$content_header = "application/json";

if ( $get_var === 'json' ) $return_type = 'json';
else if ( $get_var === 'csv' ) $return_type = 'csv';
else if ( $get_var === 'xml' ) $return_type = 'xml';
else if ( $get_var === 'tsv' ) $return_type = 'tsv';
else if ( $get_var === 'ttl' ) $return_type = 'ttl';
else if ( $get_var === 'rdf' ) $return_type = 'rdf';
else $return_type = 'json';

if ( $get_var === 'json' ) $content_header = "Content-Type: application/json";
else if ( $get_var === 'csv' ) $content_header = "Content-Type: text/csv";
else if ( $get_var === 'xml' ) $content_header = "Content-Type: text/xml";
else if ( $get_var === 'tsv' ) $content_header = "Content-Type: text/tab-separated-values";
else if ( $get_var === 'ttl' ) $content_header = "Content-Type: text/turtle";
else if ( $get_var === 'rdf' ) $content_header = "Content-Type: application/rdf+xml";
else $content_header = "Content-Type: application/json";




function crequest($url){
// is curl installed?
if (!function_exists('curl_init')){
    die('CURL is not installed!');
}
// get curl handle
$ch= curl_init();
// set request url
curl_setopt($ch,CURLOPT_URL,$url);
// return response, don't print/echo
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
/*
Here you find more options for curl:
http://www.php.net/curl_setopt
*/	
$response = curl_exec($ch);
curl_close($ch);
return $response;
}


$sparql_endpoint = "http://dbpedia.org/sparql" ;

// SPARQL query (SELECT * from feed ... ) // Split for readability




$path = "select ?s1 as ?stateURI str(?s4) as ?stateName str(?s5) as ?stateDesc " ;
$path .= "where  { " ;
$path .= "?s1 a <http://dbpedia.org/ontology/Place> . " ;
$path .= "?s1 <http://dbpedia.org/property/type> ?s3 . " ;
$path .= "?s1 rdfs:label ?s4 .  " ;
$path .= "?s1 rdfs:comment ?s5 . " ;
$path .= "filter ( ?s3 in ( <http://dbpedia.org/resource/States_of_Nigeria> ) )  " ;
$path .= "filter langMatches( lang(?s4), \"en\" )  " ;
$path .= "filter langMatches( lang(?s5), \"en\" )  " ;
$path .= "} " ;

// update the parameters

$uri = $sparql_endpoint . "?query=" . urlencode($path). "&default-graph-uri=&output=" . $return_type ;

// Call SPARQL Endpoint, and if the query didn't fail, cache the returned data
$feed = crequest($sparql_endpoint . "?query=" . urlencode($path). "&default-graph-uri=&output=" . $return_type);

// header('Content-Type: text/turtle');

header($content_header);
echo $feed ;
	
?>
