// returns prefixes; anchor(s) that nest another
// (e.g. 1862-01-09), '1862' and '01' are prefixes to 09 entry.
function getPrefixes(anchor) {
    let prefixes = [];
    // if anchor has dashes, split and store the first two prefixes
    // (e.g.) '1862-01-09' -> ['1862', '01']
    if (anchor.includes('-')) {
        prefixes = anchor.split('-').slice(0, 2);
    } else {
        prefix.push(anchor);
    }
    return prefixes;
}

// wait for anchors to generate
window.onload = function() {
    // if anchor exists in url
    if (window.location.hash) {
        // save anchor from url
        let anchor = window.location.hash.substring(1);
        let prefixes = getPrefixes(anchor);
        // if year or month in prefix, open them
        for (const prefix of prefixes) {
            if (document.getElementById(prefix)) {
                document.getElementById(prefix).open = true;
            }
        }
        // If entry date in anchor, open it
        if (document.getElementById(anchor)) {
            document.getElementById(anchor).open = true;
        } else {
            // otherwise, reassign hash in url to prefixes to represent a valid anchor
            // e.g. hash in url '#1862-01-203209320943----232-' -> '#1862-01'
            window.location.hash = prefixes.join("-");
        }
    }
}