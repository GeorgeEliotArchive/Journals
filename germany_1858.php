<!DOCTYPE html>
<html>
  <head>
    <title>Germany 1858</title>
    <link href="https://georgeeliotarchive.org/themes/gearchive-theme/css/style.css?v=3.0.2" media="all" rel="stylesheet" type="text/css">
    <link href="https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" media="all" rel="stylesheet" type="text/css">
    <link href="journal.css" media="all" rel="stylesheet" type="text/css">
    <?php
// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value
// https://stackoverflow.com/a/9802854
function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    // bugfix in comments
    curl_setopt($curl, CURLOPT_HTTPHEADER,
                array('Content-Type: application/json',
                      'Accept: application/json'));

    // https://www.php.net/manual/en/function.curl-exec.php
    curl_setopt($curl, CURLOPT_TIMEOUT, 15); // this takes a LONG time...

    // Optional Authentication:
    // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    // curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    if (!$result) {
        trigger_error($curl_e);
        //print(curl_error($curl));
    }

    curl_close($curl);

    return $result;
}

function makeEntrySummary($dom, $date, $journal, $page) {
    $summary = $dom->createElement('h3');
    $summary->appendChild($dom->createTextNode($date.' -- Journal Entry (Harris & Johnston, '));
    $summary->appendChild($dom->createElement('i', $journal));
    $summary->appendChild($dom->createTextNode(': '.$page.'.)'));

    return $summary;
}

function getJournalEntries($data) {
    $info = array();
	foreach($data as $val){
		$is_germ = false;
		foreach($val['element_texts'] as $v){
			if(strlen($v['text']) < 30 && strtolower($v['text']) == 'germany 1858'){
				$is_germ = true;
				break;
			}
		}
		if($is_germ){
			$info[] = $val;
		}
	}
	// foreach($info as &$val){
	// 	foreach($val['element_texts'] as $k => $v){
	// 		if(strlen($v['text']) > 30 || strtolower($v['text']) != 'germany 1858'){
	// 			unset($val['element_texts'][$k]);
	// 		}
	// 	}
	// }
	$info = json_decode(json_encode($info));

    return $info;
}

$resp = CallAPI("GET", "https://georgeeliotarchive.org/api/items?collection=17");
$data = json_decode($resp, true);
$entries = getJournalEntries($data);

// https://stackoverflow.com/questions/8144061/using-php-to-get-dom-element

?>
</head>
<?php

$resp = CallAPI("GET", "https://georgeeliotarchive.org/api/items/21600");
#$resp = CallAPI("GET", "https://georgeeliotarchive.org/api/items?collection=17");
#print_r(gettype($resp));
if (!$resp)
    print("False");
else {
    #print("\n");
    $data = json_decode($resp, true);
    #print_r($data[0]);

    $data = $data["element_texts"];
    $date = $data[3]["text"];
    $page = $data[6]["text"];
    $journal = $data[4]["text"];

    $dom = new DOMDocument("1.0", "utf-8");
    $dom->appendChild(makeEntrySummary($dom, $date, $journal, $page));

    echo $dom->saveXML();
}
?>
<ul>
    <?php
    for ($i = 0; $i < 10; $i++) {
    ?>
        <li>
            <?php
            echo $i;
            ?>
        </li>
    <?php
    }
    ?>
</ul>
