<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
// This checks the key sent by the JavaScript against the current bunch of saves
// XML Saves location - assumes it will be saves/
$saves = glob("../saves/*.xml");

$key_requested = $_GET['key'];

$xml_good = "<response><state>OK</state><key>".$key_requested."</key></response>";
$xml_bad = "<response><state>NO</state><key>".$key_requested."</key></response>";
$xml_error = "<response><state>ERROR</state><key>".$key_requested."</key></response>";
if (is_array($saves))
{
    foreach($saves as $filename) {
        $xml_string = file_get_contents($filename, FILE_TEXT);
        $xml_object = simplexml_load_string($xml_string);
        if ($xml_object != false) {
            if (isset($value['key']))
            {
                if ($value['key'] == $key_requested) {
                    echo $xml_bad;
                    return;
                }
            }
        }
    }
    $filename = "../saves/save-".$key_requested.".xml";
    $fileHandle = fopen($filename, 'c');
    if ($fileHandle == FALSE) {
        echo $xml_error;
        return;
    }
    fclose($fileHandle);
    // TODO:
    //  Generate the XML Base file and save it
    $doc_struct = new SimpleXMLElement('<waetresult/>');
    $doc_struct->addAttribute("key",$key_requested);
    //  Add start time
    //  Add IP Address information
    //  Save the file
    $doc_struct->asXML($filename);
    echo $xml_good;
    return;
} else {
    echo $xml_error;
    return;
}
?>
