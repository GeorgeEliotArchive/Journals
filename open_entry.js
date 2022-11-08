// Checks if anchor to entry is in URL
// https://stackoverflow.com/a/10076097
if (window.location.hash) {
    // Gets the anchor from URL
    let hash = window.location.hash.slice(1);
    // https://stackoverflow.com/a/55377750
    // Splits and slices anchor to get month and year detail anchors
    let prefixes = hash.split("-", 3).slice(0, 2);
    // Opens year and month detail
    for(const prefix of prefixes) {
        document.getElementById(prefix).open = true;
    }
    // Opens entry
    let entry = document.getElementById(hash);
    entry.open = true;
}
