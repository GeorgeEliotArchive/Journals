<!DOCTYPE html>
<?php
    $url_params = $_GET ?? array();
?>
<html>
  <head>
    <title>Search Results</title>
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
        trigger_error($curl);
        //print(curl_error($curl));
    }

    curl_close($curl);

    return $result;
}

function getSearchResults($data, $searchOptions) {
    $keyword = $searchOptions["keyword"];
    $results = array();
	foreach($data as $entry){
		foreach($entry['element_texts'] as $entryText){
            if ($entryText["element"]["name"] == "Journal Entry") {
                $entryContent = $entryText['text'];
                if (strpos($entryContent, $keyword) !== false)
                    $results[] = $entry;
                break;
            }
		}
	}

    #print_r($results);
    return $results;
}

// Retrieves a specific item of text from an item in the collection.
function getElement($entry, $elementName) {
    $elements = $entry["element_texts"];

    if ($elementName == "Source") {
        // Page numbers are lumped together with the source.  If we
        // want the journal name we can't use $journal, so instead
        // what we'll do is...idk
        $sources = array();
        foreach ($elements as $element) {
            if ($element["element"]["name"] == $elementName) {
                $sources[] = $element["text"];
            }
        }
        return json_encode($sources);
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

// Retrieves the year from each entry
function getYear($entry) {
    return intval(substr(getElement($entry, "Date"), 0, 4));
}

// Testing setup
// $searchOptions = array(
//     "keyword" => "the"
// );

$resp = CallAPI("GET", "https://georgeeliotarchive.org/api/items?collection=17");
$data = json_decode($resp, true);
$entries = getSearchResults($data, $url_params);

// years array, structure of year => month => entry
$years = array();
foreach ($entries as $entry) {
    $year = getYear($entry);
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
?>
  </head>
  <body>
    <header class="topbar">
    <h1><?php echo json_encode($url_params); ?></h1>
    </header>
    <?php
    // For each year, display the year in detail tag.
    foreach ($years as $year => $months) {
    ?>
    <details>
    <summary><?php echo $year;?></summary>
        <?php
        // For each month, display the month in detail tag.
        foreach (array_keys($months) as $month) {
            $monthEntries = $months[$month];
        ?>
        <details>
        <summary><?php echo $month;?> (<?php echo sizeof($monthEntries);?> Items)</summary>
        <div>
            <?php 
            // For each entry, display the date in detail tag.
            foreach ($monthEntries as $entry) { 
            ?>
            <details>
            <summary>
                <?php echo getElement($entry, "Date"); ?>
                -- Journal Entry (Harris &amp; Johnston, <em>Journals</em>,
                <?php echo getElement($entry, "Source"); ?>: <?php echo getElement($entry, "Source"); ?>.)
            </summary>
            <div><?php echo getElement($entry, "Journal Entry"); ?></div>
            </details>
            <?php } /* end foreach entry */?>
        </div>
        </details>
        <?php } /* end foreach month */?>
    </details>
    <?php } /* end foreach year */?>
  </body>