

# Introduction

This system is intended to take the digitized versions of Mary Ann
Evans (*AKA George Eliot*)'s journals and make them available in a beautiful and
accessible website.

The Journals are stored and maintained in an Omeka Classic database,
and retrieved on page-load through Omeka Classic's REST API.

The code is hosted on GitHub at <https://github.com/GeorgeEliotArchive/Journals>


# Architecture Notes


## Key Principles

-   **The underlying data should be maintainable by non-technical curators.**
-   The implementation should be as frugal as possible.
    -   Computationally efficient.
    -   Use free solutions where possible.
-   The implementation should be as simple as possible.
    -   The software will be maintained by students, with high churn.
    -   Ease of onboarding is essential.
        -   Documentation. (Hi! :D)
        -   Simplicity.


## File Structure

There are, broadly speaking, 3 types of file in this project:

1.  Index Files: The landing page of the system describes the journals,
    and provides a starting point from which to navigate to the rest of
    the system. This is mostly contained to `index.html` and its
    associated CSS and JavaScript files.
2.  Journal Files:
    1.  Every journal has its own eponymous PHP file.
    2.  `search.php` renders search results using the same code as a
        multi-year journal.
3.  Component Files: Segments of php common to several journals are
    decomposed into components and saved in their own files, which can
    then be included in the Journal Files during page load using PHP's
    `require`.

The two notable exceptions are `functions.php`, which contains a library
of functions used by the Journal Files, and `search.php`, which is a
self-contained search page for the rest of the site.


## Deployment Environment

The goal is to deploy the code onto `georgeeliotarchive.org` by simply
uploading the files into an appropriate subdirectory on Reclaim. In
this environment it's unclear how dependancies could be installed, so
development is restricted to vanilla PHP 8.


# Known Issues


## Style

There are several remaining pain points in the visual design of the
site. All of these should be resolvable by tweaking the two CSS files
`index.css` and `journal.css`.


## Load Time

Unfortunately, loading a journal page or any search result typically
takes around 20 seconds. The overwhelming majority of this time is
spent waiting on Omeka, so any fixes would have to involve either
caching API requests or somehow integrating more directly with Omeka
Classic's PHP API (if the PHP API is even part of Omeka Classic, which
it may not be).

