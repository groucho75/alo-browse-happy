alo-browse-happy
================

This WordPress plugin checks the browser and, if necessary, opens a modal giving a warning that the browser is obsolete and insecure (e.g. old IE).
Inside the modal there is a link to [Browse Happy](http://browsehappy.com/).
The plugin uses a cookie to show the modal once per session.
It uses the WP core functions used for browser check in admin panel. It requires WP 3.2+.

## Installation
1. Create a **alo-browse-happy** folder in **wp-content/plugins/**
2. Put these files there.
3. Activate the plugin as usual.


## Note
* You can select your preferred JqueryUi theme customizing the line: `wp_enqueue_style('jquery-ui-theeme', ... )`
* You can let modal open also when browser has to be updated commenting the line: `if ( $response && empty($response['insecure'] ) ) return;`
