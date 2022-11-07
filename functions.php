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
        trigger_error($curl);
        //print(curl_error($curl));
    }

    curl_close($curl);

    return $result;
}

function getJournalEntries($data, $journal) {
    $info = array();
	foreach($data as $val){
		$is_germ = false;
		foreach($val['element_texts'] as $v){
			if(strlen($v['text']) == strlen($journal) && strtolower($v['text']) == strtolower($journal)){
				$is_germ = true;
				break;
			}
		}
		if($is_germ){
			$info[] = $val;
		}
	}

    return $info;
}

// Retrieves a specific item of text from an item in the collection.
function getElement($entry, $elementName) {
    global $journal;
    $elements = $entry["element_texts"];

    if ($elementName == "Source") {
        // Page numbers are lumped together with the source
        // If we want the journal name we can use $journal
        foreach ($elements as $element) {
            if ($element["element"]["name"] == $elementName
                && $element["text"] != $journal) {
                return $element["text"];
            }
        }
    } else {
        foreach ($elements as $element) {
            if ($element["element"]["name"] == $elementName) {
                return $element["text"];
            }
        }
    }

    // If we've gotten here, the element does not exist.
    return "Element missing: ".$elementName."!";
}

function getMonthString($entry) {
    $count = 0;
    $monthNum = '';
    $chars = str_split(getElement($entry, "Date"));
    foreach($chars as $char) {
        if (is_numeric($char) && $count >= 4 && $count < 6) {
            $monthNum .= $char;
            $count += 1;
        } elseif (is_numeric($char)) {
            $count += 1;
        }
    }
    return date("F", mktime(0, 0, 0, intval($monthNum), 10));
}

function arrangeEntriesByMonth($entries) {
    $months = array();
    foreach ($entries as $entry) {
        $month = getMonthString($entry);
        // Guarantee the month is in the months array.
        if (!array_key_exists($month, $months))
            $months[$month] = array();

        // Append this entry to the appropriate month's array.
        $months[$month][] = $entry;
    }

    // Sort the months
    $monthNames = array(
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December"
    );
    $sortedMonths = array();
    foreach ($monthNames as $monthName) {
        if (array_key_exists($monthName, $months)) {
            $sortedMonths[$monthName] = $months[$monthName];

            // Sort entries within the month
            // https://stackoverflow.com/a/31298778
            usort($sortedMonths[$monthName], function ($a, $b) {
                return getElement($a, "Date") <=> getElement($b, "Date");
            });
        }
    }

    return $sortedMonths;
}
?>
