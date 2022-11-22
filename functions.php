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
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // this takes a LONG time...

    // Optional Authentication:
    // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    // curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    if (!$result) {
        trigger_error(curl_error($curl));
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

    // Page numbers are lumped together with the source.
    if ($elementName == "Source") {
        foreach ($elements as $element) {
            if ($element["element"]["name"] == "Source"
                && !is_numeric($element["text"][0])) {
                return $element["text"];
            }
        }
    } else if ($elementName == "Page") {
        foreach ($elements as $element) {
            if ($element["element"]["name"] == "Source"
                && is_numeric($element["text"][0])) {
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

// Returns day from entry Date (everything after the second dash)
// Used for creating the entry anchors
function getDay($entry) {
    $count = 0;
    $day = '';
    $date = getElement($entry, "Date");
    $chars = str_split($date);
    $i = 0;
    foreach($chars as $char) {
        if ($char == '-' && $count == 1) {
            $day = substr($date, $i + 1);
            break;
        } elseif ($char == '-') {
            $count += 1;
        }
        $i += 1;
    }
    return $day;
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

// Retrieves the year from each entry
function getYear($entry) {
    $dateStr = getElement($entry, "Date");
    if ($dateStr[0] == '[')
        return intval(substr($dateStr, 1, 5));
    else
        return intval(substr($dateStr, 0, 4));
}

function arrangeEntriesByYear($entries) {
    // years array, structure of year => month => entry
    $years = array();
    foreach ($entries as $entry) {
        $year = getYear($entry);
        if ($year < 1000)
            print_r('Warning: Year extraction failed on date string "'.getElement($entry, 'Date').'"!');

        // Checks if year is in years array; if not, year is created.
        if (!array_key_exists($year, $years))
            $years[$year] = array();

        $month = getMonthString($entry);
        // Guarantee the month is in the months array.
        if (!array_key_exists($month, $years[$year]))
            $years[$year][$month] = array();

        // Append this entry to the appropriate month's array.
        $years[$year][$month][] = $entry;
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

    foreach ($years as $year => $months) {
        $newMonths = array();
        foreach ($monthNames as $monthName) {
            if (array_key_exists($monthName, $months)) {
                $newMonths[$monthName] = $months[$monthName];

                // Sort entries within the month
                // https://stackoverflow.com/a/31298778
                usort($newMonths[$monthName], function ($a, $b) {
                    return getElement($a, "Date") <=> getElement($b, "Date");
                });
            }
        }
        $years[$year] = $newMonths;
    }
    // Sort the year
    ksort($years);

    return $years;
}

// Example URL Parameters:
// ?keywords[1]=this&keywords[2]=that&strict_caps
// ?keywords[1]=Pug&keywords[2]=Bank&start_year=1860&end_year=1865
function getSearchResults($data, $searchOptions) {
    if (array_key_exists('keywords', $searchOptions))
        $keywords = preg_split("/[\s]/", $searchOptions["keywords"]);
    else
        $keywords = array('');
    $results = array();

    // Loop through the journal entries, checking conditions on each.
    foreach($data as $entry) {
        if (array_key_exists('journal', $searchOptions)
            && $searchOptions['journal'] != 'JournalName'
            && $searchOptions['journal'] != getElement($entry, "Source"))
            continue;

        if (array_key_exists('start_year', $searchOptions)
            && $searchOptions['start_year'] != "StartingYear"
            && $searchOptions['start_year'] > getYear($entry))
            continue;

        if (array_key_exists('end_year', $searchOptions)
            && $searchOptions['end_year'] != "EndingYear"
            && $searchOptions['end_year'] < getYear($entry))
            continue;

        foreach($entry['element_texts'] as $entryText) {
            // We only check the body of the journal entry.
            if ($entryText["element"]["name"] == "Journal Entry") {
                $entryContent = $entryText['text'];
                foreach($keywords as $keyword) {
                    if (stripos($entryContent, $keyword) !== false) {
                        $results[] = $entry;
                        break 2; // Skip to the next entry
                    }
                }
            }
        }
    }

    return $results;
}
?>
