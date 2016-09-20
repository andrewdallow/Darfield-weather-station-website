<?php
############################################################################
# A Project of TNET Services, Inc.
############################################################################
#
#   Project:    Cumulus Project 
#   Package:    Cumulus Weather Logging
#   Module:     realtimelog.php
#   Version:    2.0 - October 17th, 2008
#   Purpose:    This script should be called from cron or some type of
#               scheduling system once a minute to extract out the
#               contents of the current realtime.txt file and store
#               it in a logfile.
#   Authors:    Kevin W. Reed <programs@tnet.com>
#               TNET Services, Inc.
#   Copyright:  (c) 1992-2008 Copyright TNET Services, Inc.
#
#   License:    
#
#   This program is free software; you can redistribute it and/or
#   modify it under the terms of the GNU General Public License
#   as published by the Free Software Foundation; either version 2
#   of the License, or (at your option) any later version.
#   
#   This program is distributed in the hope that it will be useful,
#   but WITHOUT ANY WARRANTY; without even the implied warranty of
#   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#   
#   You should have received a copy of the GNU General Public License
#   along with this program; if not, write to the Free Software
#   Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, 
#   USA.
#
############################################################################
#   HISTORY
############################################################################
#
#   1.0     Released October 10th, 2008
#           Initial Release
#
#   2.0     Released October 17th, 2008
#           This version works with N lengthed output from the realtime.txt
#           file so that if it is expanded, it simply starts logging the
#           expanded variables.
#
############################################################################
#   This document uses Tab 4 Settings
############################################################################
$DATA   = array();
$SITE   = array();
check_sourceview();
############################################################################
# CONFIGURATION INFORMATION
############################################################################
# NOTES
#
# The home should point to your webservers home directory.
#
# The datafile MUST exist and MUST be chmod 0666 to work as the webserver
# Needs to have permission to write to this file.
#
# The source should point to the realtime.txt file that you are uploading
# to your server.  You should be uploading this at least once a minute
#
# This script should be called via cron or similar scheduler. It must be
# called using PHP CLI... so that it can collect the data properly.
#
# A sample cron entry would look like:
#
# * * * * * php /path_to_your_webroot/realtimelog.php > /dev/null 2>&1
#
# You may need to chmod the realtimelog.php script to 0755.
#
############################################################################
$SITE['version']        = "2.0";
$SITE['home']           = "/var/www/vhosts/darfield-weather.co.nz/httpsdocs/";
$SITE['datafile']       = "realtime.log";
$SITE['source']         = "realtime.txt";
#---------------------------------------------------------------------------
$SITE['debug']          = 1;
############################################################################
check_debug();

// Change directory to home so that we know where we are

if ($SITE['debug']) {
    echo "Current directory is: " . getcwd() . " <br/>\n";
    echo "Changing directory to: " . $SITE['home'] . " <br/>\n";
}

if ($SITE['home'] != "YOU MUST SET THIS") {
    chdir($SITE['home']);
} else {
    echo "You have not set the home directory<br/>";
    exit;
}

if ($SITE['debug']) {
    echo "Current directory is: " . getcwd() . " <br/>\n";
}
// Load the current contents of the source file (normally realtime.txt)

if ($SITE['debug']) {
    echo "Attempting to load: " . $SITE['source'] . " <br/>\n";
}
$rawdata = file($SITE['source']);
if ($SITE['debug']) {
    echo "We loaded: " . count($rawdata) . " - Should be 1<br/>\n";
}

// Extract the data into an array

$DATA = preg_split('/ +/', $rawdata[0]);

if ( $SITE['debug'] ) {
    echo "Found " . count($DATA) . " Fields<br/>\n";
}

// Fix date so that it is in ISO format so that it is sortable
    
$DATA[0] = "20" . substr($DATA[0],6,2) . "-" . substr($DATA[0],3,2) . 
    "-" . substr($DATA[0],0,2);

// Open up the datafile and write out to the end of the file the new
// Information

// Output data with spaces between each field
// It doesn't matter how many fields there are

$fp = fopen( $SITE['datafile'] , 'a' );
if ($fp) {
    
    // Collect all the fields into the variable $info
    $info = "";     # Empty info field
    $first = 1;     # Flag for first variable
    foreach($DATA as $key) {
        // Trim extra spaces off the end (Removes \n's as well)
        $key = trim($key);
        
        // If there is anything left...
        if (strlen($key) > 0 ) {
            
            // Was this the first variable
            if ($first) {
                // yep.. 
                $first=0;
            } else {
                // Nope... add a space
                $info .= " ";
            }
            // Add the field to the end of the info variable
            $info .= $key;
        }
    }
    
    // If debug, display what we ended up with
    
    if ($SITE['debug']) {
        echo "Saving Data as: " . $info . "<br/>\n";
    }    
    
    // Write the data
    
    fwrite($fp, $info . "\n" ) ;
    fclose($fp);
} else {
    
    // Opps... we had an error opening the file
    
    if ($SITE['debug']) {
        echo "UNABLE TO SAVE DATA - CHECK PERMISSIONS <br/>\n";
    }   
    echo "Unable to open file<br/>";
}

if ($SITE['debug']) {
    echo "COMPLETE<br/>\n";
}   
// We are done
exit;

function check_debug () {
    global $SITE;
    
    if ( isset($_GET['debug']) ) {
        $SITE['debug'] = 1;
        echo "DEBUG MODE ON<br/>\n";
    }
}

function check_sourceview () {
    global $SITE;
    
    if ( isset($_GET['view']) && $_GET['view'] == 'sce' ) {
        $filenameReal = __FILE__;
        $download_size = filesize($filenameReal);
        header('Pragma: public');
        header('Cache-Control: private');
        header('Cache-Control: no-cache, must-revalidate');
        header("Content-type: text/plain");
        header("Accept-Ranges: bytes");
        header("Content-Length: $download_size");
        header('Connection: close');
        readfile($filenameReal);
        exit;
    }
}
############################################################################
#   END OF MODULE
############################################################################
