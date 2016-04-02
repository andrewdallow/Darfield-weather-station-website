<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
// ajax-dashboard.php  - Ken True - webmaster@saratoga-weather.org
// ------------------------------------------------------------------------------------------
//  Burnsville Weather LIVE Modifications to Ken True's original ajax-dashboard.php
//          Special Thanks to Kevin W. Reed <kreed@tnet.com> TNET Services, Inc. 
//          and Ken True <saratoga-weather.org> for the great Ajax Scripts and
//          Tom Chapman <www.carterlake.org> for all his work on Scripts and Brian
//          from Weather Display <www.weather-display.com> for loads of help and tags!
//          Without their great programs, code and scripts, none of this would be possible!
//          Thanks to all of them for allowing me to modify their great code and put it out!
//          www.BurnsvilleWeatherLIVE.com - scott@burnsvilleweatherlive.com
// ------------------------------------------------------------------------------------------
//  Version 6.00 - 10-Nov-2010   Totally New Layout, Many new options and setups.  New config, additional Testtags and modified AjaxWDwx.js
//                               file required now labeled version 3.00 per request of authors of that script.
//  Version 6.01 - 14-Nov-2010   Fixed issues with Snow.  Dropped snow season config options in favor of showing snow when there is some!
//  Version 6.02 - 03-Dec-2010   Fixed issue with double Unit of Measure in one area of the script.
//  Version 6.03 - 09-Jan-2011   Fix for Moon Phase sticking at 1%.  Must also replace ajaxWDwx.js to complete the fix!
//  Version 6.04 - 09-Jan-2011   Fix for some users with bad formatted date in Trends High/Low readings. fixed dup UofM on low dewpoint and
//                               added option for no snow or lightning (blank section) if both are set to false.
//  Version 6.05 - 15-Jan-2011   Yet again another fix for the moon issue.  Be sure to update ajaxWDwx.js to version 3.02 as well.
//  Version 6.06 - 16-Jan-2011   Made a change to ajaxWDwx.js to match up with Rainer's lates update to 9.13.  New version is 3.03 to work
//                               with version 6.xx.  Will not work with version 9.xx due to mods I have made.
//  Version 6.07 - 22-Jan-2011   Minor update to RSS AQI for no update found image.  Fixed madding intermittant bug that would sometimes cause
//                               the rain icon to display when below freezing or the Record High/low to display when no record was warrented!
//                               Also minor change to re-fix the colored UV word in ajaxWDwx.js.  Upgrading from 6.05 or later just needs
//                               ajax-dashboard.php and ajacWDwx.js replaced.
//  Version 6.08 - 18-Feb-2011   Updated all split() functions to explode to fit with new rules in PHP 5.3.x.  Changed snow/rain
//                               Icon to follow wetbulb temperature instead of config setting for $Freezing.  Fixed Date formats for several places
//                               where we have to build the date from individual parts (Record High/low, Windchill, Heatindex). Fix for wider Sun
//                               column not displaying right. Server other minor changes.
//  Version 6.09 - 26-Feb-2011   Fixed bug that caused a preg_match function error.wet
//
//  Version 6.20 - 27-Jun-2011   Updated to conform to Ken's new version 3.xx script for Weather-Display.  Use version 6.1x if still using Version 2.xx
//                               of Ken's Script. Added additional code for language awareness on the windrose graphic. Change for UV forecast in evening
//                               being off a day, thanks to Jerry (gwwilk)!
//  Version 6.21 - 26-Jul-2011   Added Record High Dew Point.  Updated Solar Function.  Fixed UV over 11 image problems
//  Version 6.50 - 13-Nov-2011   Added Leaf moisture and Soil moisture/temperature options.  Also updated ajaxWDwx.js to enable updates to Leaf Moisture.
//                               Added support for alternative animated icon set from http://www.meteotreviglio.com.  Added new tags for 1st snow date and
//                               average 1st snow of the season.
//  Version 6.51 - 15-Nov-2011   Fix for rain/snow image not changing correctly for all users. 
//  Version 6.52 - 01-Dec-2011   More changes to update to Ken's latest code changes
//  Version 6.53 - 07-Jan-2012   Condensed average snow/rain/temps, thanks Matthew Romer. Fix for average snow reporting too low.  Thanks Tim Bubla!
//  Version 6.54 - 24-Jan-2012   Fix for type in Solar display, thanks Travis Walter!
//  Version 6.60 - 17-Mar-2012   Added Tide Display as an option in place of snow. Also added Ability for Lightning to over-ride tide or snow if you have
//                               Lightning set TRUE as well as either ANOW or TIDE.  See changes in Config File.
//  Version 6.70 - 17-May-2012   Another fix for lightning display. Fix for dew point change always showing raising (Thanks Jerry). Added new option for Solor
//                               adding two different readings plus a config change from Jerry Wilkins.  Now supports both station record hi/lo as well as Weather
//                               Underground Hi/Lo Records and separate optional Record HIGH/LOW icons for Station and Official (Weather Underground).
//  Version 6.71 - 12-Sep-2012   Fix for format issue in Tide. Changes to Soil for 200cb.  Can now use Heat Index and Wind Chill instead of Feels Like under current Temp.
//  Version 6.72 - 25-Oct-2012	 Fixes to allow Unit of Measure switching for most values between Metric and Imperial; Eliminated double degree in Station All Time Records section; Improved Style-Switching compliance; Changed some font sizes to relative % rather than fixed px.
//                               Just use settings in config. Added ability to block soil moisture if you only have temp probes. (New config included only for those
//                               updating, you do not need to update that if using version 6.6 or later or using new features for soil moisture/temp)
//  Version 6.80 - 17-Feb-2013   Modified first pannel to include Storm Prediction(US) animated image under forecast and moved Fire Danger under Rain/Snow.  Several
//                               other minor changes.  Config File Change required.  New Version of ajaxWDwx.js also required.  Included many of the suggested changes
//                               from Gerald Wilkins from www.gwwilkins.org, thanks for all your hard work Gerald!  Added year to date Max Gust.  Added Snow Melt to 
//                               Top section next to Snowflake if usesnow is false, thanks Jerry! Changed name of script to Burnsville Weather Live.  Fixed issue
//                               with Tide centerization.  Added back Yesterday Hi/lo section.  Many minor fixes and enhancements.
//  Vesrion 6.81 - 20-Mar-2013   Fixes from Jerry for Solar and Conversions in Java Script.  Other minor changes and fixes. Added in Yesterday High/Low, Fix for CBI/FWI
//                               that got messed up in 6.80.  Now works fully with Metric/Imperial translations.
//                               NOTE:  MUST be used with ajaxWDwx3.js version 3.01 (formally 6.xx but renamed back to the 3.xx series I was supposed to be using)                                                                                                            
//  Version 6.82 - 07-Sep-2013   Moved Storm Prediction Image to config to make setting the image easier for other countries and locals.  Minor CSI and other fixes.  Changed
//                               FWI section to take 6 images and allow for a scale of 0-232 as shown in Weather Display.  NOTE YOU WILL NEED TO FIX IMAGE NAMES (In main script
//                               and Ajax) OR RE-NAME IMAGES FOR FWI                              
//  Version 6.90 - 30-Oct-2013   Relayout and additional items in Dew Point, Humidity.  Added opional Rain Chart (new config file) and made a lot of code clean-ups
//                               Added date translation to function "function mon_no($Tmonth)" below for multiple languages.  Search for it to add more languages.
//                               Added ability to see what version AjaxWDwx3.js version is being run in the station status section.
//  Version 6.91 - 10-Nov-2013   Added changes to ajaxWDwx3.js and Lightning Code only.  Not needed if not using Lightning detection.  Several minor changes.
//  Version 6.92 - 09-Feb-2014   Added Show Mobile Version Button option, will display mobile version if enabled.  Updates to Lightning and Snow in CM
//                               Fix for max gust showing year man instead of all time gust.
//  -----------------------------------------------------------------------------------------
//  NOTE:  Please do not remove the version link at the bottom of the script unless you have
//  donated something to me or your local food-shelf.  That's all I ask in return for using
//  this script.  I'll leave it up to you which you do.  You don't need to send me anything
//  if you choose to donate to your local food shelf, your word is good for me!  THANKS!
//  (Of course if you want to donate AND leave the link up, thats great too! :)  )  Scott
//  -----------------------------------------------------------------------------------------
//
//error_reporting(E_ALL);
// --- settings for standalone use --------------------------
$tagsInterval = 1; // testtags.php upload interval in minutes

$ajaxvers = "| Ajax 3.xx";  // Required ajaxWDwx3.js version for this script.  Will display actual version (3.06) in station status section.
$Lang = 'en';
$uomTemp = '&deg;C';
$uomBaro = ' hPa';
$uomWind = ' kph';
$uomRain = ' mm';
$uomSoil = ' mm';
$uomPerHour = '/hr';
$uomHeight = ' m';
$uomDistance = ' km';
$uomSnow = ' cm';
$imagesDir = 'ajax-images/';  // directory for ajax-images with trailing slash
$condIconDir = './ajax-images/'; // directory for condition icons
$condIconType = '.png'; // default type='.jpg' -- use '.gif' for animated icons from http://www.meteotreviglio.com/
//  $timeFormat = 'D, d-M-Y g:ia T';  // Fri, 31-Mar-2006 6:35pm TZone
$timeFormat = 'd-M-Y g:ia';  // Fri, 31-Mar-2006 6:35pm TZone
$timeOnlyFormat = 'g:ia';    // h:mm[am|pm];
//$timeOnlyFormat = 'H:i';     // hh:mm
$dateOnlyFormat = 'd-M-Y';   // d-Mon-YYYY
$WDdateMDY = true;     // true=dates by WD are 'month/day/year'
//                     // false=dates by WD are 'day/month/year'
$ourTZ = "America/Chicago";  //NOTE: this *MUST* be set correctly to
// translate UTC times to your LOCAL time for the displays.
//
include_once("common.php");          // for language translation
include_once("Settings.php");
if (file_exists('raintodate.php')) include_once('raintodate.php');		// for ytd/mtd rain calculations
include_once("AltAjaxDashboardConfig6.php"); // for alt Dashboard configuration items
include_once('get-aqi-rss.php');   // get AQI index value for dashboard display
// --- end of settings for standalone use
//
// overrides from Settings.php if available
echo '<!-- ...................... ' . $timeOnlyFormat . " 1-->\n";
global $SITE, $forwardTrans, $reverseTrans;
$commaDecimal = false;
if (isset($SITE['lang']))           { $Lang = $SITE['lang']; }
if (isset($SITE['uomTemp']))        { $uomTemp = $SITE['uomTemp']; }
if (isset($SITE['uomBaro']))        { $uomBaro = $SITE['uomBaro']; }
if (isset($SITE['uomWind']))        { $uomWind = $SITE['uomWind']; }
if (isset($SITE['uomRain']))        { $uomRain = $SITE['uomRain']; }
if (isset($SITE['uomSnow']))        { $uomSnow = $SITE['uomSnow']; }
if (isset($SITE['uomPerHour']))     { $uomPerHour = $SITE['uomPerHour']; }
if (isset($SITE['imagesDir']))      { $imagesDir = $SITE['imagesDir']; }
if (isset($SITE['timeFormat']))     { $timeFormat = $SITE['timeFormat']; }
if (isset($SITE['timeOnlyFormat'])) { $timeOnlyFormat = $SITE['timeOnlyFormat']; }
if (isset($SITE['dateOnlyFormat'])) { $dateOnlyFormat = $SITE['dateOnlyFormat']; }
if (isset($SITE['WDdateMDY']))      { $WDdateMDY = $SITE['WDdateMDY']; }
if (isset($SITE['tz']))             { $ourTZ = $SITE['tz']; }
if (isset($SITE['UV']))             { $haveUV = $SITE['UV']; }
if (isset($SITE['SOLAR']))          { $haveSolar = $SITE['SOLAR']; }
if (isset($SITE['WXtags']))         { $WXtags = $SITE['WXtags']; }
if (isset($SITE['fcstorg']))        { $fcstorg = $SITE['fcstorg']; }
if (isset($SITE['fcstscript']))     { $fcstscript = $SITE['fcstscript']; }
if (isset($SITE['UVscript']))       { $UVscript = $SITE['UVscript']; }
if (isset($SITE['DavisVP']))        { $DavisVP = $SITE['DavisVP']; }
if (isset($SITE['showSnow']))       { $showSnow = $SITE['showSnow']; }
if (isset($SITE['commaDecimal']))   { $commaDecimal = $SITE['commaDecimal']; }
if (isset($SITE['fcsticonsdir']))   { $fcstIconDir = $SITE['fcsticonsdir']; }
if (isset($SITE['fcsticonstype']))  { $condIconType = $SITE['fcsticonstype']; }
if (isset($SITE['moonIconsSet']))     $moonIconsSet = $SITE['moonIconsSet']; 

$moonHemisphere = 'NH';
if ($SITE['latitude'] < 0) $moonHemisphere = 'SH';

if (isset($_REQUEST['sce']) && ( strtolower($_REQUEST['sce']) == 'view' or
        strtolower($_REQUEST['sce']) == 'show')) {
    //--self downloader --
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
// testing parameters// testing parameters
print "<!--  $ADBversion -->\n";

$DebugMode = false;
if (isset($_REQUEST['debug']))   { $DebugMode = strtolower($_REQUEST['debug']) == 'y'; }
if (isset($_REQUEST['UV']))      { $haveUV = $_REQUEST['UV'] <> '0'; }
if (isset($_REQUEST['solar']))   { $haveSolar = $_REQUEST['solar'] <> '0'; }
if (isset($_REQUEST['snow']))    { $displaySnow = $_REQUEST['snow'] <> '0'; }

$fcstby = isset($_REQUEST['fcstby']) ? strtoupper($_REQUEST['fcstby']) : '';
if ($fcstby == 'NWS') {
    $SITE['fcstscript'] = 'advforecast2.php';  // USA-only NWS Forecast script
    $SITE['fcstorg'] = 'NWS';    // set to 'NWS' for NOAA NWS
    $fcstorg = $fcstby;
    $fcstscript = $SITE['fcstscript'];
}

if ($fcstby == 'EC') {

    $SITE['fcstscript'] = 'ec-forecast.php';    // Canada forecasts from Environment Canada
    $SITE['fcstorg'] = 'EC';    // set to 'EC' for Environment Canada
    $fcstorg = $fcstby;
    $fcstscript = $SITE['fcstscript'];
}
if ($fcstby == 'WU') {

    $SITE['fcstscript'] = 'WU-forecast.php';    // Non-USA, Non-Canada Wunderground Forecast Script
    $SITE['fcstorg'] = 'WU';    // set to 'WU' for WeatherUnderground
    $fcstorg = $fcstby;
    $fcstscript = $SITE['fcstscript'];
}
if ($fcstby == 'WXSIM') {

    $SITE['fcstscript'] = 'plaintext-parser.php';    // WXSIM forecast
    $SITE['fcstorg'] = 'WXSIM';    // set to 'WXSIM' for WXSIM forecast
    $fcstorg = $fcstby;
    $fcstscript = $SITE['fcstscript'];
}
// end of special testing parms
print "<!-- fcstby='$fcstby' fcstscript='" . $SITE['fcstscript'] . "' fcstorg='" . $SITE['fcstorg'] . "' -->\n";
// end of overrides from Settings.php

include_once($WXtags);   // for the bulk of our data
$doPrintNWS = false; // suppress printing of forecast by advforecast2.php
$doPrint = false; // suppress printing of ec-forecast.php
$doPrintWU = false; // suppress printing of WU-forecast.php
include_once($fcstscript); // for the forecast icon stuff
// copy forecast script variables to carterlake-style names if script used is not advforecast2.php
if ($fcstorg == 'WU') {
    $forecasticons = $WUforecasticons;
    $forecasttemp = $WUforecasttemp;
    $forecasttitles = $WUforecasttitles;
    $forecasttext = $WUforecasttext;
} else if ($fcstorg == 'EC') {
    foreach ($forecasticon as $i => $temp) {
        $forecasticons[$i] = $forecasttitles[$i] . "<br />\n" .
                $forecasticon[$i] . "\n" .
                $forecasttext[$i] . "\n";
    }
    $forecasttext = $forecastdetail;
    foreach ($forecastdays as $i => $temp) {
        $t = explode('<br />', $forecastdays[$i]);
        $forecasttitles[$i] = $t[0];
    }
} else if ($fcstorg == 'WXSIM') {

    $forecasticons = $WXSIMicons;
    $forecasttemp = $WXSIMtemp;
    $forecasttitles = $WXSIMday;
    $forecasttext = $WXSIMtext;
}
if (isset($SITE['WXSIM']) and $SITE['WXSIM'] == true and $fcstorg <> 'WXSIM') {
    $doPrint = false;
    include_once($SITE['WXSIMscript']);
}

// Fix Lightning testtag to split it to separate date and time tags for ajax update ability - New Version 6.91
//$lighteningcountlasttime = '19:07:43 2013/24/11';
$llst = explode(' ',$lighteningcountlasttime);
$lastlightningstriketime = $llst[0];
$llsd = explode('/',trim($llst[1]));
$lastlightningstrikedate = rdate($RecDateF,$llsd[2],$llsd[1],$llsd[0]);

$freezing = false;  // assume normal rain display
//if ((isset($snowseasoncm) and isset($snowseasonin)) and 
//        ($snowseasoncm > 0.0 or $snowseasonin > 0.0)) {
//         $displaySnow = true;
//}
$nowTemp = strip_units($temperature);
$showChill = true;  // Do not change this value manually, it is changed by the temperature
if (strip_units($nowTemp) >= $coolVal) {
    $showChill = false;
}
$showHeat = false;  // Do not change this value manually, it is changed by the temperature
if (strip_units($nowTemp) >= $coolVal) {
    $showHeat = true;
}
print "<!-- Lang='$Lang' -->\n";
if ($Lang <> 'en') { // try changing windrose graphics test for the Calm graphic
    $tfile = preg_replace('|^' . $wrName . '|', $wrName . $Lang . '-', $wrCalm);
    print "<!-- checking for '" . $imagesDir . $tfile . "' -->\n";
    if (file_exists($imagesDir . $tfile)) {
        print "<!-- alternate windrose for '" . $Lang . "' loaded -->\n";
        print '<script type="text/javascript">';
        print "\n var wrCalm = \"$tfile\";\n";
        print " var wrName = \"$wrName$Lang-\";\n";
        if (!isset($SITE['langTransloaded'])) {
            if (count($forwardTrans) > 0) {
                print "// Language translation for conditions by ajaxWDwx3.js\n";
            }
            foreach ($forwardTrans as $key => $val) {
                print "  langTransLookup['$key'] = '$val';\n";
            }
            $SITE['langTransloaded'] = true;
        }
        print "</script>\n";
        $wrCalm = $tfile;  // change the PHP dashboard settings too
        $wrName = $wrName . $Lang . '-';
        print "<!-- new wrName='$wrName', wrCalm='$wrCalm' -->\n";
    }
}
// --- end of settings -------------------
if (stristr($_SERVER["HTTP_HOST"], 'localhost') !== false)
    $ajaxDebug = true; // ajax debug messages
else if (isset($_GET["progress"]))
    $ajaxDebug = true;
else
    $ajaxDebug = false;

$rest = substr($firstsnowseason, 0, 3);
if ($rest == '0/0')
    $firstsnowseason = 'None Yet!'; // set first snow of season to 'Not Yet' rather then 0/0/20xx

    
// Sample from WD: $moonage = "Moon age: 10 days,10 hours,41 minutes,80%";	// current age of the moon (days since new moon)
// Sample from WD: $moonphase = "80%";	// Moon phase %
$moonagedays = preg_replace('|^Moon age:\s+(\d+)\s.*$|is', "\$1", $moonage);
if ($moonphase == '') { // MAC version of WD is missing this value
    preg_match_all('|(\d+)|is', $moonage, $matches);
    $moonphase = $matches[1][3];
    if (isset($matches[1][4])) {
        $moonphase .= '.' . $matches[1][4]; // pick up decimal;
        $moonphase = round($moonphase, 0);
    }
    $moonphase .= '%';
}
# Set timezone in PHP5/PHP4 manner
if (!function_exists('date_default_timezone_set')) {
    putenv("TZ=" . $ourTZ);
#	$Status .= "<!-- using putenv(\"TZ=$ourTZ\") -->\n";
} else {
    date_default_timezone_set("$ourTZ");
#	$Status .= "<!-- using date_default_timezone_set(\"$ourTZ\") -->\n";
}
$UpdateDate = date($timeFormat, strtotime("$date_year-$date_month-$date_day  $time_hour:$time_minute:00"));
//  $UpdateDate = "$date_year-$date_month-$date_day  $time_hour:$time_minute:00";
$monthname = langtransstr($monthname); // translate WD month name to selected language
$dayname = langtransstr(substr($dayname, 0, 3));
//
// Snow setup
if ($snowtoday == '---' or $snowtoday == '') {
    $snowtoday = '0';
}
if ($burntime == '---' or $burntime == '') {
    $burntime = 'N/A';
}
if ($avgmonthrain1 == '') {
    $avgmonthrain1 = ${'avrain' . strtolower(strftime("%b"))};
    $avgmonthsnow1 = ${'avsnow' . strtolower(strftime("%b"))};
    $avgmonthtemp1 = ${'avtemp' . strtolower(strftime("%b"))};
}
if (preg_match('|in|i', $uomSnow)) { // use USA measurements
    $sndiff = $snowmonthin - $avgmonthsnow1; //calculate difference in snow vs avg, inches
    $snowseason = $snowseasonin; // Snow for season you have entered under input daily weather, inches
    $snowmonth = $snowmonthin; // Snow for month you have entered under input daily weather, inches
    $snowtoday = $snowtodayin; // Snow for today you have entered under input daily weather, inches
    $snowyesterday = $snowyesterday; // Yesterdays' snow
    $snownow = $snownowin; // Current snow depth, inches.
    $apparentsolartemp = $apparentsolartempf;    // Apparent Solar
    $avgmonthsnow1 = ($avgmonthsnow1 * .3937);  //Removed for version 3 of the ajax script from Ken True (Thanks Tim for the find)
} elseif (isset($snowseasoncm)) { // use Metric measurements
    $sndiff = $snowmonthcm - $avgmonthsnow1; //calculate difference in snow vs avg, cm
    $snowseason = $snowseasoncm; // Snow for season you have entered under input daily weather, cm
    $snowmonth = $snowmonthcm; // Snow for month you have entered under input daily weather, cm
    $snowtoday = $snowtodaycm; // Snow for today you have entered under input daily weather, cm
    $snowyesterday = $snowyesterday; // Yesterdays' snow
    $snownow = $snownowcm; // Current snow depth, cm.
    $apparentsolartemp = $apparentsolartempc;    // Apparent Solar
}
if ((preg_match('|in|i', $uomRain) and strip_units($wetbulb) > 32)
        or (preg_match('|[A-Za-z]m|i', $uomRain) and strip_units($wetbulb) > 0)) { //Check to see if snow is possible
    $freezing = false;
} else {
    $freezing = true;
}

//$displaySNOW = true; 
if (!$useSNOW) { $freezing = false; } // if useSNOW is set to false, then never allow any snow related items to turn on.
$rndiff = $monthrn - $avgmonthrain1; // calculate difference in rain vs avg
$rndiff_mtd = $monthrn - $avg_mtd_rain; // calculate difference in rain vs avg month-to-date
$sndiff = $snowmonthin - $avgmonthsnow1; //calculate difference in snow vs avg
$decimalComma = (strpos($temperature, ',') !== false) ? true : false; // using comma for decimal point?
//Setup up Lightning Over-ride
if (($useLIGT) and ($lighteningcountlast5minutes > $minLcnt))
    $S3C2 = $S3C2 + 6; // Lightning count in last 5 minutes indicates Lightning present
if (($useSNOW) and ($snownow > 0))
    $S3C2 = $S3C2 + 5;  //Snow is on the ground
//Detect Internet Explorer Browser for Sunlight Pie Chart
$iExplorer = ae_detect_ie();

$wrType = ".gif";

// --- end of initialization code ---------

print "<!--  Alternative-ajax-dashboard.php - 6.92 - 09-Feb-2014 by BurnsvilleWeatherLIVE.com -->\n";
?>
<!-- start of alt-ajax-dashboard.php -->
<div class="ajaxDashboard" align="center">
    <noscript>
    <b style="color:red;">Enable JavaScript for live updates and for changing units-of-measure.</b>
    </noscript>
    <table width="100%" border="0" cellpadding="2" cellspacing="1" style="border:solid; border-color: #CCCCCC;">
<?php
// get last update time from testtags.php
$updTime = mktime($time_hour, $time_minute, 0, $date_month, $date_day, $date_year);
$nextUpd = mktime($time_hour, $time_minute + $tagsInterval, 0, $date_month, $date_day, $date_year);
?>
        <tr align="center">
			<tr align="center">
				<td class="ads" colspan="4" style="text-align: center">
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- Footer Banner -->
					<ins class="adsbygoogle"
						 style="display:inline-block;width:728px;height:90px"
						 data-ad-client="ca-pub-8360343031177119"
						 data-ad-slot="1233353386"></ins>
					<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
					</script>
					<br/><br/>
					<script type="text/javascript" src="http://www.gmodules.com/ig/ifr?url=http://www.wunderground.com/google/stationmap.xml&amp;up_loc=Darfield&amp;up_zoom=Metro&amp;up_units=Metric&amp;synd=open&amp;w=728&amp;h=300&amp;output=js"></script>



					<!-- AddThis Button BEGIN -->
					<div class="addthis_toolbox addthis_default_style ">
					<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
					<a class="addthis_button_tweet"></a>
					<a class="addthis_button_pinterest_pinit" pi:pinit:layout="horizontal"></a>
					<a class="addthis_counter addthis_pill_style"></a>
					</div>
					<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
					<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52536f1f2d555900"></script>
					<!-- AddThis Button END -->
				</td>
			</tr>
		
            <td>
                <div onclick="javascript:ajax_changeUnits();" style="cursor: pointer;" title="Toggle units-of-measure &amp; restart"><b>
                        <span id="uomM" style="color: blue;"><?php langtrans('METRIC'); ?></span> |
                        <span id="uomE" style="color: gray;"><?php langtrans('IMPERIAL'); ?></span></b>
                </div>
            </td>
            <td class="data2" colspan="3" style="text-align: center">
                <span class="ajax" id="ajaxindicator"><?php langtrans('Updated'); ?>:</span>
                <span class="ajax" id="ajaxdate2">
					<?php echo date($SITE['dateOnlyFormat'] . " @ " . $SITE['timeOnlyFormat'], $updTime); ?><!-- Uses Settings.php values -->
                </span>
                <span class="ajax" id="ajaxntimess">&nbsp;</span>
                <script type="text/javascript"><!--
						document.write(' <small><i>(<span id="ajaxcounter">0</span>&nbsp;<?php langtrans('sec ago'); ?>)</i></small>');
          //--></script>
                            <?php if ($ajaxDebug) { // FOR DEBUGGING \\  ?><br />
                    [<span class="ajax" id="ajaxupdatecount"></span> of
                    <span class="ajax" id="ajaxmaxupdatecount"></span> updates]&nbsp;&nbsp;
                    [State: <span class="ajax" id="ajaxState"></span>]&nbsp;&nbsp;
                    [Status: <span class="ajax" id="ajaxStatus"></span>]&nbsp;&nbsp;
                    [Progress: <span class="ajax" id="ajaxProgress">N/A IN PACKED VERSION</span>]
                    <?php } ?>
            </td>
        </tr>
        <tr align="center">
            <td class="datahead"><?php langtrans('Temperature'); ?></td>
            <td class="datahead"><?php langtrans('Current Conditions'); ?></td>
            <td class="datahead" colspan="2"><?php langtrans('Webcam / Realtime Graph'); ?></td>
        </tr>
        <tr align="center">
            <td valign="top" class="data1">
                <table align="center" width="180" border="0" cellpadding="0" cellspacing="4" >
                    <tr>
                        <td align="center" colspan="2" style="border: 1px solid gray;">
                            <?php langtrans('Currently Outside'); ?>:
                            <span class="ajax" id="ajaxtemparrow">
                            <?php echo gen_difference($temperature, strip_units($temperature) - strip_units($tempchangehour), '', 'Warmer %s' . $uomTemp . ' than last hour.', 'Colder %s' . $uomTemp . ' than last hour.');?></span><br/><br/>
                            <strong><span class="ajax" id="ajaxtemp" style="font-size: 38px">
                            <?php echo strip_units($temperature) . $uomTemp; ?>
                                </span></strong><br/><br/>
                            <?php if (($useHC) && ((strip_units($nowTemp) >= $MHI))) { ?>
                                <?php langtrans('Heat Index'); ?>:&nbsp;<span class="ajax" id="ajaxheatidx2">
                                <?php echo strip_units($heati) . $uomTemp; ?></span><br />
                            <?php } // End Use Heat Index or WindChill Readings  ?>
                            <?php if (($useHC) && ((strip_units($nowTemp) < $MWC))) { ?>
                            <?php langtrans('Wind Chill'); ?>:&nbsp;<span class="ajax" id="ajaxwindchill2">
                                <?php echo strip_units($windch) . $uomTemp; ?></span><br />
                            <?php } // End Use Heat Index or WindChill Readings ?><?php if (($useFL) && (abs((strip_units(abs($nowTemp))) - abs($feelslike)) >= 2)) { ?>
                                <?php langtrans('Feels like'); ?>:&nbsp;<span class="ajax" id="ajaxfeelslike">
                                <?php echo strip_units($feelslike) . $uomTemp; ?>
                                </span><br/>

<?php } // End Use Feels Like Display  ?>                          
                            <span class="ajax" id="ajaxheatcolorword"><?php echo $heatcolourword; ?></span><br/>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="text-align: center; border: 1px solid red;">
                            <?php langtrans('High Today'); ?><br/>
                            <strong><span class="ajax" id="ajaxtempmax" style="font-size: 16px"><?php echo strip_units($maxtemp) . $uomTemp; ?></span></strong><br/>
                            <?php echo $maxtempt; ?>
                        </td>
                        <td align="center" valign="top" style="text-align: center; border: 1px solid blue;">
                             <?php langtrans('Low Today'); ?><br/>
                            <strong><span class="ajax" id="ajaxtempmin" style="font-size: 16px"><?php echo strip_units($mintemp) . $uomTemp; ?></span></strong><br/>
                            <?php echo $mintempt; ?>
                        </td>
                    </tr>
                            <?php if ($useYHL) { // Use Yesterday's High/lows ?>
                        <tr>
                            <td align="center" valign="top" style="text-align: center; border: 1px solid red;">
                                <?php langtrans('Yest High'); ?><br/>
                                <strong><span style="font-size: 16px"><span class="convTemp"><?php echo strip_units($maxtempyest) . $uomTemp; ?></span></span></strong><br/>
                                <?php echo $maxtempyestt; ?>
                            </td>
                            <td align="center" valign="top" style="text-align: center; border: 1px solid blue;">
                                <?php langtrans('Yest Low'); ?><br/>
                                <strong><span style="font-size: 16px"><span class="convTemp"><?php echo strip_units($mintempyest) . $uomTemp; ?></span></span></strong><br/>
                                <?php echo $mintempyestt; ?>
                            </td>
                        </tr>
<?php } // End Yesterday's High/Low  ?>
<?php if ($useSTAhilo) { ?>
                <tr>
                    <td align="center" valign="top" style="text-align: center; border: 1px solid red;">
                          <?php langtrans('Sta* High'); ?><br/>
                          <strong><span style="font-size: 16px"><span class="convTemp"><?php echo strip_units($mrecordhightemp) . $uomTemp; ?></span></span></strong><br/>
                          <?php echo rdate($RecDateF, $mrecordhightempmonth, $mrecordhightempday, $mrecordhightempyear); ?>
                    </td>
                    <td align="center" valign="top" style="text-align: center; border: 1px solid blue;">
                          <?php langtrans('Sta* Low'); ?><br/>
                          <strong><span style="font-size: 16px"><span class="convTemp"><?php echo strip_units($mrecordlowtemp) . $uomTemp; ?></span></span></strong><br/>
                          <?php echo rdate($RecDateF, $mrecordlowtempmonth, $mrecordlowtempday, $mrecordlowtempyear); ?>
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="2" style="font-size: 8px">
                                 <?php langtrans('* This Station\'s Monthly High and Low'); ?>
                            </td>
                        </tr>
<?php } // End Station High/Low  ?>
<?php if ($useWU) { // Use WU High/lows  ?>
                <tr>
                            <td align="center" valign="top" style="text-align: center; border: 1px solid red;">
                                    <?php langtrans('Rec* High'); ?><br/>
                        <strong><span style="font-size: 16px"><span class="convTemp"><?php echo strip_units($WUmaxtempr) . $uomTemp; ?></span></span></strong><br/>
                        <?php echo $WUmaxtempryr; ?>
                    </td>
                            <td align="center" valign="top" style="text-align: center; border: 1px solid blue;">
    <?php langtrans('Rec* Low'); ?><br/>
                        <strong><span style="font-size: 16px"><span class="convTemp"><?php echo strip_units($WUmintempr) . $uomTemp; ?></span></span></strong><br/>
                        <?php echo $WUmintempryr; ?>
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="2" style="font-size: 8px">
                        <?php langtrans('* Records from Weather Underground '); ?>
                    </td>
                </tr>
<?php } // End WU High/Low  ?>
                </table>
                    </td>
                    <td align="center" class="data1" valign="top">
                <table align="center" width="180" border="0" cellpadding="0" cellspacing="4">
<?php if ($useORHL) {  //  Show Weather Underground Based New Record HIGH/LOW Image ?>
                <tr>
                    <td colspan ="2">
                        <?php if (strip_units($maxtemp) > strip_units($WUmaxtempr)) {
                            echo "<img src=\"${imagesDir}OfficialRec_HIGH.gif\" alt=\"Official Record High\"/>"; }
                        ?>
                        <?php if (strip_units($mintemp) < strip_units($WUmintempr)) {
                            echo "<img src=\"${imagesDir}OfficialRec_LOW.gif\" alt=\"Official Record Low\"/>"; }
                        ?>
                    </td>
                </tr>
<?php } // end $useORHL  ----------------------------------------  ?>
<?php if ($useSRHL) {  //  Show Local Station Based New Record HIGH/LOW Image ?>
               <tr>
                    <td colspan ="2">
                         <?php
                               if (strip_units($maxtemp) > strip_units($maxtemp4today)) {
                               echo "<img src=\"${imagesDir}StationRec_HIGH.gif\" alt=\"Station Record High\"/>"; }
                         ?>
                         <?php
                               if (strip_units($mintemp) < strip_units($mintemp4today)) {
                               echo "<img src=\"${imagesDir}StationRec_LOW.gif\" alt=\"Station Record Low\"/>";  }
                         ?>
                    </td>
                </tr>
<?php } // end $useSRHL  ---------------------------------------- ?>
                <tr>
                    <td valign="middle" align="center" style="border: none;">
                        <span class="ajax" id="ajaxconditionicon2">
                        <img src="<?php echo $condIconDir . newIcon($iconnumber) ?>" alt="<?php $t1 = $current_summary; echo $t1; ?>"
                        title="<?php echo $t1; ?>" height="74" width="74" />
                        </span>
                    </td>
                    <td align="center" style="text-align: center; border: 1px solid gray;">
                        <span class="ajax" id="ajaxcurrentcond">
                        <?php echo $t1; ?> </span><br/><br/>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <span class="ajax" id="ajaxwindiconwr">
                            <?php
                                $wr = $imagesDir . $wrName . $dirlabel . $wrType; // normal wind rose
                                $wrtext = langtransstr('Wind from') . " " . langtransstr($dirlabel);
                                if ((strip_units($avgspd) + strip_units($gstspd) < 0.1 ) and
                                        ($wrCalm <> '')) { // use calm instead
                                    $wr = $imagesDir . $wrCalm;
                                    $wrtext = $bftspeedtext; }
                            ?>
                            <img src="<?php echo $wr; ?>" 
                                height="<?php echo $wrHeight; ?>" width="<?php echo $wrWidth; ?>" 
                                title="<?php echo $wrtext; ?>" 
                                alt="<?php echo $wrtext; ?>"  style="text-align:center" />
                        </span> 
                    </td>
                    <td valign="middle" style="text-align: center; border: 1px solid gray;" >
                        <?php langtrans('Wind'); ?>:&nbsp;
                            <strong><span style="font-size: 14px" class="ajax" id="ajaxwinddir"> <?php echo langtrans($dirlabel); ?></span><br />
                            <span style="font-size: 14px" class="ajax" id="ajaxwind">
                            <?php echo strip_units($avgspd); ?></span></strong><br />
                            <span class="meas"><?php langtrans('Gusting to'); ?>:<br /></span>
                            <span class="ajax" id="ajaxgust"><?php echo strip_units($gstspd) . " $uomWind"; ?></span>
                    </td>
                </tr>
                <tr>
<?php if (!$freezing) { ?>
                    <td valign="middle" style="text-align: center;" >
                        <?php echo "<img src=\"${imagesDir}raindrop.jpg\" alt=\"Current Rain\" />"; ?>
                    </td>
                    <td valign="middle" style="text-align: center; border: 1px solid gray;" >
                        <?php langtrans('Rain'); ?>:<br/>
                        <span style="font-size: 18px" class="ajax" id="ajaxrain">
                        <?php echo strip_units($dayrn) . $uomRain; ?></span>
                    </td>
<?php } // end warmer then freezing ?>
<?php if ($freezing && $useSNOW) { ?>
                    <td valign="middle" style="text-align: center;" >
                        <?php echo "<img src=\"${imagesDir}snowflake.jpg\" alt=\"Current Snow\" />"; ?>
                    </td>
                    <td valign="middle" style="text-align: center; border: 1px solid gray;" >
                        <?php langtrans('Snow'); ?><sup>3</sup>:<br/>
                        <span style="font-size: 18px" class="ajax" id="ajaxsnowToday">
                        <span class="convSnow"><?php printf("%8.2f", $snowtoday); echo $uomSnow; ?></span></span>
                    </td>
<?php } elseif ($freezing && !$useSNOW) { ?>
                    <td valign="middle" style="text-align: center;" >
                        <?php echo "<img src=\"${imagesDir}snowflake.jpg\" alt=\"Current Snow\" />"; ?>
                    </td>
                    <td valign="middle" style="text-align: center; border: 1px solid gray;" >
                        <?php langtrans('SnowMelt'); ?>:<br/>
                        <span style="font-size: 18px" class="ajax" id="ajaxrain">
                        <?php echo strip_units($dayrn) . $uomRain; ?></span>
                    </td>
<?php } // end cooler then freezing  ?>
                </tr>
                <tr>
<?php if ($useFWI) {  //  Show Fire Weather Index data ?>
                    <td style="text-align: center;"><span id="ajaxfireimg">
                        <?php switch ($firewi) {
                            case ($firewi < 14):
                                echo "<img src=\"${imagesDir}FWIFire0.${FireImage}\" width='74' height='74' alt=\"Fire Weather Index: MINIMAL\"/>";
                                break;
                            case ($firewi < 35):
                                echo "<img src=\"${imagesDir}FWIFire1.${FireImage}\" width='74' height='74' alt=\"Fire Weather Index: LOW\"/>";
                                break;
                            case ($firewi < 64):
                                echo "<img src=\"${imagesDir}FWIFire2.${FireImage}\" width='74' height='74' alt=\"Fire Weather Index: MODERATE\"/>";
                                break;
                            case ($firewi < 122):
                                echo "<img src=\"${imagesDir}FWIFire3.${FireImage}\" width='74' height='74' alt=\"Fire Weather Index: VERY HIGH\"/>";
                                break;
                            case ($firewi <= 232):
                                echo "<img src=\"${imagesDir}FWIFire4.${FireImage}\" width='74' height='74' alt=\"Fire Weather Index: EXTREME\"/>";
                                break;
                            case ($firewi > 232):
                                echo "<img src=\"${imagesDir}FWIFire5.${FireImage}\" width='74' height='74' alt=\"Fire Weather Index: CATASTROPHIC\"/>";
                                break;
                        } // end switch
                        ?></span>
                    </td>
                    <td class="data1" valign="middle" style="text-align: center; border: 1px solid gray;" >
                        <?php langtrans('Current FWI'); ?>:<sup>4</sup><br/><a href="<?php echo $fwi_url?>" target="_blank" title="USFS Wildfire Danger"><?php langtrans('Fire Danger'); ?>:</a><br />
                            <span class="ajax" id="ajaxfireindex"><?php echo $firewi; ?></span> of 232 <br/>
                    </td>
<?php } // end $useFWI   ?>
<?php if ($useCBI) {  //  Show Chandler Buring Index data ?>                  
                    <td style="text-align: center;"><span id="ajaxcbiimg">
					<?php list($chandler,$chandlertxt,$chandlerimg) = CU_CBI($temperature,$uomtemp,$humidity); ?>
                        <?php switch ($chandler) {
                            case ($chandler <= 0):
                                echo "<img src=\"${imagesDir}Fire0.${FireImage}\" width='74' height='74' alt=\"Chandler Burning Index: LOW\" />";
                                break;
                            case ($chandler < 50):
                                echo "<img src=\"${imagesDir}Fire0.${FireImage}\" width='74' height='74' alt=\"Chandler Burning Index: LOW\"/>";
                                break;
                            case ($chandler < 75):
                                echo "<img src=\"${imagesDir}Fire16.${FireImage}\" width='74' height='74' alt=\"Chandler Burning Index: MODERATE\"/>";
                                break;
                            case ($chandler < 90):
                                echo "<img src=\"${imagesDir}Fire25.${FireImage}\" width='74' height='74' alt=\"Chandler Burning Index: HIGH\"/>";
                                break;
                            case ($chandler <= 97.5):
                                echo "<img src=\"${imagesDir}Fire31.${FireImage}\" width='74' height='74' alt=\"Chandler Burning Index: VERY HIGH\"/>";
                                break;
                            case ($chandler > 97.5):
                                echo "<img src=\"${imagesDir}Fire32.${FireImage}\" width='74' height='74' alt=\"Chandler Burning Index: EXTREME\"/>";
                                break;
                        } // end switch
                        ?></span>
                    </td>
                    <td class="data1 center" valign="middle" style="text-align: center; border:1px solid gray;" >
                        <?php langtrans('Current CBI'); ?><br /><?php langtrans('Fire Danger'); ?>:<br />
                            <span class="ajax" id="ajaxcbiindex"><?php echo $chandler; ?></span> of 100 <br/>
                    </td>
<?php } // end $useCBI   ?>                  
                </tr>
            </table>
                    </td>
                    <td class="data1" valign="top" align="center" colspan="3" style="border-bottom:0px">
            <table align="center" width="230" border="0" cellpadding="0" cellspacing="4">
                <tr>
                    <td class="data1" align="center" style="text-align: center; font-size: 11px; border: 1px solid gray;" >
                      <a href="image.jpg"><img src="/image.jpg" id="myPull" alt="Current Webcam Image Darfield, NZ." title="Current Webcam Image Darfield, NZ." width="320" height="150"/></a>
					  
					  <span class="ajax" id="imagereLoader">
						<script type="text/javascript"><!--
						//<![CDATA[
						//function reloadWebcam() {
						//	var now = new Date();
						//	if (document.images && (ajaxUpdates < update.maxupdates)) {
						//		document.images.myPull.src = 'image.jpg?' + now.getTime();
						//	}
						//	setTimeout('reloadWebcam()',15000);
						//}
						//setTimeout('reloadWebcam()',15000);
						//]]>
						-->
						</script>
            		</span>

                    </td>
                </tr>
<?php if ($use5DAYLINK) { ?>
                <tr>
                    <td align="center" colspan="3" style="border-style:outset; border-width: 0;">
                        <br/><strong><a href="wxsimforecast.php" title="Click Here for Local 5-day Forecast">
                        <?php langtrans('Click here for full Forecast'); ?></a></strong>
                    </td>
                </tr>
<?php } // End of use 5 day link  ?>            
                <tr>
                    <td align="center" colspan="3">
                        <span id="container" style="width: 350px; height: 175px;"></span>

						<!-- JQuery script -->
						<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

						<!-- Basic Highcharts script -->
						<script type="text/javascript" src="./highcharts/js/highcharts.js"></script>
						<!-- Add some 'styling' -->
						<script type="text/javascript" src="./highcharts/js/themes/grid.js"></script>

						<!-- Additional files for the Highslide popup effect -->
						<script type="text/javascript" src="http://www.highcharts.com/highslide/highslide-full.min.js"></script>
						<script type="text/javascript" src="http://www.highcharts.com/highslide/highslide.config.js"></script>

						<script type="text/javascript" src="./realtimeCumulus.js"></script>
                    </td>
                </tr>
            </table>
                    </td>
                </tr>
                <tr align="center">
                    <td class="datahead"><?php langtrans('Dew Point'); ?></td>
                    <td class="datahead"><?php langtrans('Liquid Precipitation'); ?></td>
                    <td class="datahead"><?php langtrans('Wind Speed'); ?></td>
                    <td class="datahead"><?php langtrans('Sun/Moon'); ?></td>
                </tr>
                <tr>
                    <td align="center" valign="middle">
            <table width="180" border="0" cellpadding="2" cellspacing="0">
                <tr>
                    <td class="data1" nowrap="nowrap" ><?php langtrans('Current'); ?>: </td>
                    <td class="data1" style="text-align: right;" >
                        <?php
                            $t1 = strip_units($dewpt);
                            $dewArrow = gen_difference(
                                $t1 + $dewchangelasthour, $t1, '', 'Increased %s' . $uomTemp . ' since last hour.', 'Decreased %s' . $uomTemp . ' since last hour.');
                            echo $dewArrow;
                        ?>
                        <span class="ajax" id="ajaxdew"><span class="convTemp">
                        <?php echo $t1 . $uomTemp; ?></span></span>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap" ><?php langtrans('Last Hour'); ?>: </td>
                    <td class="data1" style="text-align: right;" >
                        <?php echo $dewArrow; ?>
                        <span class="convDdif"><?php echo strip_units(abs($dewchangelasthour)) . $uomTemp; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap" style="font-size: 10px;"><?php langtrans('High '); ?><?php echo $maxdewt; ?>: </td>
                    <td class="data1" nowrap="nowrap" style="font-size: 10px; text-align: right;" >
                        <span class="convTemp"><?php echo strip_units($maxdew) . $uomTemp; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap" style="font-size: 10px;"><?php langtrans('Low '); ?><?php echo $mindewt; ?>: </td>
                    <td class="data1" nowrap="nowrap" style="font-size: 10px; text-align: right;" >
                        <span class="convTemp"><?php echo strip_units($mindew) . $uomTemp; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap" ><?php langtrans('Record High'); ?>: </td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;" >
                        <span class="convTemp"><?php echo strip_units($recordhighdew) . $uomTemp . ' on'; ?></span><br />
                        <?php echo rdate($RecDateF, $recordhighdewmonth, $recordhighdewday, $recordhighdewyear); ?>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap" ><?php langtrans('Record Low'); ?>: </td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;" >
                        <span class="convTemp"><?php echo strip_units($recordlowdew) . $uomTemp . ' on'; ?></span><br />
                        <?php echo rdate($RecDateF, $recordlowdewmonth, $recordlowdewday, $recordlowdewyear); ?>
                    </td>
                </tr>
                <tr>
                    <td class="data1" style="text-align: left;" ><?php langtrans('Wetbulb'); ?>:</td>
                    <td class="data1" style="text-align: right;" >
                        <span class="ajax" id="ajaxwetbulb"><span class="convTemp">
                        <?php echo strip_units($wetbulb) . $uomTemp; ?></span></span>
                    </td>
                </tr>
<?php if ($useLEAF) {  //  Show Leaf Wetness if disabled you may want to change the class="data1" in the above two spots to class="data2" ?>
                <tr>
                    <td class="data2" nowrap="nowrap"><?php langtrans('Leaf Wet'); ?>:<sup>7</sup></td>
                    <td class="data2" nowrap="nowrap" style="text-align: right;">
                        <span class="ajax" id="ajaxVPleaf"><?php printf("%2.0f", $VPleaf); ?></span>
                    </td>
                </tr>
<?php } // End Use Leaf ?>
            </table>
                    </td>
                    
                    <td valign="middle" align="center" rowspan="3">
            <table width="180" border="0" cellpadding="0" cellspacing="2">
<?php if ($useRainGraph) {  // Show Rain Graph if this option is selected ?>
                <tr>
                    <td class="data1" colspan="2" style="text-align: center;">
                        <span class="ajax" id="imagereLoader">
                            <script type="text/javascript">
                            <!--
                            //<![CDATA[
                            function reloadImage() {
                            var now = new Date();
                            if (document.images && (ajaxUpdates < update.maxupdates)) {
                            document.images.RainGraph.src ='<?php echo $RainGraphImage ?>?' + now.getTime();
                            }
                            setTimeout('reloadImage()',15000);
                            }
                            setTimeout('reloadImage()',15000);
                            //]]>
                            -->
                        </script>
                        </span>
                        <?php echo "Rain Today"; ?><br />  
                        <img id="RainGraph" src="<?php echo $RainGraphImage ?>" width='178' alt="rain graph" /><br/><br />  
                    </td>
                </tr>
<?php }  // End of Rain Graph ?>
                <tr>
                    <td class="data1">
                        <?php if ($freezing) {
                            echo langtrans('Melt Today');
                        } else {
                            echo langtrans('Today'); }
                       ?>:
                    </td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;">
                        <span class="ajax" id="ajaxrain2"><?php printf("%8.2f", strip_units($dayrn)); echo $uomRain; ?></span>
                    </td>
                </tr>
<?php if (strip_units($currentrainratehr) > 0) {  //  Show Rain Rates ?>
                <tr>
                    <td class="data1" nowrap="nowrap" >Rate (<?php echo $uomPerHour; ?>):</td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;">
                        <span class="ajax" id="ajaxrainratehr"><?php echo strip_units($currentrainratehr) . $uomRain; ?></span>
                    </td>
                </tr>
<?php } // End Show Rain Rates ?>
<?php if (($DavisVP) && ($vpstormrain > 0)) { // Storm Rain is a Davis VP thing  ?>
                <tr>
                    <td class="data1" nowrap="nowrap"><?php langtrans('Storm Rain'); ?>: </td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;"><span class="convRain">
                        <?php echo strip_units($vpstormrain) . $uomRain; ?></span>
                    </td>
                </tr>
<?php } // end of DavisVP specific variable ?>
                <tr>
                    <td class="data1" nowrap="nowrap"><?php langtrans('Yesterday'); ?>: </td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;">
                        <span class="ajax" id="ajaxrainYes"><?php printf("%8.2f", strip_units($yesterdayrain)); echo $uomRain; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap"><?php langtrans('Last 7 Days'); ?>: </td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;">
                        <span class="convRain"><?php printf("%8.2f", strip_units($raincurrentweek)); echo $uomRain; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap" ><?php echo substr($monthname, 0, 3) . " Rain:"; ?></td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;">
                        <span class="ajax" id="ajaxrainmo"><?php printf("%8.2f", strip_units($monthrn)); echo $uomRain; ?></span>
                    </td>
                </tr>
<?php if ($use_historical) { ?>
                <tr>
                   <td class="data1" nowrap="nowrap" >
                       <span style="font-size:8.5px; line-height:120%">
                       <?php echo substr($monthname, 0 , 3).' ' ?> <?php echo langtrans('to Date');?><br/>&nbsp;&nbsp;<?php echo langtrans('Avg')?>:<sup>10</sup></span>
                    </td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;">
                        <span class="convRain"><?php printf( "%8.2f",$avg_mtd_rain); echo $uomRain; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap" >
                        <span style="font-size:8.5px; line-height:120%"><?php echo substr($monthname ,0 ,3) ?><?php echo langtrans(' to Date')?><br/>&nbsp;&nbsp;<?php echo langtrans('Diff from Avg'); ?>:<sup>10</sup></span>
                    </td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;">
                        <?php echo gen_difference(strip_units($monthrn), $avg_mtd_rain, '',
			'Over %s'.$uomRain.' for the month',
			'Under %s'.$uomRain.' for the month');
                        ?>
                        <span class="convRain"><?php printf( "%8.2f",abs(strip_units($rndiff_mtd))); echo $uomRain; ?></span>
                    </td>
                </tr>
<?php } // End use Historical ?>
                <tr>
                    <td class="data1" nowrap="nowrap" ><?php echo substr($monthname ,0 ,3) . " Avg:"; ?></td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;">
                        <span class="convRain"><?php printf("%8.2f", $avgmonthrain1); echo $uomRain; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap"><?php langtrans('Diff from Avg'); ?>:</td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;">
                        <?php echo gen_difference(strip_units($monthrn), $avgmonthrain1, '', 'Over %s' . $uomRain . ' for the month', 'Under %s' . $uomRain . ' for the month'); ?>
                            <span class="convRain"><?php printf("%8.2f", abs(strip_units($rndiff)));echo $uomRain; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap"><?php langtrans('Season'); ?>:<sup>1</sup></td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;">
                        <span class="ajax" id="ajaxrainyr"><?php echo strip_units($yearrn) . $uomRain; ?></span>
                    </td>
                </tr>
<?php if ($use_historical) { ?>
                <tr>
                    <td class="data1" nowrap="nowrap" >
                        <span style="font-size:8.5px; line-height:120%"><?php echo langtrans('YTD');?><br/>&nbsp;&nbsp;<?php echo langtrans('Avg')?>:<sup>11</sup></span>
                    </td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;">
                        <span class="convRain"><?php printf( "%8.2f",$avg_ytd_rain); echo $uomRain; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap" >
                        <span style="font-size:8.5px; line-height:120%"><?php echo langtrans('YTD')?><br/>&nbsp;&nbsp;<?php echo langtrans('Diff from Avg'); ?>:<sup>11</sup></span>
                    </td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;">
                        <?php echo gen_difference(strip_units($yearrn), $avg_ytd_rain, '',
			'Over %s'.$uomRain.' for the year',
			'Under %s'.$uomRain.' for the year');
                        ?>
                        <span class="convRain"><?php printf( "%8.2f",abs(strip_units($yearrn)-strip_units($avg_ytd_rain))); echo $uomRain; ?></span>
                    </td>
                </tr>
<?php } else { ?>
                <tr>
                    <td class="data1" nowrap="nowrap"><?php langtrans('Last YTD'); ?>:<sup>1</sup></td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;">
                        <span class="convRain"><?php echo $raintodateyearago . $uomRain; ?></span>
                    </td>
                 </tr>
<?php } // End use Historical ?>
<?php if (strip_units($currentrainratehr) == 0) {  //  Show last tip  ?>
                <tr>
                    <td class="data1" nowrap="nowrap" ><?php langtrans('Last Rain'); ?><br/><?php langtrans('Time/Date'); ?>: </td>
                    <td class="data1" nowrap="nowrap" style="font-size: 9px; text-align: right;">
                        <?php $_array = split_hash_date($dateoflastrainalways) ?>
                        <?php echo rdate($RecDateF, $_array[1], $_array[0], $_array[2]); ?> <br/>
                        <?php echo "at " . $timeoflastrainalways; ?>
                    </td>
                </tr>
<?php } // End Show Last Tip ?>
                <tr>
                    <td class="data2" colspan="2" style="text-align: center;" >
                        <?php
                            print "$dayswithrainyear";
                            print ($dayswithrainyear != 1) ? langtransstr(' rain days in') : langtransstr(' rain day in');
                            print " $date_year"; 
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="data2" colspan="2" style="text-align: center;">
                        <?php
                            print "$dayswithrain ";
                            print ($dayswithrain != 1) ? langtransstr('days in') : langtransstr('day in');
                            print " " . $monthname;
                        ?><br/>
                        <?php
                            print "$dayswithnorain ";
                            print ($dayswithnorain != 1) ? langtransstr('days since last rain') : langtransstr('day since last rain');
                        ?>
                    </td>
                </tr>
            </table>
                    </td>
                    <td valign="middle" align="center">
            <table width="180" border="0" cellpadding="2" cellspacing="0">
                <tr>
                    <td class="data1" nowrap="nowrap" align="center" ><?php langtrans('Current'); ?>: </td>
                    <td class="data1" nowrap="nowrap" style="text-align: right; ">
                        <span class="ajax" id="ajaxwinddir2">
                        <?php echo $dirlabel; ?></span>&nbsp;
                        <span class="ajax" id="ajaxwind2">
                        <?php echo strip_units($avgspd) . $uomWind; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data1" align="center" nowrap="nowrap"><?php langtrans('1Hr Average'); ?>: </td>
                    <td class="data1" style="text-align: right;">
                        <?php if (strip_units($avwindlastimediate60) > 0) { ?>
                            <span class="convWind"><?php echo strip_units($avwindlastimediate60) . $uomWind; ?></span>
                        <?php } else {
                            echo langtrans('Calm');
                        } ?>
                    </td>
                </tr>
                <tr>
                    <td class="data2" nowrap="nowrap"><?php langtrans('Wind Run'); ?>: </td>
                    <td class="data2" nowrap="nowrap" style="text-align: right;">
                        <!--<span class="ajax" id="ajaxwindrun"><span class="convDist"><?php echo $windruntoday . " " . $uomDistance; ?></span></span> -->
						<span class="convDist"><?php echo $windruntoday . " " . $uomDistance; ?></span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;" class="datahead" colspan="2">
                        <?php echo " " . langtransstr('Wind Gust'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap" align="center"><?php langtrans('Current'); ?>: </td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;">
                        <span class="ajax" id="ajaxgust2"><?php echo $gstspd . $uomWind; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap" style="font-size: 11px;"><?php langtrans('Today'); ?>: </td>
                    <td class="data1" nowrap="nowrap" style="font-size: 10px; text-align: right;">
                        <span class="ajax" id="ajaxwindmaxgust2"><?php echo strip_units($maxgst); ?></span><?php echo $uomWind; ?>
                        <span class="ajax" id="ajaxwindmaxgusttime"><?php echo $maxgstt; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap" style="font-size: 11px;"><?php langtrans('Month'); ?>: </td>
                    <td class="data1" nowrap="nowrap" style="font-size: 10px; text-align: right; ">
                        <span class="convWind"><?php echo $mrecordwindgust . $uomWind; ?></span>
                        <?php echo rdate($ShortDateF, $date_month, $mrecordhighgustday,0); ?>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap" style="font-size: 11px;"><?php langtrans('Year'); ?>:</td>
                    <td class="data1" nowrap="nowrap" style="font-size: 10px; text-align: right; ">
                        <span class="convWind"><?php echo " " . $yrecordwindgust . $uomWind; ?></span>
                        <?php echo rdate($ShortDateF,  $yrecordhighgustmonth, $yrecordhighgustday,0); ?>
                    </td>
                </tr>
                <tr>
                    <td class="data2" nowrap="nowrap"><?php langtrans('Record Gust:'); ?></td>
                    <td class="data2" nowrap="nowrap" style="text-align: right;">
                        <span class="convWind"><?php echo " " . $recordwindgust . $uomWind; ?></span><br/>
                        <?php echo rdate($RecDateF, $recordhighgustmonth, $recordhighgustday, $recordhighgustyear); ?>
                    </td>
                </tr>
            </table>
                    </td>
                    <td rowspan="3" valign="middle" align="center">
            <table border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td class="data1" style="text-align: center; background:transparent"><?php langtrans('Sunlight'); ?>:<br/>
                        <?php $time_arr = explode(':', $changeinday);
                        if ($time_arr[1] < 8) {         //Check for bogus minute differences on Standard Time <=> Sunlight Time Change Days
                        if ($useSunlightPie && (substr_count($changeinday, ':') == 2 ) && !ae_detect_ie())  {  //Make piechart if using WD V10.37R >=b51 ?> 
                    <table align="center"><tr><td style="width:80px">
                        <?php $timebits = explode(":", $hoursofpossibledaylight);
                            $testresult = $timebits[0] + $timebits[1] / 60;
                            $test1jus = round(($testresult / 24) * 100, 1);
                            $testmorker = 100 - $test1jus; ?>
                            <span class="data2" style="position: relative; font-weight:lighter; font-size:9px; line-height:140%; background:transparent">
                        <?php
                            $time_arr = explode(':', $changeinday);
                            $time_arr[0]>0?$cidsign = '+': $cidsign ='-';
                            if ($time_arr[1]< 5) {
                            $time_arr = explode(':', $hoursofpossibledaylight, 2);
                            echo (int) $time_arr[0] . " hrs " . (int) $time_arr[1] . " min";
                            echo '<br/>of Sunlight Today<br/>';
                            $pieStart = 1.61 - round((((((int)$time_arr[0]*60)+(int)$time_arr[1])-720)/360),2);
                        ?></span>
<?php if ($useSymSPC) {                                                     
                            $pieStart = 1.61 - round(((((int)$time_arr[0]*60+(int)$time_arr[1])-720)/360),2);?>
                            <?php echo !$iExplorer? '<a href="wxastronomy.php" target="_blank" title="Astronomy Page in New Tab">':''?><img style="margin-top:<?php echo !$iExplorer? 4:-4?>px; <?php echo $iExplorer? 'margin-bottom:-18px':''?>" src="http://chart.apis.google.com/chart?chs=90x60&amp;chma=0,0,0,0&amp;chd=t:<?php echo $test1jus;?>,<?php echo $testmorker;?>&amp;cht=p3&amp;chp=<?php echo $pieStart?>&amp;chf=bg,s,65432100&amp;chco=FFD700,000000" alt="graph" width="80"/><?php echo !$iExplorer? '</a>':''?>
                            <?php } else { ?>
                            <?php   echo !$iExplorer? '<a href="wxastronomy.php" target="_blank" title="Astronomy Page in New Tab">':''?><img style="margin-top:<?php echo !$iExplorer? 4:-4?>px; <?php echo $iExplorer? 'margin-bottom:-18px':''?>" src="http://chart.apis.google.com/chart?chs=90x60&amp;chma=0,0,0,0&amp;chd=t:<?php echo $test1jus;?>,<?php echo $testmorker;?>&amp;cht=p3&amp;chp=1.61&amp;chf=bg,s,65432100&amp;chco=FFD700,000000" alt="graph" width="80"/><?php echo !$iExplorer? '</a>':''?>
<?php } // End useSymSPC ?>
                            <span style="position:relative; float:left; margin-top:<?php echo!$iExplorer ? -36 : -18 ?>px; margin-left:12px; font-weight:lighter; color:#000000; font-size:8px">        <!-- was -5 -->
                            <?php echo $test1jus . "%"; ?></span>
                            <span style="position:relative; float:right; margin-top:<?php echo!$iExplorer ? -36 : -18 ?>px; margin-right:10px; font-weight:lighter; color:#FFFFFF; font-size:8px">      <!-- was 5 -->
                            <?php echo $testmorker . "%"; ?></span>
                            <?php echo $iExplorer ? '<br/>' : '' ?>
                            <span class="data2" style="position:relative; margin-top:10px; font-weight:lighter; font-size:9px; line-height:140%; background:transparent">
                            <?php
                                echo '<br/>Which is <br/>';
/*				daylengthdiff = $todaylength - $yesterdaylength;
				echo floor(abs($daylengthdiff)/60)." min ".abs($daylengthdiff)%60; echo(($daylengthdiff<0)?" sec Shorter":" sec Longer");
*/				$time_arr = explode(':', $changeinday);
                                if (substr($time_arr[0], 0, 1) != "-") {         // -0 when getting shorter
                                    echo (int) $time_arr[1] . " min " . $time_arr[2] . " sec Longer";
                                } else {
                                    echo (int) $time_arr[1] . " min " . $time_arr[2] . " sec Shorter";
                                }
                                echo ('<br />Than Yesterday<br />&nbsp;');
                            ?></span> </td></tr></table>
                        <?php } else { echo 'Unavailable<br/>&nbsp;'; ?></span></td></tr></table>
                        <?php } } else { ?>  <!-- Use Original AltAjaxDashboard6 Code -->
                        <?php echo "<img src=\"${imagesDir}sun.jpg\" alt=\"Possible hours of Sunlight\" />" ?><br/>
                        <?php echo $hoursofpossibledaylight; ?><br/><br/>
                        <?php
                            if (substr_count($changeinday, ':') == 2) {            // using V10.37R >= b51
                            $time_arr = explode(':', $changeinday);
                            if (substr($time_arr[0], 0, 1) != "-") {         // -0 when getting shorter
                                echo '+';
                                } else {
                                echo '-';
                                } echo (int) $time_arr[1] . ":" . $time_arr[2] . "<br/>Min:Sec";
                                } else {                                                                                                                                                  // using version earlier than V10.37R b51
                            if (substr_count($changeinday, '-') == 0) {
                                echo ("+" . "$changeinday");
                                } else {
                                echo "$changeinday";
                                }
                            ?><br/> <?php langtrans('Minutes'); } ?><br/><br/>
                        <?php } ?>
                        <?php } else { ?>
                        <span class="data2">
                        <?php echo 'Sunlight<br/>Hours<br/>' ?></span>
                        <span class="data1"><?php echo 'Unavailable<br/>&nbsp;'; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td class="data2" style="text-align: center; font-size: 12px;">
                    <span class="ajax" id="ajaxmoonphase"><?php getMoonPhase(); ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data2" style="text-align: center; font-size: 12px;">
                    <span class="ajax" id="ajaxmoonphasename"><?php echo $moonphasename; ?></span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;"><span id="ajaxmoonimg"><?php
                        echo '<img src="' . $imagesDir . 'moon/w/' . $moonHemisphere . '-moon' . str_pad(getMoonPic(), 2, "0", STR_PAD_LEFT) . '.gif"
                        alt  ="' . getMoonPhase() . ', ' . getMoonAge() . ' in cycle"
                        title="' . getMoonPhase() . ', ' . getMoonAge() . ' in cycle"
                        width="60" height="60" style="border: 0;" />'; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data2" style="text-align: center; font-size: 12px;" >
                        <span class="ajax" id="ajaxmoonpct"><?php echo getMoonIll() . "%"; ?></span><br/>
                        <?php langtrans(' Illuminated'); ?>
                    </td>
                </tr>
            </table>
                    </td>
                </tr>
                <tr align="center" >
                    <td class="datahead"><?php langtrans('Humidity'); ?></td>
                    <td class="datahead"><?php langtrans('Barometer'); ?></td>
                </tr>
                <tr>
                    <td align="center">
            <table width="180" border="0" cellpadding="2" cellspacing="0">
                <tr>
                    <td class="data1"><?php langtrans('Current'); ?>:</td>
                    <td class="data1" style="text-align: right;">
                        <?php $t1 = preg_replace('|\s|s', '', $humchangelasthour);
                        echo gen_difference($humidity, $humidity - $t1, '', 'Increased %s%% since last hour.', 'Decreased %s%% since last hour.'); ?>
                        <span class="ajax" id="ajaxhumidity"><?php echo $humidity; ?></span>%
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap"><?php langtrans('Last Hour'); ?>:</td>
                    <td class="data1" style="text-align: right;">
                        <?php echo gen_difference($humidity, $humidity - $t1, '', 'Increased %s%% since last hour.', 'Decreased %s%% since last hour.');?>
                        <?php echo abs($humchangelasthour) . '%'; ?>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap" style="font-size: 10px;"><?php langtrans('High '); ?><?php echo $highhumt; ?>:</td>
                    <td class="data1" nowrap="nowrap" style="font-size: 10px; text-align: right;" >
                        <?php echo $highhum . '%'; ?>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap" style="font-size: 10px;"><?php langtrans('Low '); ?><?php echo $lowhumt; ?>:</td>
                    <td class="data1" nowrap="nowrap" style="font-size: 10px; text-align: right;" >
                        <?php echo $lowhum . '%'; ?>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap" ><?php langtrans('Record High'); ?>: </td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;" >
                        <?php echo strip_units($recordhighhum) . '%'; ?><br />
                        <?php echo rdate($RecDateF, $recordhighhummonth, $recordhighhumday, $recordhighhumyear); ?>
                    </td>
                </tr>
                <tr>
                    <td class="data2" nowrap="nowrap" ><?php langtrans('Record Low'); ?>: </td>
                    <td class="data2" nowrap="nowrap" style="text-align: right;" >
                        <?php echo strip_units($recordlowhum) . '%'; ?><br />
                        <?php echo rdate($RecDateF, $recordlowhummonth, $recordlowhumday, $recordlowhumyear); ?>
                    </td>
                </tr>
            </table>
                    </td>
                    <td align="center" valign="middle">
            <table width="180" border="0" cellpadding="2" cellspacing="0">
                <tr>
                    <td class="data1" nowrap="nowrap"><?php langtrans('Current'); ?>:</td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;">
                        <span class="ajax" id="ajaxbaroarrow">
                        <?php
                        if (isset($trend)) {
                        if ($commaDecimal) {
                            $Tnow = preg_replace('|,|', '.', strip_units($baro));
                            $TLast = preg_replace('|,|', '.', strip_units($trend));
                        } else {
                            $Tnow = strip_units($baro);
                            $TLast = strip_units($trend);
                        }
                            $decPts = 1;
                        if (preg_match('|in|i', $uomBaro)) {
                            $decPts = 2;
                        }
                            echo gen_difference($Tnow, $Tnow - $TLast, '', langtransstr('Rising') . ' %s ' . $uomBaro . $uomPerHour, langtransstr('Falling') . ' %s ' . $uomBaro . $uomPerHour, $decPts);
                        }
                        ?></span>
                        <span class="ajax" id="ajaxbaro">
                            <?php $t1 = strip_units($baro);
                            echo $t1 . $uomBaro; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap"><?php langtrans('Last Hour'); ?>:</td>
                    <td class="data1" style="text-align: right;">
                       <span class="ajax" id="ajaxbarotrendtext">
                       <?php langtrans($pressuretrendname); ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap" style="font-size: 10px;"><?php langtrans('High '); ?><?php echo $highbarot; ?>:</td>
                    <td class="data1" nowrap="nowrap" style="font-size: 10px; text-align: right;">
                        <span class="ajax" id="ajaxbaromax">
                        <?php echo $highbaro; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap" style="font-size: 10px;"><?php langtrans('Low '); ?><?php echo $lowbarot; ?>:</td>
                    <td class="data1" nowrap="nowrap" style="font-size: 10px; text-align: right;" >
                        <span class="ajax" id="ajaxbaromin">
                        <?php echo $lowbaro; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap" ><?php langtrans('Record High'); ?>: </td>
                    <td class="data1" nowrap="nowrap" style="text-align: right;" >
                        <span class="convBaro"><?php echo strip_units($recordhighbaro) . $uomBaro; ?></span><br />
                        <?php echo rdate($RecDateF, $recordhighbaromonth, $recordhighbaroday, $recordhighbaroyear); ?>
                    </td>
                </tr>
                <tr>
                    <td class="data2" nowrap="nowrap" ><?php langtrans('Record Low'); ?>: </td>
                    <td class="data2" nowrap="nowrap" style="text-align: right;" >
                        <span class="convBaro"><?php echo strip_units($recordlowbaro) . $uomBaro; ?></span><br />
                        <?php echo rdate($RecDateF, $recordlowbaromonth, $recordlowbaroday, $recordlowbaroyear); ?>
                    </td>
                </tr>
            </table>
                    </td>
                </tr>
                <tr align="center" valign="top">
                    <td class="datahead">
                        <?php
                            if (strip_units($nowTemp) >= $coolVal)
                                echo " " . langtransstr('Cooling Degree Days') . " ";
                            else
                                echo " " . langtransstr('Heating Degree Days') . " ";
                        ?>
                    </td>
<?php if (($S3C2 == 3) or ($S3C2 == 4) or ($S3C2 == 10) or ($S3C2 == 11) or ($S3C2 == 15)) { // Show Lightning ?>    
                    <td class="datahead"><?php $S3C2 . langtrans('Lightning Strikes'); ?></td>
<?php } // end $useLIGT  ---------------------------------------- ?>
<?php if (($S3C2 == 1) or ($S3C2 == 6) or ($S3C2 == 9)) {  //  Show Snow ?>
                    <td class="datahead"><?php langtrans('Aurora Forecast'); ?></td>
<?php } // end $freezing  ---------------------------------------- ?>
<?php if (($S3C2 == 2) or ($S3C2 == 5)) {  //  Show Tide, Lightning will over-ride ?>
                    <td class="datahead"><?php langtrans('Tide'); ?></td>
<?php } // end $useTIDE  ----------------------------------------  ?>
<?php if ($S3C2 == 0) {  // Lightning, Tide and snow are False ?>
                    <td class="datahead"><?php langtrans('Coming Soon'); ?></td>
<?php } // end no lightning, tide or snow ?>
                    <td class="datahead">
                    <?php
                        if (strip_units($nowTemp) >= $coolVal)
                            echo " " . langtransstr('Heat Index') . " ";
                        else
                            echo " " . langtransstr('Wind Chill') . " ";
                    ?>
                    </td>
<?php if ($useAQI) { ?>
                    <td class="datahead"><?php langtrans('Cloud Level'); ?></td>
<?php } // End useAQI ?>
                </tr>
                <tr align="center" valign="middle">
                    <td valign="middle">
            <table width="180" border="0" cellpadding="2" cellspacing="0">
                <tr>
                    <td class="data1"><?php langtrans('Today'); ?>:</td>
                    <td style="text-align: right;" class="data1">
                    <?php
                        if (strip_units($nowTemp) >= $coolVal)
                            echo $cddday;
                        else
                            echo $hddday;
                    ?>
                    </td>
                </tr>
                <tr>
                    <td class="data1" style="text-align: left;"><?php echo substr($monthname, 0 ,3); ?>:</td>
                    <td class="data1" style="text-align: right;">
                    <?php
                        if (strip_units($nowTemp) >= $coolVal) echo $cddmonth;
                    else
                        echo $hddmonth;
                    ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;" class="data2"><?php echo $date_year ?> <?php langtrans('to Date'); ?>:</td>
                    <td style="text-align: right;" class="data2">
                    <?php
                        if (strip_units($nowTemp) >= $coolVal)
                            echo $cddyear;
                        else
                            echo $hddyear;
                    ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;" class="datahead" colspan="2">
                    <?php
                        if (strip_units($nowTemp) >= $coolVal)
                            echo " " . langtransstr('Heating Degree Days') . " ";
                        else
                            echo " " . langtransstr('Cooling Degree Days') . " ";
                    ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;" class="data2"><?php echo $date_year ?> <?php langtrans('to Date'); ?>:</td>
                    <td style="text-align: right;" class="data2">
                        <?php
                            if (strip_units($nowTemp) >= $coolVal)
                                echo $hddyear;
                            else
                                echo $cddyear;
                        ?>
                    </td>
                </tr>
            </table>
                    </td>
                    <td align="center" valign="middle" rowspan="3">
            <table width="180" border="0" cellpadding="2" cellspacing="0">
<?php if (($S3C2 == 3) or ($S3C2 == 4) or ($S3C2 == 11) or ($S3C2 == 10) or ($S3C2 == 15)) { // Show Lightning 
      //  Show Lightning if lightning is true and no snow
      //  Show Lightning if lightning count in 5 minutes is greater then $minLcnt (set in config)
      //  This allows the lightning to over-ride the Tide display if you have both TIDE and LIGHTNING Detection.
?>
                <tr>
                    <td class="data1" nowrap="nowrap"><?php langtrans('Last Min'); ?>:</td>
                    <td style="text-align: right;" class="data1">
                        <span class="ajax" id="ajaxlightning"><?php echo $lighteningcountlastminute; ?></span>
                    </td>
                </tr>
<?php if ($use1WIR) {  //  Show only if using 1-Wire ?>
                <tr>
                    <td class="data1" nowrap="nowrap"><?php langtrans('Last 5 Mins'); ?>:</td>
                    <td style="text-align: right;" class="data1">
                        <?php echo $lighteningcountlast5minutes; ?>
                    </td>
                </tr>
<?php } // End Use 1 Wire ?>
                <tr>
                    <td class="data1" nowrap="nowrap"><?php langtrans('Last 30 Mins'); ?>:</td>
                    <td style="text-align: right;" class="data1">
                        <?php if ($use1WIR)
                            echo $lighteningcountlast30minutes;
                        else
                            echo $lighteningcountlast30minutesnextstorm;
                        ?> 
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap"><?php langtrans('Last 60 Mins'); ?>:</td>
                    <td style="text-align: right;" class="data1">
                        <?php if ($use1WIR)
                            echo $lighteningcountlasthour;
                        else
                            echo $lighteningcountlasthournextstorm;
                        ?> 
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap"><?php langtrans('Last 12 Hrs'); ?>:</td>
                    <td style="text-align: right;" class="data1">
                        <?php if ($use1WIR)
                            echo $lighteningcountlast12hour;
                        else
                            echo $lighteningcountlast12hournextstorm;
                        ?> 
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap"><?php echo substr($monthname, 0 ,3); ?>:</td>
                    <td style="text-align: right;" class="data1">
                        <?php echo $lighteningcountmonth; ?>
                    </td>
                </tr>
                <tr>
                    <td class="data1" nowrap="nowrap"><?php echo $date_year ?> <?php langtrans('to Date'); ?>:</td>
                    <td style="text-align: right;" class="data1">
                        <?php echo $lighteningcountyear; ?>
                    </td>
                </tr>
                <tr>
                    <td class="data2"><?php langtrans('Last'); ?><br/><?php langtrans('Strike'); ?>:</td>
                    <td style="text-align: right; font-size: 10px" class="data2">
                        <span class="ajax" id="ajaxlightningtime">
                        <?php echo $lastlightningstriketime; ?></span><br />
                        <?php echo $lastlightningstrikedate; ?>    
                    </td>
                </tr>
<?php if ($useNEX) {  //  Show Only if using NexStorm ?>
                <tr>
                    <td class="data1"><?php langtrans('Last Bearing'); ?>:</td>
                    <td style="text-align: right; font-size: 10px" class="data1">
                        <span class="ajax" id="ajaxlightningbearing">
                        <?php echo $lighteningbearing; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="data2"><?php langtrans('Last Distance'); ?>:</td>
                    <td style="text-align: right; font-size: 10px" class="data2">
                        <span class="ajax" id="ajaxlightningdist">
                        <?php echo $lighteningdistance . $uomDistance; ?></span>
                    </td>
                </tr>
<?php } // End use NEX ?>
<?php } // end $useLIGT  ----------------------------------------  ?>
<?php if (($S3C2 == 1) or ($S3C2 == 6) or ($S3C2 == 9)) {  //  Show Snow ?>
<tr>
               <td class="data1" align="center" style="text-align: center; font-size: 11px;" >
                      <a href="http://legacy-www.swpc.noaa.gov/SWN/sw_dials.gif"><img src="http://legacy-www.swpc.noaa.gov/SWN/sw_dials.gif" alt="Space Weather Dials" title="Space Weather Dials" width="172" height="94"/></a>
				</td> 
            </tr>
			
			<tr>
               <!-- <td class="data1" align="center" style="text-align: center; font-size: 11px;" >
                      					  
					  <a href="http://services.swpc.noaa.gov/images/animations/ovation-south/latest.png"><img src="http://services.swpc.noaa.gov/images/animations/ovation-south/latest.png" alt="Aurora Forecast Model" title="Aurora Forecast Model" width="175" height="175"/></a>

                    </td> --> 
            </tr>


                
<?php } // end $useSNOW  ----------------------------------------  ?>
<?php if (($S3C2 == 2) or ($S3C2 == 5)) {  //  Show Tide, Lightning will over-ride ?>
                        <?php
                        $tide = tide_data(0);
                        $tide1 = tide_data(1);
                        //print_r($tide);
                        //print_r($tide1);
                        $next_tides = array();
                        if (is_array($next_t1 = next_tide_info($tide[1], 0)))
                            $next_tides = $next_tides + $next_t1;
                        if (is_array($next_t2 = next_tide_info($tide[2], 0)))
                            $next_tides = $next_tides + $next_t2;
                        if (is_array($next_t3 = next_tide_info($tide[3], 0)))
                            $next_tides = $next_tides + $next_t3;
                        if (is_array($next_t4 = next_tide_info($tide[4], 0)))
                            $next_tides = $next_tides + $next_t4;
                        if (is_array($next_t5 = next_tide_info($tide[5], 0)))
                            $next_tides = $next_tides + $next_t5;
                        if (is_array($next_t1 = next_tide_info($tide1[1], 1)))
                            $next_tides = $next_tides + $next_t1;
                        if (is_array($next_t2 = next_tide_info($tide1[2], 1)))
                            $next_tides = $next_tides + $next_t2;
                        if (is_array($next_t3 = next_tide_info($tide1[3], 1)))
                            $next_tides = $next_tides + $next_t3;
                        if (is_array($next_t4 = next_tide_info($tide1[4], 1)))
                            $next_tides = $next_tides + $next_t4;
                        if (is_array($next_t5 = next_tide_info($tide1[5], 1)))
                            $next_tides = $next_tides + $next_t5;
                        // figure out wich tide is the next one
                        ksort($next_tides);
                        //print_r ($next_tides);
                        //print '<br>';
                        foreach ($next_tides as $k => $v) {
                            if ($k >= 0) {
                                $mins_to_next_tide = $k;
                                $tide_type = $v;
                                break;
                            }
                            }
                        echo "<tr><td style='text-align:center;'>";
                        $next_tide_time = date('h:i A T', strtotime("+$mins_to_next_tide minutes"));
                        echo "<p>" . time_till_tide($mins_to_next_tide * 60) . " to " .
                        $tide_type . " tide at<br />" . $next_tide_time . "</p>";
                        $mins_to_next_tide_rounded = round_tide_time($mins_to_next_tide);
                        $tide_image = select_tide_image($mins_to_next_tide_rounded, $tide_type);
                        echo "<img src=\"tides/$tide_image\" width=\"150\" alt=\"\"/><br />";
                        echo "</td></tr>";
                        //echo $nexttidequarter;
                        ?>
<?php } // end $useTide ----------------------------------------- ?>              
        </table>
                </td>
                <td align="center">
        <table width="180" border="0" cellpadding="2" cellspacing="0">
<?php if ($showChill) {  //  Show Wind Chill ?>
            <tr>
                <td class="data1"><?php langtrans('Current'); ?>:</td>
                <td style="text-align: right;" class="data1">
                    <span class="ajax" id="ajaxwindchill"><?php echo strip_units($windch) . $uomTemp; ?></span>
                </td>
            </tr>
            <tr>
                <td class="data1" nowrap="nowrap" style="text-align: left;"><?php langtrans('Low '); ?><span class="ajax" id="ajaxwindchillmintime"><?php echo $minwindcht; ?></span>:</td>
                <td class="data1" nowrap="nowrap" style="text-align: right;">
                    <span class="ajax" id="ajaxwindchillmin"><?php echo strip_units($minwindch) . $uomTemp; ?></span>
                </td>
            </tr>
            <tr>
                <td class="data1"><?php langtrans('Yesterday'); ?>:</td>
                <td style="text-align: right;" class="data1">
                    <span class="convTemp"><?php echo strip_units($minchillyest) . $uomTemp; ?></span>
                </td>
            </tr>
            <tr>
                <td class="data2" nowrap="nowrap" style="text-align: left;"><?php langtrans('Record'); ?>:</td>
                <td class="data2" nowrap="nowrap" style="text-align: right;" >
                    <span class="convTemp"><?php echo strip_units($recordlowchill) . $uomTemp; ?></span><br/>
                    <?php echo rdate($RecDateF, $recordlowchillmonth, $recordlowchillday, $recordlowchillyear); ?>
                </td>
            </tr>
<?php } // end $showChill  ?>
<?php if ($showHeat) {  //  Show Heat Index ?>
            <tr>
                <td class="data1"><?php langtrans('Current'); ?>:</td>
                <td style="text-align: right;" class="data1">
                    <span class="ajax" id="ajaxheatidx">
                    <span class="convTemp"><?php echo strip_units($heati) . $uomTemp; ?></span></span>
                </td>
            </tr>
            <tr>
                <td class="data1" nowrap="nowrap" style="text-align: left;"><?php langtrans('High '); ?><?php echo fixup_time($maxheatt); ?>:</td>
                <td class="data1" nowrap="nowrap" style="text-align: right;">
                    <span class="convTemp"><?php echo strip_units($maxheat) . $uomTemp; ?></span>
                </td>
            </tr>
            <tr>
                <td class="data1" nowrap="nowrap"><?php langtrans('Yesterday'); ?>:</td>
                <td class="data1" nowrap="nowrap" style="text-align: right;">
                    <span class="convTemp"><?php echo strip_units($maxheatyest) . $uomTemp; ?></span>
                </td>
            </tr>
            <tr>
                <td class="data2" nowrap="nowrap" style="text-align: left;"><?php langtrans('Record'); ?>:</td>
                <td class="data2" nowrap="nowrap" style="text-align: right;">
                    <span class="convTemp"><?php echo strip_units($recordhighheatindex) . $uomTemp; ?></span><br/>
                    <?php echo rdate($RecDateF, $recordhighheatindexmonth, $recordhighheatindexday, $recordhighheatindexyear); ?>
                </td>
            </tr>
<?php } // end $showHeat  ?>
        </table>
                </td>
<?php if ($useCloudImg) { // Display Cloud Height Graphic from Bashewa Weather ?>
                <td align="center" valign="middle" rowspan="3">
					<table border="0" cellpadding="2" cellspacing="0">
						<tr>                
							<td class="data1" align="center" style="font-size: 9px; border:none;text-align: center">
								<span class="ajax" id="ajaxcloudheightimg">
								<img src="cloud-base.php?uom=M" alt="" title="<?php echo $cloudheightmeters; ?>m ASL" height="200" width="90" /></span>
							</td>
<?php } // End Use Cloud Image ?>
            </tr>
        </table>
                </td>
            </tr>
                <?php
                    $leftHead = 'UV Summary/Forecast';
                    if (($haveUV) && ($dayornight == 'Day')) {
                        $leftHead = 'Current UV Index';
                    }
                    $rightHead = 'Support this Site!';
                    if (($haveSolar) && ($dayornight == 'Day')) {
                        if ($useSISwitch) {
                            $rightHead = 'Current Solar Energy<sup>9</sup>';
                        } else {
                            $rightHead = 'Current Solar Energy';
                        }
                    }
                    $UVfcstDate = array_fill(0, 9, '');   // initialize the return arrays
                    $UVfcstUVI = array_fill(0, 9, 'n/a');
                    if (isset($UVscript)) { // load up the UV forecast script
                        @include_once($UVscript);
                    }
                    $UVptr = 0;  // index for which day to use
                ?>
            <tr>
                <td class="datahead" style="text-align:center"><?php echo $leftHead; ?></td>
                <td class="datahead" style="text-align:center"><?php echo $rightHead; ?></td>
            </tr>
            <tr>
                <td align="center">
        <table width="180" border="0" cellpadding="2" cellspacing="0">
<?php if (($haveUV) && ($dayornight == 'Day')) {  //  Have a UV sensor .. show realtime data & not night ?>
            <tr>
                <td align="center" width="35%" nowrap="nowrap" class="data2" style="text-align: center; font-size: 9px;">
                <span class="ajax" id="ajaxuv" style="font-size: 14px;"><?php echo $VPuv; ?></span>
                    <div style="padding-top: 3px;"><?php langtrans('Sunburn in'); ?></div>
                    <span class="ajax" id="ajaxuvburnrate"><?php echo $burntime; ?></span> <?php langtrans('Minutes'); ?>
                </td>
                <td width="30%" align="center">
                    <?php $VPuv2 = ''; // clear var ?>
                    <?php $VPuv2 = round($VPuv);
                    if ($VPuv2 >= 11) {
                        $VPuv2 = 11;
                    }
                    ?>
                    <span class="ajax" id="ajaxuvimg">
                    <img src="ajax-images/UV<?php echo $VPuv2; ?>.gif" height="65" width="34" alt="" title="Current UV rate" /></span>
                </td>
                <td width="35%" class="data2" style="text-align:center; font-size: 9px;">
                    <?php langtrans('Highest'); ?>:<br />
                    <?php echo $highuvtime; ?><br />
                    <span style="font-size: 14px;"><?php echo $highuv; ?></span>
                </td>
            </tr>
<?php } else {  //  don't have UV sensor .. show UV forecast instead  ?>
                    <?php
                    if ($haveUV) { // Show todays high if evening or yesterdays high if morning
                        $evening = 0;    // Need to adjust forecasts if it's before Midnight after dark
                    if ($time_hour > 12) {   // because the forecasts points at wrong day otherwise
                        $evening = 1;   // and forecast isn't for 'tomorrow'.
                    }
                    ?>
            <tr>
                <td align="center" colspan="3" nowrap="nowrap" class="data2" style="text-align: center; font-size: 10px;">
                    <?php if ($time_hour > 12) { // Display Summary if in the evening  ?>
                    <?php langtrans('High Today'); ?>:&nbsp;&nbsp;<span style="font-size: 9px;">
                    <?php echo $highuv; ?>&nbsp;@&nbsp;<?php echo $highuvtime; ?></span>
                    <?php $UVptr++; // increment counter if it's evening  ?>
                    <?php } else { ?>
                    <?php langtrans('High Yest'); ?>:&nbsp;&nbsp;<span style="font-size: 9px;">
                    <?php echo $highuvyest; ?>&nbsp;@&nbsp;<?php echo $highuvyesttime; ?></span>
                    <?php } ?>
                </td>
            </tr>
<?php } // End UV ?>
            <tr>
                <td align="center" width="35%" nowrap="nowrap" class="data2" style="text-align: center; font-size: 9px;">
                    <?php $VPuv2 = round($UVfcstUVI[$UVptr - $evening], 0);
                    if ($VPuv2 >= 11) {
                        $VPuv2 = 11;
                    }
                    ?>
                    <?php $UVshortdate = mktime(0, 0, 0, date("m"), date(d) + $UVptr, date("y")); ?>
                    <?php echo date("M-d", $UVshortdate) ?><br/>
                    <img src="ajax-images/UV<?php echo $VPuv2; ?>.gif" height="45" width="34" alt="" title="Predicted UV rate" /><br/>
                    <b><a href="<?php echo htmlspecialchars($UV_URL); ?>" title="<?php echo strip_tags($requiredNote); ?>"><?php echo $UVfcstUVI[$UVptr - $evening]; ?></a></b>
                    <?php $UVptr++; // increment counter ?>
                    <?php $VPuv2 = ''; // clear var  ?>
                </td>
                <td align="center" width="30%" class="data2" nowrap="nowrap" style="text-align: center; font-size: 9px;">
                    <?php
                    $VPuv2 = round($UVfcstUVI[$UVptr - $evening], 0);
                    if ($VPuv2 >= 11) {
                        $VPuv2 = 11;
                    }
                    ?>
                    <?php $UVshortdate = mktime(0, 0, 0, date("m"), date(d) + $UVptr, date("y")); ?>
                    <?php echo date("M-d", $UVshortdate) ?><br/>
                    <img src="ajax-images/UV<?php echo $VPuv2; ?>.gif" height="45" width="34" alt="" title="Predicted UV rate" /><br/>
                    <b><a href="<?php echo htmlspecialchars($UV_URL); ?>" title="<?php echo strip_tags($requiredNote); ?>"><?php echo $UVfcstUVI[$UVptr - $evening]; ?></a></b>
                    <?php $UVptr++; // increment counter ?>
                    <?php $VPuv2 = ''; // clear var  ?>
                </td>
                <td align="center" width="35%" class="data2" nowrap="nowrap" style="text-align: center; font-size: 9px;">
                    <?php
                    $VPuv2 = round($UVfcstUVI[$UVptr - $evening], 0);
                    if ($VPuv2 >= 11) {
                        $VPuv2 = 11;
                    }
                    ?>
                    <?php $UVshortdate = mktime(0, 0, 0, date("m"), date(d) + $UVptr, date("y")); ?>
                    <?php echo date("M-d", $UVshortdate) ?><br/>
                    <img src="ajax-images/UV<?php echo $VPuv2; ?>.gif" height="45" width="34" alt="" title="Predicted UV rate" /><br/>
                    <b><a href="<?php echo htmlspecialchars($UV_URL); ?>" title="<?php echo strip_tags($requiredNote); ?>"><?php echo $UVfcstUVI[$UVptr - $evening]; ?></a></b>
                    <?php $UVptr++; // increment counter ?>
                    <?php $VPuv2 = ''; // clear var  ?>
                </td>
            </tr>
                    <?php $UVptr++; // increment counter  ?>
<?php } // end $haveUV  ----------------------------------------  ?>
        </table>
                </td>
                <td align="center">
        <table width="180" border="0" cellpadding="2" cellspacing="0">
<?php if ($haveSolar) {  // Have a Solar Sensor  show current values  ?>
<?php if ($dayornight == 'Night') { ?>
            <tr>
                <td class="data1" style="text-align: center;" nowrap="nowrap" >
                    <?php if ($time_hour > 12) { // Display Summary if in the evening  ?>
                    <?php langtrans('High Today'); ?>:&nbsp;&nbsp;<br/><span style="font-size: 9px;">
                    <?php echo $highsolar . ' W/m'; ?><sup>2</sup>&nbsp;@&nbsp;<?php echo $highsolartime; ?></span>
                    <?php } else { ?>
                    <?php langtrans('High Yest'); ?>:&nbsp;&nbsp;<span style="font-size: 9px;">
                    <?php echo $highsolaryest; ?>&nbsp;@&nbsp;<?php echo $highsolaryesttime; ?></span>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;" class="data1"><?php langtrans('Sun Hours Today'); ?>:<br/><span style="font-size:9px">
                    <?php echo $sunshinehourstodateday; ?></span><br/>&nbsp;&nbsp;
                    <?php langtrans('This Month'); ?><span style="font-size:9px">:&nbsp;<?php echo $sunshinehourstodatemonth; ?> Hrs</span>
                </td>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 9px;" class="data2"><?php langtrans('Rise'); ?>:
                    <?php echo $sunrise; ?>&nbsp;&nbsp;<?php langtrans('Set'); ?>:&nbsp;<?php echo $sunset; ?>
                </td>
            </tr>
<?php } // End Of Solar Night ?>
<?php if ($dayornight == 'Day') { ?>
            <tr>
                <td align="center" width="35%" nowrap="nowrap" class="data2" style="text-align: center; font-size: 9px;">
                <span class="ajax" id="toggleSI">
                <script type="text/javascript">
                    function toggle_SI(){
                    if (document.getElementById("ajaxSIWm2").style.display == 'none') {
                    document.getElementById("ajaxSIWm2").style.display = 'inline'
                    document.getElementById("ajaxSIimgWm2").style.display = 'inline'
                    document.getElementById("ajaxSISolarp").style.display = 'none'
                    document.getElementById("ajaxSIimgSolarp").style.display = 'none'
                    } else {
                    document.getElementById("ajaxSIWm2").style.display = 'none'
                    document.getElementById("ajaxSIimgWm2").style.display = 'none'
                    document.getElementById("ajaxSISolarp").style.display = 'inline'
                    document.getElementById("ajaxSIimgSolarp").style.display = 'inline'
                    }
                    };
                </script></span>
                <span class="ajax" id="ajaxsolar" style="font-size: 14px;"><?php echo $VPsolar; ?></span> W/m<sup>2</sup><br/>
                <span class="ajax" id="ajaxsolarpct"><?php echo strip_units($currentsolarpercent); ?></span>%<br/>
<?php if ($useWM2SI) { ?>
                <span <?php if ($useSISwitch) { ?> onclick="javascript:toggle_SI()" ><?php } ?><span class="ajax" id="ajaxSIWm2" style=" <?php if ($useSISwitch) { ?> cursor:pointer; <?php } ?>display:inline; font-size: 8px;">W/m<sup>2</sup> <b>&rArr;</b> SI</span></span>
                <span <?php if ($useSISwitch) { ?> onclick="javascript:toggle_SI()" ><?php } ?><span class="ajax" id="ajaxSISolarp" style=" <?php if ($useSISwitch) { ?> cursor:pointer; <?php } ?> display:none; font-size: 8px;" >Solar % <b>&rArr;</b> SI</span></span>
                <?php } else { ?>
                <span <?php if ($useSISwitch) { ?> onclick="javascript:toggle_SI()"><?php } ?><span class="ajax" id="ajaxSIWm2" style=" <?php if ($useSISwitch) { ?> cursor:pointer; <?php } ?>display:none; font-size: 8px;<?php if ($useSISwitch) { ?> cursor:pointer><?php } ?>">W/m<sup>2</sup> <b>&rArr;</b> SI</span></span>
                <span <?php if ($useSISwitch) { ?> onclick="javascript:toggle_SI()"><?php } ?><span class="ajax" id="ajaxSISolarp" style=" <?php if ($useSISwitch) { ?> cursor:pointer; <?php } ?> display:inline; font-size: 8px;">Solar % <b>&rArr;</b> SI</span></span>
                <?php } ?>
                </td>
                <td width="30%" align="center">
<?php if ($useWM2SI) { ?>
                <span class="ajax" id="ajaxSIimgWm2" style="display:inline"
<?php if ($useSISwitch) { ?>
                onclick="javascript:toggle_SI()"> 
<?php } ?>
                <span class="ajax" <?php if ($useSISwitch) { ?> style="cursor:pointer" <?php } ?> id="ajaxsiimg"><img src="ajax-images/SI<?php echo round(($VPsolar * 10) / (round($recordhighsolar, 0)), 0); ?>.gif" height="65" width="34" alt="" title="Current Solar Index Using WM2"/></span></span>
                <span class="ajax" id="ajaxSIimgSolarp" style="display:none; cursor:pointer" 
<?php if ($useSISwitch) { ?>
                onclick="javascript:toggle_SI()">
<?php } ?>
                <span class="ajax" <?php if ($useSISwitch) { ?> style="cursor:pointer" <?php } ?> id="ajaxsiimg2"><img src="ajax-images/SI<?php echo floor(strip_units($currentsolarpercent) * 0.1); ?>.gif" height="65" width="34" alt="" title="Current Solar Index Using Solar%"/></span></span>
                <?php } else {
                ?> 
                <span class="ajax" id="ajaxSIimgWm2" style="display:none"
<?php if ($useSISwitch) { ?>
                onclick="javascript:toggle_SI()"> 
<?php } ?>
                <span class="ajax"id="ajaxsiimg"><img src="ajax-images/SI<?php echo round(($VPsolar * 10) / (round($recordhighsolar, 0)), 0); ?>.gif" height="65" width="34" alt="" title="Current Solar Index Using WM2"/></span></span>
                <span class="ajax" id="ajaxSIimgSolarp" style="display:inline; <?php if ($useSISwitch) { ?> cursor:pointer <?php } ?>" 
<?php if ($useSISwitch) { ?>
                onclick="javascript:toggle_SI()">
<?php } ?>
                <span class="ajax" <?php if ($useSISwitch) { ?> style="cursor:pointer" <?php } ?> id="ajaxsiimg2"><img src="ajax-images/SI<?php echo floor(strip_units($currentsolarpercent) * 0.1); ?>.gif" height="65" width="34" alt="" title="Current Solar Index Using Solar%"/></span></span>
<?php } ?>
                </td>
                <td width="35%" class="data2" style="text-align:center; font-size: 9px;">
                    <?php langtrans('High Today'); ?>:<br />
                    <?php echo $highsolartime; ?><br />
                    <span style="font-size: 11px;"><?php echo $highsolar; ?>&nbsp;</span>
                    <span style="font-size: 9px;">W/m<sup>2</sup></span>
                    <span style="font-size: 7px;">
                    <?php langtrans('Record'); ?>:<?php echo round($recordhighsolar, 0); ?>&nbsp;W/m<sup>2</sup></span>
                </td>
            </tr>
<?php } ?>
<?php } else { // don't have solar  show info instead  ?>
            <tr>
               <td style="text-align: center; " colspan="2">
                    <?php echo "Your donation helps support this site"; ?>
                    <?php
                    ///// NOTICE: Code below must be created on your PayPal Account and will look like this below.  The Value code has been
                    ///// changed to "88888888" for this example and will be unique to your account.  This code will not work unmodified!
                    ?>
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_s-xclick"/>
					<input type="hidden" name="hosted_button_id" value="7E3VFMFHU8K8G"/>
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"/>
					<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"/>
					</form>
<?php //// END OF PAYPAL CODE ?>
                </td>
            </tr>
<?php } // end $haveSolar  ?>
        </table>
                </td>
            </tr>
            <tr align="center">
<?php if (!$useSOIL) { // Display Almanac if LEAF and Soil not available ?>
                <td class="datahead"><?php langtrans('Almanac'); ?></td>
<?php } ?>
<?php if ($useSOIL) { // Display LEAF and Soil ?>
                <td class="datahead"><?php langtrans('Soil Conditions'); ?><sup>8</sup></td>
<?php } ?>
<?php if ((!$useET) && (!$useSNOW2) && (!$useDNT)) { // Display Blank Header if No ET Available and not SNOW2 ?>
                <td class="datahead">&nbsp;
                </td>
<?php } ?>
<?php if ($useET) {  // Display ET ?>
                <td class="datahead"><?php langtrans('Irrigation Index'); ?><sup>5</sup></td>
<?php } ?>
<?php if ($useSNOW2) {  // Display SNOW2 ?>
                <td class="datahead"><?php langtrans('Snow'); ?><sup>3</sup></td>
<?php } ?>
<?php if ($useDNT) {  // Display Donation Header at top because no ET or SNOW is being shown ?>
                <td class="datahead"><?php langtrans('Coming Soon'); ?>
                </td>
<?php } ?>
                <td class="datahead"><?php langtrans('Station All Time Records'); ?></td>
                <td class="datahead">
<?php if ($useCloudImg) { // Display the correct header for either Cloud Image or Outlook
                     echo " " . langtransstr('Coming Soon') . " ";
                } else {
                     echo " " . langtransstr('Outlook') . " ";
                }
?>          
                </td>
            </tr>
            <tr>
                <td valign="top" align="center">
        <table width="180" border="0" cellpadding="2" cellspacing="0">
<?php if (!$useSOIL) { // Display Almanac if Soil Conditions not available ?>
            <tr>
                <td class="data1"><?php langtrans('Current'); ?>:</td>
                <td class="data1" style="text-align: right;">
                    <?php echo $timeofdaygreeting; ?>
                </td>
            </tr>
            <tr>
                <td class="data1" nowrap="nowrap"><?php langtrans('YTD Avg Temp'); ?>:</td>
                <td class="data1" style="text-align: right;">
                    <span class="convTemp"><?php echo ($yeartodateavtemp) . $uomTemp; ?></span>
                </td>
            </tr>
            <tr>
                <td class="data1"><?php langtrans('Sunrise'); ?>:</td>
                <td class="data1" style="text-align: right;">
                    <?php echo $sunrise; ?>
                </td>
            </tr>
            <tr>
                <td class="data1"><?php langtrans('Sunset'); ?>:</td>
                <td class="data1" style="text-align: right;">
                    <?php echo $sunset; ?>
                </td>
            </tr>
            <tr>
                <td class="data1"><?php langtrans('Moonrise'); ?>:</td>
                <td class="data1" style="text-align: right;">
                    <?php echo $moonrise; ?>
                </td>
            </tr>
            <tr>
                <td class="data1"><?php langtrans('Moonset'); ?>:</td>
                <td class="data1" style="text-align: right;">
                    <?php echo $moonset; ?>
                </td>
            </tr>
            <tr>
                <td class="data1"><?php langtrans('Full Moon'); ?>:</td>
                <td class="data1" nowrap="nowrap" style="text-align: right;">
                    <?php echo rdate($RecDateF, (split_to_mon(1, ltrim($fullmoondate))), (split_to_day(1, ltrim($fullmoondate))), (split_to_year(ltrim($fullmoondate)))) ?>
                </td>
            </tr>
            <tr>
                <td class="data2"><?php langtrans('New Moon'); ?>:</td>
                <td class="data2" nowrap="nowrap" style="text-align: right;">
                    <?php $nnmlen =  strlen($nextnewmoon) - 9; ?>
                    <?php echo substr($nextnewmoon, 0 ,9); ?><br />
                    <?php echo rdate($RecDateF, (split_to_mon(1, substr($nextnewmoon ,10 ,$nnmlen))), (split_to_day(1, substr($nextnewmoon ,10 ,$nnmlen))), (split_to_year(substr($nextnewmoon ,9 ,$nnmlen)))) ?>
                </td>
            </tr>
<?php } // End Almanac  ?>
<?php if ($useSOIL) { // Display Soil Conditions ?>
            <tr>
                <td class="data1" nowrap="nowrap"><span class="convSoil"><?php echo $SOILlvl1 . " " . $uomSoil; ?>:</span></td>
<?php if ($hideSM1) {  // Hide soil moisture ?>    
                <td class="data1" nowrap="nowrap" style="text-align: right;">
                <span class="convTemp"><?php echo $soiltemp . ' ' . $uomTemp; ?></span>
<?php } // End Hide SM1 ?>
<?php if (!$hideSM1) {  // Don't Hide soil moisture ?>
                <td class="data1" nowrap="nowrap" style="text-align: right;">
                    <span style="color: blue"><span class="convMoist"><?php echo $VPsoilmoisture . $uomMoist; ?></span> | </span>
                    <span class="convTemp"><?php echo $soiltemp . $uomTemp; ?></span>
<?php } // End NOT Hide SM1 ?>                                  
                </td>
            </tr>
<?php if ($SOILcnt > 1) {  // Display next sensor if required ?>
            <tr>
                <td class="data1" nowrap="nowrap"><span class="convSoil"><?php echo $SOILlvl2 . " " . $uomSoil; ?>:</span></td>
<?php if ($hideSM2) {  // Hide soil moisture ?>    
                <td style="text-align: right;" class="data1" nowrap="nowrap">
                    <span class="convTemp"><?php echo $VPsoiltemp2 . $uomTemp; ?></span>
<?php } // End Hide SM2 ?>
<?php if (!$hideSM2) {  // Don't Hide soil moisture ?>
                <td class="data1" nowrap="nowrap" style="text-align: right;">
                    <span style="color: blue;"><span class="convMoist"><?php echo $VPsoilmoisture2 . $uomMoist; ?></span> | </span>
                    <span class="convTemp"><?php echo $VPsoiltemp2 . $uomTemp; ?></span>
<?php } // End NOT Hide SM2 ?>                                  
                </td>
            </tr>
<?php } ?>
<?php if ($SOILcnt > 2) {  // Display next sensor if required ?>
            <tr>
                <td class="data1" nowrap="nowrap"><span class="convSoil"><?php echo $SOILlvl3 . " " . $uomSoil; ?>:</span></td>
<?php if ($hideSM3) {  // Hide soil moisture ?>    
                <td style="text-align: right;" class="data1" nowrap="nowrap">
                    <span class="convTemp"><?php echo $VPsoiltemp3 . $uomTemp; ?></span>
<?php } // End Hide SM3 ?>
<?php if (!$hideSM3) {  // Don't Hide soil moisture ?>
                <td class="data1" nowrap="nowrap" style="text-align: right;">
                    <span style="color: blue;"><span class="convMoist"><?php echo $VPsoilmoisture3 . $uomMoist; ?></span> | </span>
                    <span class="convTemp"><?php echo $VPsoiltemp3 . $uomTemp; ?></span>
<?php } //End NOT Hide SM3 ?>                                  
                </td>
            </tr>
<?php } ?>
<?php if ($SOILcnt > 3) {  // Display next sensor if required ?>
            <tr>
                <td class="data1" nowrap="nowrap"><span class="convSoil"><?php echo $SOILlvl4 . " " . $uomSoil; ?>:</span></td>
<?php if ($hideSM4) {  // Hide soil moisture ?>    
                <td style="text-align: right;" class="data1" nowrap="nowrap">
                    <span class="convTemp"><?php echo $VPsoiltemp4 . $uomTemp; ?></span>
<?php } // End Hide SM4 ?>
<?php if (!$hideSM4) {  // Don't Hide soil moisture ?>
                <td class="data1" nowrap="nowrap" style="text-align: right;">
                    <span style="color: blue;"><span class="convMoist"><?php echo $VPsoilmoisture4 . $uomMoist; ?></span> | </span>
                    <span class="convTemp"><?php echo $VPsoiltemp4 . $uomTemp; ?></span>
<?php } // End NOT Hide SM4 ?>                                  
                </td>
            </tr>
<?php } ?>  
            <tr>
                <td class="data2" colspan="2" style="text-align: center;">
                    <br /><?php echo langtrans('Growing conditions'); ?><br />
                    <?php echo langtrans('at the depth of'); ?>:&nbsp;
                    <span class="convSoil"><?php echo $SOILlvl1  . " " . $uomSoil; ?>:</span><br/>
                    <img src="ajax-images/sm<?php echo get_SoilMoisture($VPsoilmoisture); ?>.gif" alt="" title="Soil Moisture Zone" />
                </td>
            </tr>
            <tr>
                <td class="data2" colspan="2" style="text-align: center;">
                    <img src="ajax-images/GI<?php echo substr($uomTemp, -1) . get_SoilTemperature($soiltemp); ?>.gif" alt="" title="Soil Temperature Zone" /><br />
                    <span style="font-size: 11px;"><br /><?php echo langtrans('Soil moisture range is from'); ?><br />
                    <?php echo langtrans('0-Wet to 200-Dry/Frozen'); ?></span>
                </td>
            </tr>
<?php } ?>
        </table>
                </td>
                <td align="center" valign="top">
        <table width="180" border="0" cellpadding="2" cellspacing="0">
<?php if (($useET) && ($haveSolar)) {  //  Show ET status data ?>
            <tr>
                <td style="text-align: center; color:#0066CC" colspan="2"><?php langtrans('Updated at Midnight'); ?></td></tr>
            <tr>
                <td class="data1"><?php langtrans('Current ET'); ?>:</td>
                <td style="text-align: right;" class="data1" nowrap="nowrap">
                    <span class="convRain"><?php printf("%8.2f", $currentwdet); echo $uomRain; ?></span>
                </td>
            </tr>
            <tr>
                <td class="data1"><?php langtrans('7-Days Rain'); ?>:</td>
                <td style="text-align: right;" class="data1" nowrap="nowrap">
                    <span class="convRain"><?php printf("%8.2f", $raincurrentweek); echo $uomRain; ?></span>
                </td>
            </tr>
            <tr>
                <td class="data1"><?php langtrans('7-Days ET'); ?>:</td>
                <td style="text-align: right; " class="data1" nowrap="nowrap">
                    <span class="convRain"><?php printf("%8.2f", $etcurrentweek); echo $uomRain; ?></span>
                </td>
            </tr>
            <tr>
                <td style="text-align: Left;" class="data1">
                    <?php echo "7-Day:" ?>
                    <?php $d = $raincurrentweek - $etcurrentweek; ?>
                </td>		
                <td style="text-align: right; " class="data1" nowrap="nowrap">
                    <?php $sevendayet = gen_difference($raincurrentweek, $etcurrentweek, $uomRain, ' %s' . $uomRain . ' water in excess of what evaporated in last 7 days.', ' %s' . $uomRain . ' water in deficit of what evaporated in last 7 days.'); ?>
                    <?php echo gen_difference($raincurrentweek, $etcurrentweek, '', ' %s' . $uomRain . ' water in excess of what evaporated in last 7 days.', ' %s' . $uomRain . ' water in deficit of what evaporated in last 7 days.'); ?>
                                <span class="convRain"><?php echo abs($sevendayet) . $uomRain; ?> </span>
                </td>
            </tr>
            <tr>
                <td style="text-align: Left;" class="data1">
                    <?php echo "$monthname:"; ?>
                </td>		
                <td style="text-align: right;" class="data1" nowrap="nowrap">
                    <?php echo gen_difference($monthrn, $VPetmonth, '', ' %s' . $uomRain . ' water in excess of what evaporated this month.', ' %s' . $uomRain . ' water in deficit of what evaporated this month.') ?>
                    <span class="convRain"><?php echo abs($monthrn - $VPetmonth) . $uomRain ?></span>
                </td>
            </tr>
<?php } // end $useET   ?>
<?php if (($useDONATE) && (!$useSNOW2)) {  //  Show Donation Section ?>
<?php if (!$useDNT) { //Don't Display Header if already using header at top of column ?>
            <tr>
                <td style="text-align: center;" class="datahead" colspan="2">Space Weather</td>
				
            </tr>
<?php } //end of conditional header  ?>
            <tr>
               <td class="data1" align="center" style="text-align: center; font-size: 11px;" >
				</td> 
            </tr>
			
			
            </tr>
<?php } // end $useDONATE   ?>
<?php if ($useSNOW2) {  //  Show Snow if true or in Snow Season ?>
            <tr>
                <td class="data1"><?php langtrans('Today'); ?>: </td>
                <td class="data1" nowrap="nowrap" style="text-align: right;">
                    <span class="ajax" id="ajaxsnowToday2">
                    <span class="convSnow"><?php printf("%8.2f", $snowtoday); echo $uomSnow; ?></span></span>
                </td>
            </tr>
            <tr>
                <td class="data1"><?php langtrans('Yesterday'); ?>:</td>
                <td class="data1" nowrap="nowrap" style="text-align: right;">
                    <span class="convSnow"><?php printf("%8.2f", $snowyesterday); echo $uomSnow; ?></span>
                </td>
            </tr>
            <tr>
                <td class="data1" nowrap="nowrap"><?php echo substr($monthname, 0 ,3) . " Snow:" ?> </td>
                <td class="data1" nowrap="nowrap" style="text-align: right;">
                    <?php echo gen_difference(strip_units($snowmonth), $avgmonthsnow1, '', 'Over %s' . $uomSnow . ' for the month', 'Under %s' . $uomSnow . ' for the month'); ?>
                    <span class="ajax" id="ajaxsnowMonth">
                    <span class="convSnow"><?php printf("%8.2f", $snowmonth); echo $uomSnow; ?></span></span>
                </td>
            </tr>
            <tr>
                <td class="data1" nowrap="nowrap"><?php echo substr($monthname, 0 ,3) . " Avg:"; ?></td>
                <td class="data1" nowrap="nowrap" style="text-align: right;">
                    <span class="convSnow"><?php printf("%8.2f", $avgmonthsnow1); echo $uomSnow; ?></span>
                </td>
            </tr>
            <tr>
                <td class="data1" nowrap="nowrap"><?php langtrans('Diff from Avg'); ?>:</td>
                <td class="data1" nowrap="nowrap" style="text-align: right;">
                    <span class="convSnow"><?php printf("%8.2f", strip_units($sndiff)); echo $uomSnow; ?></span>
                </td>
            </tr>
            <tr>
                <td class="data1"><?php langtrans('Season Total'); ?>:<sup>2</sup></td>
                <td class="data1" nowrap="nowrap" style="text-align: right;">
                    <span class="ajax" id="ajaxsnowSeason">
                    <span class="convSnow"><?php printf("%8.2f", $snowseason); echo $uomSnow; ?></span></span>
                </td>
            </tr>
            <tr>
                <td class="data1"><?php langtrans('Snow Depth'); ?>: </td>
                <td class="data1" nowrap="nowrap" style="text-align: right;">
                    <span class="ajax" id="ajaxsnowDepth">
                    <span class="convSnow"><?php printf("%8.2f", $snownow); echo $uomSnow; ?></span></span>
                </td>
            </tr>
            <tr>
                <td class="data2" colspan="2" style="text-align: center;">
                    <?php echo "$snowdaysthismonth snow day"; $t = ($snowdaysthismonth > 1) ? 's' : ''; print "$t in " . $monthname; ?>
                </td>
            </tr>
            <tr>
                <td class="data2" colspan="2" style="text-align: center;">
                    <?php echo "$snowdaysthisyear snow day"; $t = ($snowdaysthisyear > 1) ? 's' : ''; print "$t this season."; ?><sup>2</sup>
                </td>
            </tr>
<?php } // end $useSNOW2  ----------------------------------------  ?>
<?php if ($useCloudImg == '0') {  // Displaying Cloud Height Graphic from Bashewa Weather ?>
            <tr>
                <td class="data2" align="left"><?php langtrans('Cloud Height'); ?>:</td>
                <td class="data2" style="text-align: right;">
                    <span class="ajax" id="ajaxcloudheight"><?php echo $cloudheightfeet . " ft"; ?></span>
                </td>
            </tr>  
<?php } ?>
        </table>
                </td/>
                <td align="center" valign="middle">
        <table width="180" border="0" cellpadding="2" cellspacing="0">
            <tr>
                <td class="data1" style="text-align: center; color:#0066CC"><strong><?php langtrans('HIGHS'); ?>:</strong></td>
                <td class="data1" style="text-align: center; color:#0066CC"><strong><?php langtrans('LOWS'); ?>:</strong></td>
            </tr>
            <tr>
                <td class="data1" style="text-align: center; font-size: 11px;">
                    <span class="convTemp"><?php echo strip_units($recordhightemp) . $uomTemp ?></span><br />
                    <?php echo rdate($RecDateF, $recordhightempmonth, $recordhightempday, $recordhightempyear); ?>
                </td>
                <td class="data1" style="text-align: center; font-size: 11px;">
                    <span class="convTemp"><?php echo strip_units($recordlowtemp) . $uomTemp ?></span><br />
                    <?php echo rdate($RecDateF, $recordlowtempmonth, $recordlowtempday, $recordlowtempyear); ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;" class="datahead" colspan="2"><?php langtrans('Daytime Records'); ?></td>
            </tr>
            <tr>
                <td class="data2" style="text-align: center; font-size: 11px;">
                    &nbsp;<?php $_array = fix_record2($RecDateF, $warmestdayonrecord); ?>
                    <span class="convTemp"><?php echo " " . $_array[0] . $uomTemp ?> </span>
                    <?php echo "<br/>" . $_array[1]; ?>
                </td>
                <td class="data2" style="text-align: center; font-size: 11px;">
                    &nbsp;<?php $_array = fix_record2($RecDateF, $coldestdayonrecord); ?><span class="convTemp">
                    <?php echo " " . $_array[0] . $uomTemp ?> </span><?php echo "<br/>" . $_array[1]; ?>
                </td>  
            </tr>
            <tr>
                <td style="text-align: center;" class="datahead" colspan="2"><?php langtrans('Nitetime Records'); ?></td>
            </tr>
            <tr>
                <td class="data1" style="text-align: center; font-size: 11px;">
                    &nbsp;<?php $_array = fix_record2($RecDateF, $warmestnightonrecord); ?><span class="convTemp">
                    <?php echo " " . $_array[0] . $uomTemp ?> </span><?php echo "<br/>" . $_array[1]; ?>
                </td>
                <td class="data1" style="text-align: center; font-size: 11px;">
                    &nbsp;<?php $_array = fix_record2($RecDateF, $coldestnightonrecord); ?><span class="convTemp">
                    <?php echo " " . $_array[0] . $uomTemp ?> </span><?php echo "<br/>" . $_array[1]; ?>
                </td>
            </tr>
            <tr>
                <td class="data2" style="text-align: center;  font-size: 6pt;" colspan="2" >
                    <?php echo "ICN:" . $iconnumber . " | " . "S3C2:" . $S3C2; ?>
                    <span class="ajax" id="ajaxvers"><?php echo $ajaxvers; ?></span><br />
                    <?php if (strstr($vpissstatus, "Low")) {
                        $lowvolt = "<span style =\"color:red; font-weight:bold\">" . $vpissstatus . "</span>";
                        echo "ISS:" . $lowvolt . " | ";
                        } else
                        echo "ISS:" . $vpissstatus . " | ";
                        if ($useVPst) {  //  Show Vantage Pro status data
                        $lowvolt = "<span style =\"color:red; font-weight:bold\">" . $vpconsolebattery . "</span>";
                        if ($vpconsolebattery < 3.6)
                        echo "CON:" . $lowvolt . " | ";
                        else
                        echo "CON:" . $vpconsolebattery . " | ";
                        echo "RCP:" . $vpreception2;
                        } // end $useVPst
                    ?>
                </td>
            </tr>
        </table>
                </td>

<?php if ($useCloudImg == 0) { // Display Outlook Forecast ?>
                <td class="data1" align="center" style="font-size: 9px; border:none;text-align: center">
                    <?php print $forecasticons[1]; ?>
                </td>
<?php } // End Display Outlook Forecast ?>
            </tr>
<?php if ($useRefresh2 == true) { ?>
            <tr align="center">
                <td>
                    <div onclick="javascript:ajax_changeUnits();" style="cursor: pointer;" title="Toggle units-of-measure &amp; restart"><b>
                    <span id="uomM2" style="color: gray;"><?php langtrans('METRIC'); ?></span> |
                    <span id="uomE2" style="color: blue;"><?php langtrans('IMPERIAL'); ?></span></b>
                    </div>
                </td>
                <td class="data2" colspan="3" style="font-size: 100%; text-align: center">
                    <span class="ajax" id="ajaxindicator2"><?php langtrans('Updated'); ?>:</span>
                    <span class="ajax" id="ajaxdate3">
                    <?php echo date($SITE['dateOnlyFormat'] . " @ " . $SITE['timeOnlyFormat'], $updTime); ?><!-- Uses Settings.php values --></span>
                    <span class="ajax" id="ajaxntimess2">&nbsp;</span>
                    <script type="text/javascript"><!--
                    document.write(' <small><i>(<span id="ajaxcounter2">0</span>&nbsp;<?php langtrans('sec ago'); ?>)</i></small>');
                    --></script>
                </td>
            </tr>
<?php } // End Use Refresh 2 ?>
        </table>
        <table width="100%" border="0" cellpadding="2" cellspacing="1" >
            <tr>
                <td class="data1" style="font-size: 80%; text-align: center">
                <sup>1</sup> <?php echo $Foot1; ?>. &nbsp;
<?php if ($useSNOW) {  //  Show Snow ?>
                    <sup>2</sup> <?php echo $Foot2; ?>. &nbsp;
                    <sup>3</sup> <?php echo $Foot3; ?>. &nbsp;
<?php } ?>
<?php if ($useFWI) {  //  Show FWI ?>
                    <sup>4</sup> <?php echo $Foot4; ?>. &nbsp;
<?php } ?>
<?php if ((($useET) and ($haveSolar))) {  //  Show ET status data    ?>
                    <sup>5</sup> <?php echo $Foot5; ?>. &nbsp;
<?php } ?>
<?php if ($useAQI) {  //  Show Air Quality Index footnote ?>
                    <sup>6</sup> <?php echo $Foot6; ?>. &nbsp;
<?php } ?>
<?php if ($useLEAF) {  //  Show LEAF Weatness footnote ?>
                    <sup>7</sup> <?php echo $Foot7; ?>. &nbsp;
<?php } ?>
<?php if ($useSOIL) {  //  Show Soil sensor footnote ?>
                    <sup>8</sup> <?php echo $Foot8; ?>. &nbsp;
<?php } ?>
<?php if (($haveSolar) and ($dayornight == 'Day') and ($useSISwitch)) { ?>
                    <sup>9</sup> <?php echo $Foot9; ?>. &nbsp;
<?php } ?>
<?php if ($use_historical) {  // Use Historical Month-to-Date Average ?>
                    <sup>10</sup> <?php echo $Foot10; ?>. &nbsp;
                    <sup>11</sup> <?php echo $Foot11; ?>.
<?php } ?>
                </td>
            </tr>
        </table>
        <table width="100%" border="1" cellpadding="2" cellspacing="1">
            <tr>
                <td style="text-align: left" class="datahead">&nbsp;<? print $fcstorg; ?> <?php langtrans('Weather Forecast'); ?>&nbsp; - &nbsp;
                    <?php langtrans('Outlook'); ?>&nbsp;<?php echo $forecasttitles[0]; ?> &amp; <?php echo $forecasttitles[1]; ?></td>
            </tr>
            <tr>
                <td class="data1" align="center" valign="middle">
        <table width="100%" border="0" cellpadding="2" cellspacing="3">
            <tr align="center">
                <td  style="font-size: 9px;border: none;text-align: center" valign="middle" align="center"><strong>
                    <?php echo $forecasticons[0] . "<br />" . $forecasttemp[0]; ?></strong>
                </td>
                <td style="width: 240px; text-align: left" >
                    <?php print "<b>$fcstorg " . langtransstr ('forecast') . ":</b> "  . $forecasttext[0] . "<br />\n";
                    if ($fcstorg <> 'WXSIM' and isset($WXSIMtext[0])) {
		    print '              <span style="color:#33CC00;"><b>WXSIM ' . langtransstr('forecast') . ':</b> ' . $WXSIMtext[0] . "<br /></span>\n";
                    }
                    if (($useVPfc) && ($vpforecasttext <> '')) {
                    print '		   	   <b>' . langtransstr('Local station forecast') . ':</b> <span style="color: #4863A0; font-size: 10px">' . ucfirst($vpforecasttext) . "</span>";
                    }
                    ?>
                </td>
                <td style="font-size: 9px; border:none; text-align: center" valign="middle" align="center"><strong>
                    <?php echo $forecasticons[1] . "<br />" . $forecasttemp[1]; ?></strong>
                </td>
                <td style="width: 240px; text-align: left" >
                    <?php print "<b>$fcstorg " . langtransstr('forecast') . ":</b> " . $forecasttext[1];
                    if ($fcstorg <> 'WXSIM' and isset($WXSIMtext[1])) { 
		    print '              <br/><span style="color:#33CC00;"><b>WXSIM ' . langtransstr('forecast') . ':</b> ' . $WXSIMtext[1] . "<br/></span>\n";
                    }
                    ?>            
                </td>
            </tr>
        </table>
                </td>
            </tr>
<?php if ($useEXTf) {  //  Show Short Term Extended Forcast Data ?>
            <tr>
                <td>
        <table width="100%" border="1" cellpadding="2" cellspacing="1">
            <tr>
                <td style="text-align: left" class="datahead">&nbsp;<? print $fcstorg; ?> <?php langtrans('Short Term Weather Forecast'); ?></td>
            </tr>
        </table>
                </td>
            </tr>
            <tr>
                <td class="data1">
        <table style="text-align:center;" width="100%" border="1" cellpadding="2" cellspacing="2">
            <tr>
                <td style="font-size: 10px;">
                    <?php echo $forecasticons[2]; ?><br/>
                    <?php echo $forecasttemp[2]; ?>
                </td>
                <td style="font-size: 10px;">
                    <?php echo $forecasticons[3]; ?><br/>
                    <?php echo $forecasttemp[3]; ?>
                </td>
                <td style="font-size: 10px;">
                    <?php echo $forecasticons[4]; ?><br/>
                    <?php echo $forecasttemp[4]; ?>
                </td>
                <td style="font-size: 10px;">
                    <?php echo $forecasticons[5]; ?><br/>
                    <?php echo $forecasttemp[5]; ?>
                </td>
                <td style="font-size: 10px;">
                    <?php echo $forecasticons[6]; ?><br/>
                    <?php echo $forecasttemp[6]; ?>
                </td>
                <td style="font-size: 10px;">
                    <?php echo $forecasticons[7]; ?><br/>
                    <?php echo $forecasttemp[7]; ?>
                </td>
                <td style="font-size: 10px;">
                    <?php echo $forecasticons[8]; ?><br/>
                    <?php echo $forecasttemp[8]; ?>
                </td>
            </tr>   
        </table>
                </td>
            </tr>
<?php } // end $useEXTf  ?>
            <tr align="center">
                <td class="data1" style="text-align: center; font-size: 85%; line-height:120%" colspan="6" >
                    <?php if($condIconType <> '.jpg') { 
                    echo langtransstr('Animated icons courtesy of')." <a href=\"http://www.meteotreviglio.com/\" target=\"_blank\" title=\"Opens in New Tab\">www.meteotreviglio.com</a>";
                    ?><br/>
                    <?php } if ($use_historical) {
                        echo langtransstr('Precipitation average-to-date script courtesy of')." <a href=\"http://www.weather-watch.com/smf/index.php/topic,45144.0.html\" target=\"_blank\" title=\"Opens in New Tab\">Murry Conarroe</a><br/>";
                    }?>            
                    Version 6.92 - 09-Feb-2014 - Script Mods by: <a href="http://www.burnsvilleweatherlive.com" target="_blank" title="Opens in New Tab">BurnsvilleWeatherLIVE.com</a>
                    <?php if ($showMob) { ?>
                        <br /><br /><input type="button" value="Show Mobile Version" onclick="location.href='<?php echo $mobilesite; ?>';"/><br />
                    <?php } // End Show Mobile Button ?>
                </td>
            </tr>
        </table>
</div>
<!-- end of ajax-dashboard.php -->

<?php

//=========================================================================
//
// Functions
//
//=========================================================================

// Record Date Format - Added for Version 6.90 Burnsville Weather Live
function rdate($RecDateF, $Rmonth, $Rday, $Ryear) {
    switch (ltrim($RecDateF)) {
        case 0: // $recDateF not found or set see config file.
            $Fdate = "00-Err-0000";
            break;
        case 1: // Dec-25-2013
            $Fdate = (mon_three($Rmonth) . "-" . $Rday . "-" . $Ryear);
            break;
        case 2: // 15-Dec-2013
            $Fdate = ($Rday . "-" .  mon_three($Rmonth) . "-" . $Ryear);
            break;
        case 3: // 12/25/2013
            $Fdate = ($Rday . "/" .  $Rmonth . "/" . $Ryear);
            break;
        case 4: // 25/12/2013
            $Fdate = ($Rmonth . "/" .  $Rday . "/" . $Ryear);
            break;
        case 5: // Dec-25
            $Fdate = (mon_three($Rmonth) . "-" . $Rday);
            break;
        case 6: // 15-Dec
            $Fdate = ($Rday . "-" .  mon_three($Rmonth));
            break;
        case 7: // 12-25-2013
            $Fdate = ($Rmonth . "-" .  $Rday . "-" . $Ryear);
            break;
        case 8: // 25-12-2013
            $Fdate = ($Rday . "-" .  $Rmonth . "-" . $Ryear);
            break;
    }
    Return $Fdate;
}

function mon_three($Tmonth) { // Added for version 6.90 Burnsville Weather Live
    switch ($Tmonth) {
        case 1:
            $MMM = langtransstr("Jan");
            break;
        case 2:
            $MMM = langtransstr("Feb");
            break;
        case 3:
            $MMM = langtransstr("Mar");
            break;
        case 4:
            $MMM = langtransstr("Apr");
            break;
        case 5:
            $MMM = langtransstr("May");
            break;
        case 6:
            $MMM = langtransstr("Jun");
            break;
        case 7:
            $MMM = langtransstr("Jul");
            break;
        case 8:
            $MMM = langtransstr("Aug");
            break;
        case 9:
            $MMM = langtransstr("Sep");
            break;
        case 10:
            $MMM = langtransstr("Oct");
            break;
        case 11:
            $MMM = langtransstr("Nov");
            break;
        case 12:
            $MMM = langtransstr("Dec");
            break;
    }
    return $MMM;
}

function mon_no($Tmonth) { // Added for version 6.90 Burnsville Weather Live
//English, Danish, Dutch, Finnish, French, German, German (Swiss), Italian, Norwegian, Polish, Spanish, Swedish
//Strings for each month MUST start with x and be three characters long.  Add to the end of the file.  I did not
//repeat the month abbreviation if it was the same as another language.  Case matters!!  
    $monthJan = "xJanjantamjargenstyene";
    $monthFeb = "xFebfebhelfévlut";
    $monthMar = "xMarmarmaaMärMer";        
    $monthApr = "xApraprhuhavrkwiabr";
    $monthMay = "xMaymajmeitoumaiMaimagmaj";
    $monthJun = "xJunjunkesjuigiucze";
    $monthJul = "xJuljulheijuiluglip";
    $monthAug = "xAugaugeloaoûagosieago";
    $monthSep = "xSepsepsyysetwrz";
    $monthOct = "xOctoktlokOktottpa?d";
    $monthNov = "xNovnovmarlis";
    $monthDec = "xDecdecjoudécDezdicdesgru";
    $MMM = 0;
    //print "[" . $Tmonth . "]";
    //$Tmonth = trim($Tmonth);
    $Tmonth = substr($Tmonth, 0, 3);
    //print "[" . $Tmonth . "]";
    switch (FALSE) {
        case (strpos($monthJan, $Tmonth, 1) === False):
            $MMM = 1;
            break;    
        case (strpos($monthFeb, $Tmonth, 1) === False):
            $MMM = 2;
            break;    
        case (strpos($monthMar, $Tmonth, 1) === False):
            $MMM = 3;
            break;    
        case (strpos($monthApr, $Tmonth, 1) === False):
            $MMM = 4;
            break;    
        case (strpos($monthMay, $Tmonth, 1) === False):
            $MMM = 5;
            break;    
        case (strpos($monthJun, $Tmonth, 1) === False):
            $MMM = 6;
            break;    
        case (strpos($monthJul, $Tmonth, 1) === False):
            $MMM = 7;
            break;    
        case (strpos($monthAug, $Tmonth, 1) === False):
            $MMM = 8;
            break;    
        case (strpos($monthSep, $Tmonth, 1) === False):
            $MMM = 9;
            break;    
        case (strpos($monthOct, $Tmonth, 1) === False):
            $MMM = 10;
            break;    
        case (strpos($monthNov, $Tmonth, 1) === False):
            $MMM = 11;
            break;    
        case (strpos($monthDec, $Tmonth, 1) === False):
            $MMM = 12;
            break;    
    }
    return $MMM;
}

// Pull month only, as a number, out of strings with full date in them (NEW version 6.90)
// if $dtyp = 1 then date is in 25 December 2013 format
// if $dtyp = 2 then date is in Dec 25 2013 format
function split_to_mon($dtyp, $datetofix) {
    $datetofix = trim($datetofix);
    $d = explode(' ', $datetofix);
    if ($dtyp == 1) {
        $fmonth = mon_no(trim($d[1]));
    } else {
        $fmonth = mon_no(trim($d[0]));
    }
    //print "$d[0], $d[1], $d[2]";
    return $fmonth;
}

function split_hash_date($datetofix) { // Added for version 6.90 Burnsville Weather Live
    global $datefmt;
    $dmy = 2;
    if ($datefmt == 'd/m/Y') { $dmy = 1; }
    $datetofix = trim($datetofix);
    $d = explode('/', $datetofix);
    if ($dmy == 1) {
        $fdate[0] = $d[0];
        $fdate[1] = $d[1];
    } else {
        $fdate[0] = $d[1];
        $fdate[1] = $d[0];
    }
    $fdate[2] = $d[2];
    //print "$fdate[0], $fdate[1], $fdate[2]";
    return array($fdate[0], $fdate[1], $fdate[2]);
}


// Pull day only out of strings with full date in them (NEW version 6.90)
// if $dtyp = 1 then date is in 25 December 2013 format
// if $dtyp = 2 then date is i, Dec 25 2013 format
function split_to_day($dtyp, $datetofix) {
    $d = explode(' ', ltrim($datetofix));
    if ($dtyp == 1) {
        $fday = $d[0];
    } else {
        $fday = $d[1];
    }
    return $fday;
}

// Pull year only out of strings with full date in them (NEW version 6.90)
function split_to_year($datetofix) {
    $d = explode(' ', ltrim($datetofix));
    $fyear = $d[2];
    return $fyear;
}

// Assign unique image id to reliably clear browser cache
function getUniqueImageURL($image_url){
	$timestamp = time();
	if(strpos($image_url, '?')){
		$image_url = str_replace('?', "?$timestamp&", $image_url);
	}
	else{
		$image_url .= "?$timestamp";
	}
	return $image_url;
}

// Detect Internet Explorer
function ae_detect_ie() {
    if (isset($_SERVER['HTTP_USER_AGENT']) &&
            (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        return true;
    else
        return false;
}


function fix_record2($RecDateF, $rtfdata) {      // NEW in 6.90 BurnsvilleWeather Live Originally by www.lokaltvader.se & modified by www.gwwilkins.org
    global $datefmt;
    $dmy = 2;
    if ($datefmt == 'd/m/y') { $dmy = 1; }
    //print ("[" . $datefmt . "]");
    $tempinfo = explode(":", $rtfdata);
    $rtemp = substr($rtfdata, 0, (strpos($rtfdata, ".") + 2));
    $rtfdate = rdate($RecDateF, (split_to_mon($dmy, $tempinfo[1])), (split_to_day($dmy, $tempinfo[1])), (split_to_year($tempinfo[1])));
    return array($rtemp, $rtfdate);
}

//  generate an up/down arrow to show differences
function gen_difference($nowTemp, $yesterTemp, $Legend, $textUP, $textDN, $id = "") {
    global $imagesDir;
    $diff = round(strip_units($nowTemp) - strip_units($yesterTemp), 3);
    $absDiff = abs($diff);
    $diffStr = sprintf("%01.0f", $diff);
    $absDiffStr = sprintf("%01.0f", $absDiff);
    if ($diff == 0) { // no change
        $msg = "No Change";
        $image = "<img src=\"${imagesDir}steady.gif\" alt=\"$msg\" title=\"$msg\" width=\"8\" height=\"8\" style=\"border: 0; margin: 1px 3px;\" />";
    } elseif ($diff > 0) { // today is greater
        $msg = sprintf($textUP, $absDiff);
        $image = "<img src=\"${imagesDir}rising.gif\" alt=\"$msg\" title=\"$msg\" width=\"8\" height=\"8\" style=\"border: 0; margin: 1px 3px;\" />";
    } else { // today is lesser
        $msg = sprintf($textDN, $absDiff);
        $image = "<img src=\"${imagesDir}falling.gif\" alt=\"$msg\" title=\"$msg\" width=\"8\" height=\"8\" style=\"border: 0; margin: 1px 3px;\" />";
    }
    $idS = $idE = "";
    if (!empty($id)) {
        $idS = '<span class="ajax" id="' . $id . '">';
        $idE = '</span>';
    }
    if ($Legend) {
        return ($idS . $diff . $Legend . $idE . $image);
    } else {
        return $image;
    }
}

//=========================================================================
//  decode UV to word+color for display

function get_UVrange($inUV) {
// figure out a text value and color for UV exposure text
//  0 to 2  Low
//  3 to 5 	Moderate
//  6 to 7 	High
//  8 to 10 Very High
//  11+ 	Extreme
    if (strpos($inUV, ',') !== false) {
        $uv = preg_replace('|,|', '.', $inUV);
    } else {
        $uv = $inUV;
    }
    switch (TRUE) {
        case ($uv == 0):
            $uv = langtransstr('None');
            break;
        case (($uv > 0) and ($uv < 3)):
            $uv = '<span style="border: solid 1px; color: black; background-color: #A4CE6a;">&nbsp;' . langtransstr('Low') . '&nbsp;</span>';
            break;
        case (($uv >= 3) and ($uv < 6)):
            $uv = '<span style="border: solid 1px; color: black; background-color: #FBEE09;">&nbsp;' . langtransstr('Medium') . '&nbsp;</span>';
            break;
        case (($uv >= 6 ) and ($uv < 8)):
            $uv = '<span style="border: solid 1px; color: black; background-color: #FD9125;">&nbsp;' . langtransstr('High') . '&nbsp;</span>';
            break;
        case (($uv >= 8 ) and ($uv < 11)):
            $uv = '<span style="border: solid 1px; color: #FFFFFF; background-color: #F63F37;">&nbsp;' . langtransstr('Very&nbsp;High') . '&nbsp;</span>';
            break;
        case (($uv >= 11) ):
            $uv = '<span style="border: solid 1px; color: #FFFF00; background-color: #807780;">&nbsp;' . langtransstr('Extreme') . '&nbsp;</span>';
            break;
    } // end switch
    return $uv;
}

// end get_UVrange

function get_SoilMoisture($VPsoilmoisture) {
    switch (TRUE) {
        case (($VPsoilmoisture >= 0) and ($VPsoilmoisture < 11)):
            $sm = '1';
            break;
        case (($VPsoilmoisture >= 11) and ($VPsoilmoisture < 26)):
            $sm = '2';
            break;
        case (($VPsoilmoisture >= 26) and ($VPsoilmoisture < 61)):
            $sm = '3';
            break;
        case (($VPsoilmoisture >= 61) and ($VPsoilmoisture < 101)):
            $sm = '4';
            break;
        case (($VPsoilmoisture >= 101) and ($VPsoilmoisture < 201)):
            $sm = '5';
            break;
        case ($VPsoilmoisture > 200):
            $sm = '6';
            break;
            $sm = '7';
    } // end switch
    return $sm;
}

// end get_SoilMoisture

function get_SoilTemperature($soiltemp) {
    $um = substr($uomTemp, -1);
    switch (TRUE) {
        case ((($um == 'F') and ($soiltemp < 20)) or (($um == 'C') and ($soiltemp < -6.7))):
            $gi = '1';
            break;
        case ((($um = 'F') and (($soiltemp >= 20) and ($soiltemp < 33))) or (($um = 'C') and (($soiltemp >= -6.7) and ($soiltemp < .6)))):
            $gi = '2';
            break;
        case ((($um = 'F') and (($soiltemp >= 33) and ($soiltemp < 50))) or (($um = 'C') and (($soiltemp >= .6) and ($soiltemp < 10)))):
            $gi = '3';
            break;
        case ((($um = 'F') and (($soiltemp >= 50) and ($soiltemp < 60))) or (($um = 'C') and (($soiltemp >= 10) and ($soiltemp < 15.5)))):
            $gi = '4';
            break;
        case ((($um = 'F') and (($soiltemp >= 60) and ($soiltemp < 70))) or (($um = 'C') and (($soiltemp >= 15.5) and ($soiltemp < 21.1)))):
            $gi = '5';
            break;
        case ((($um = 'F') and (($soiltemp >= 70) and ($soiltemp < 120))) or (($um = 'C') and (($soiltemp >= 21.1) and ($soiltemp < 48.)))):
            $gi = '6';
            break;
        default:
            $gi = '0';
            break;
    } // end switch
    return $gi;
}

// end get_SoilTemperature
//=========================================================================
// change the hh:mm AM/PM to h:mmam/pm format
function fixup_time($WDtime) {
    global $timeOnlyFormat, $DebugMode;
    if ($WDtime == "00:00: AM") {
        return '';
    }
    $t = explode(':', $WDtime);
    if (preg_match('/p/i', $WDtime)) {
        $t[0] = $t[0] + 12;
    }
    if ($t[0] > 23) {
        $t[0] = 12;
    }
    if (preg_match('/^12.*a/i', $WDtime)) {
        $t[0] = 0;
    }
    $t2 = join(':', $t); // put time back to gether;
    $t2 = preg_replace('/[^\d\:]/is', '', $t2); // strip out the am/pm if any
    $r = date($timeOnlyFormat, strtotime($t2));
    if ($DebugMode) {
        $r = "<!-- fixup_time WDtime='$WDtime' t2='$t2' -->" . $r;
    $r = '<span style="color: #33CC00;">' . $r . '</span>'; 
    }
    return ($r);
}

//=========================================================================
// adjust WD date to desired format
//
function fixup_date($WDdate) {
    global $timeFormat, $timeOnlyFormat, $dateOnlyFormat, $WDdateMDY, $DebugMode;
    $d = explode('/', $WDdate);      // expect ##/##/## form
    if (!isset($d[2])) {
        $d = explode('-', $WDdate);
    } // try ##-##-#### form instead
    if (!isset($d[2])) {
        $d = explode('.', $WDdate);
    } // try ##.##.#### form instead
    if ($d[2] > 70 and $d[2] <= 99) {
        $d[2] += 1900;
    } // 2 digit dates 70-99 are 1970-1999
    if ($d[2] < 99) {
        $d[2] += 2000;
    } // 2 digit dates (left) are assumed 20xx dates.
    if ($WDdateMDY) {
        $new = sprintf('%04d-%02d-%02d', $d[2], $d[0], $d[1]); //  M/D/YYYY -> YYYY-MM-DD
    } else {
        $new = sprintf('%04d-%02d-%02d', $d[2], $d[1], $d[0]); // D/M/YYYY -> YYYY-MM-DD
    }

    $r = date($dateOnlyFormat, strtotime($new));
    if ($DebugMode) {
        $r = "<!-- fixup_date WDdate='$WDdate', WDdateUSA='$WDdateMDY' new='$new' -->" . $r;
    $r = '<span style="color: #33CC00;">' . $r . '</span>'; 
    }
    return ($r);
}

//=========================================================================
// strip trailing units from a measurement
// i.e. '30.01 in. Hg' becomes '30.01'
function strip_units($data) {
    preg_match('/([\d\,\.\+\-]+)/', $data, $t);
    return $t[1];
}

//=========================================================================
// decode WD %moonage% tag and return a text description for the moon phase name
// "Moon age: 10 days,10 hours,41 minutes,80%"

function moonphase($WDmoonage) {

    preg_match_all('|(\d+)|is', $WDmoonage, $matches);
//  print "<!-- matches=\n" . print_r($matches,true) . "-->\n";
    $mdays = $matches[1][0];
    $mhours = $matches[1][1];
    $mmins = $matches[1][2];
    $mpct = $matches[1][3];

    $mdaysd = $mdays + ($mhours / 24) + ($mmins / 1440);
    // Definitions from http://www.answers.com/topic/lunar-phase
    //  * Dark Moon - Not visible
    //  * New Moon - Not visible, or traditionally, the first visible crescent of the Moon
    //  * Waxing Crescent Moon - Right 1-49% visible
    //  * First Quarter Moon - Right 50% visible
    //  * Waxing gibbous Moon - Right 51-99% visible
    //  * Full Moon - Fully visible
    //  * Waning gibbous Moon - Left 51-99% visible
    //  * Third Quarter Moon - Left 50% visible
    //  * Waning Crescent Moon - Left 1-49% visible
    //  * New Moon - Not visible

    if ($mdaysd <= 29.53 / 2) { // increasing illumination
        $ph = "Waxing";
        $qtr = "First";
    } else { // decreasing illumination
        $ph = "Waning";
        $qtr = "Last";
    }

    if ($mpct < 1) {
        return("New Moon");
    }
    if ($mpct <= 49) {
        return("$ph Crescent");
    }
    if ($mpct < 51) {
        return("$qtr Quarter");
    }
    if ($mpct < 99) {
        return("$ph Gibbous");
    }
    return("Full Moon");
}

//=========================================================================
// pick the NOAA style condition icon based on iconnumber 
function newIcon($numb) {
    global $condIconDir, $condIconType;

    $iconList = array(
        "1.png", //  0 imagesunny.visible
        "2.png", //  1 imageclearnight.visible
        "3.png", //  2 imagecloudy.visible
        "4.png", //  3 imagecloudy2.visible
        "5.png", //  4 imagecloudynight.visible
        "6.png", //  5 imagedry.visible
        "7.png", //  6 imagefog.visible
        "8.png", //  7 imagehaze.visible
        "9.png", //  8 imageheavyrain.visible
        "10.png", //  9 imagemainlyfine.visible
        "11.png", // 10 imagemist.visible
        "12.png", // 11 imagenightfog.visible
        "13.png", // 12 imagenightheavyrain.visible
        "14.png", // 13 imagenightovercast.visible
        "15.png", // 14 imagenightrain.visible
        "16.png", // 15 imagenightshowers.visible
        "17.png", // 16 imagenightsnow.visible
        "18.png", // 17 imagenightthunder.visible
        "19.png", // 18 imageovercast.visible
        "20.png", // 19 imagepartlycloudy.visible
        "21.png", // 20 imagerain.visible
        "22.png", // 21 imagerain2.visible
        "23.png", // 22 imageshowers2.visible
        "24.png", // 23 imagesleet.visible
        "25.png", // 24 imagesleetshowers.visible
        "26.png", // 25 imagesnow.visible
        "sn.gif", // 26 imagesnowmelt.visible
        "sn.gif", // 27 imagesnowshowers2.visible
        "skc.gif", // 28 imagesunny.visible
        "scttsra.gif", // 29 imagethundershowers.visible
        "hi_tsra.gif", // 30 imagethundershowers2.visible
        "tsra.gif", // 31 imagethunderstorms.visible
        "nsvrtsra.gif", // 32 imagetornado.visible
        "wind.gif", // 33 imagewindy.visible
        "ra1.gif", // 34 stopped rainning
        "windyrain.gif"     // 35 windy/rain
    );

    $tempicon = $iconList[$numb];
    if ($condIconType <> '.jpg') {
        $tempicon = preg_replace('|\.jpg|', $condIconType, $tempicon);
    }
    return($tempicon);
}

// Function to process %Currentsolarcondition% string and 
// remove duplicate stuff, then fix capitalization, and translate from English if needed
//  
function fixupCondition($inCond) {
    global $DebugMode;

    $Cond = str_replace('_', ' ', $inCond);
    $Cond = strtolower($Cond);
    $Cond = preg_replace('| -|', '', $Cond);
    $Cond = trim($Cond);
    $dt = '';

    $vals = array();
    if (strpos($Cond, '/') !== false) {
        $dt .= "<!-- vals split on slash -->\n";
        $vals = explode("/", $Cond);
    }
    if (strpos($Cond, ',') !== false) {
        $dt .= "<!-- vals split on comma -->\n";
        $vals = explode(",", $Cond);
    }
    $ocnt = count($vals);
    if ($ocnt < 1) {
        $inCond = (langtransstr(trim($inCond)));
        $inCond = preg_replace('|/$|', '', $inCond);
        return $inCond;
    }
    foreach ($vals as $k => $v) {
        if ($DebugMode) {
            $dt .= "<!-- v='$v' -->\n";
        }
        $v = ucfirst(strtolower(trim($v)));
        $vals[$k] = langtransstr($v);
        if ($DebugMode) {
            $dt .= "<!-- vals[$k]='" . $vals[$k] . "' -->\n";
        }
    }

    if ($vals[0] == '') {
        $junk = array_shift($vals);
    }
    if (isset($vals[2]) and $vals[0] == $vals[2]) {
        $junk = array_pop($vals);
    }
    reset($vals);
    $t = join(', ', $vals);

//	return($Cond . "' orig=$ocnt n=" . count($vals) ." t='$t'");
    //if ($DebugMode) {
        $t = "<!-- fixupCondition in='$inCond' out='$t' ocnt='$ocnt' -->" . $dt . $t;
    //}
    $t = (langtransstr($t));
    $t = preg_replace('|/$|', '', $t);
    return $t;
}

// -----------------------------------------------------------------------------
// MOON FUNTIONS                                                               .
// -----------------------------------------------------------------------------
function getMoonInfo($hh = 0, $mm = 0, $ss = 0, $MM = 0, $DD = 0, $YY = 0) { // very crude way of determining moon phase (but very accurate)
// ------------- start of USNO moon data -----------------------------
// PHP tables generated from USNO moon ephemeris data http://aa.usno.navy.mil/data/docs/MoonPhase.php
// using the one-year at a time query option
// Ken True - Saratoga-weather.org generated by USNO-moonphases.php - Version 1.00 - 15-Jan-2011 on 15 January 2011 23:05 EST
    $newMoons = array(// unixtime values in UTC/GMT
        /* 2013 */ /* 11-Jan-2013 19:44 */ 1357933440, 1360480800, 1363031460, 1365586500, 1368145680, 1370706960, 1373267640, 1375825860, 1378380960, 1380933240, 1383483000, 1386030120,
        /* 2014 */ /* 01-Jan-2014 11:14 */ 1388574840, 1391117880, 1393660800, 1396205100, 1398752040, 1401302400, 1403856480, 1406414520, 1408975980, 1411539240, 1414101420, 1416659520, 1419212160,
        /* 2015 */ /* 20-Jan-2015 13:14 */ 1421759640, 1424303220, 1426844160, 1429383420, 1431922380, 1434463500, 1437009840, 1439563980, 1442126460, 1444694760, 1447264020, 1449829740,
        /* 2016 */ /* 10-Jan-2016 01:30 */ 1452389400, 1454942340, 1457488440, 1460028240, 1462562940, 1465095540, 1467630060, 1470170640, 1472720580, 1475280660, 1477849080, 1480421880, 1482994380,
        /* 2017 */ /* 28-Jan-2017 00:07 */ 1485562020, 1488121080, 1490669820, 1493208960, 1495741440, 1498271460, 1500803100, 1503340200, 1505885400, 1508440320, 1511005320, 1513578600,
        /* 2018 */ /* 17-Jan-2018 02:17 */ 1516155420, 1518728700, 1521292260, 1523843820, 1526384880, 1528918980, 1531450080, 1533981480, 1536516060, 1539056820, 1541606520, 1544167200,
        /* 2019 */ /* 06-Jan-2019 01:28 */ 1546738080, 1549314180, 1551888240, 1554454200, 1557009900, 1559556120, 1562094960, 1564629120, 1567161420, 1569695160, 1572233880, 1574780700, 1577337180,
        /* 2020 */ /* 24-Jan-2020 21:42 */ 1579902120, 1582471920, 1585042080, 1587608760, 1590169140, 1592721660, 1595266380, 1597804920, 1600340400, 1602876660, 1605416820, 1607962560,
        /* 2021 */ /* 13-Jan-2021 05:00 */ 1610514000, 1613070360, 1615630860, 1618194660, 1620759600, 1623322380, 1625879760, 1628430600, 1630975920, 1633518300, 1636060440, 1638603780,
        /* 2022 */ /* 02-Jan-2022 18:33 */ 1641148380, 1643694360, 1646242500, 1648794240, 1651350480, 1653910200, 1656471120, 1659030900, 1661588220, 1664142840, 1666694940, 1669244220, 1671790620,
        /* 2023 */ /* 21-Jan-2023 20:53 */ 1674334380, 1676876760, 1679419380, 1681963920, 1684511580, 1687063020, 1689618720, 1692178680, 1694742000, 1697306100, 1699867620
    ); /* end of newMoons array */

    $Q1Moons = array(// unixtime values in UTC/GMT
        /* 2013 */ /* 18-Jan-2013 23:45 */ 1358552700, 1361133060, 1363714020, 1366288260, 1368851640, 1371403440, 1373944680, 1376477760, 1379005680, 1381532520, 1384063020, 1386601920,
        /* 2014 */ /* 08-Jan-2014 03:39 */ 1389152340, 1391714520, 1394285220, 1396859460, 1399432500, 1402000740, 1404561540, 1407113400, 1409656260, 1412191920, 1414723680, 1417255560, 1419791460,
        /* 2015 */ /* 27-Jan-2015 04:48 */ 1422334080, 1424884440, 1427442180, 1430006100, 1432574340, 1435143720, 1437710640, 1440271860, 1442825940, 1445373060, 1447914420, 1450451640,
        /* 2016 */ /* 16-Jan-2016 23:26 */ 1452986760, 1455522360, 1458061380, 1460606340, 1463158920, 1465719000, 1468284720, 1470853260, 1473421740, 1475987580, 1478548260, 1481101380,
        /* 2017 */ /* 05-Jan-2017 19:47 */ 1483645620, 1486181940, 1488713520, 1491244740, 1493779620, 1496320920, 1498870260, 1501428180, 1503994380, 1506567180, 1509142920, 1511715780, 1514280000,
        /* 2018 */ /* 24-Jan-2018 22:20 */ 1516832400, 1519373340, 1521905700, 1524433500, 1526960940, 1529491860, 1532029920, 1534578480, 1537139700, 1539712920, 1542293640, 1544874540,
        /* 2019 */ /* 14-Jan-2019 06:45 */ 1547448300, 1550010360, 1552559220, 1555095960, 1557623520, 1560146340, 1562669700, 1565199060, 1567739400, 1570294020, 1572862980, 1575442680,
        /* 2020 */ /* 03-Jan-2020 04:45 */ 1578026700, 1580607720, 1583179020, 1585736460, 1588279080, 1590809400, 1593332160, 1595853120, 1598378280, 1600912500, 1603459380, 1606020300, 1608594060,
        /* 2021 */ /* 20-Jan-2021 21:01 */ 1611176460, 1613760420, 1616337600, 1618901940, 1621451580, 1623988440, 1626516660, 1629040740, 1631565540, 1634095500, 1636634760, 1639186500,
        /* 2022 */ /* 09-Jan-2022 18:11 */ 1641751860, 1644328200, 1646909100, 1649486880, 1652055660, 1654613280, 1657160040, 1659697560, 1662228480, 1664756040, 1667284620, 1669818960, 1672363200,
        /* 2023 */ /* 28-Jan-2023 15:19 */ 1674919140, 1677485100, 1680057120, 1682630400, 1685200920, 1687765800, 1690322820, 1692871020, 1695411120, 1697945340, 1700477400
    ); /* end of Q1Moons array */

    $fullMoons = array(// unixtime values in UTC/GMT
        /* 2013 */ /* 27-Jan-2013 04:38 */ 1359261480, 1361823960, 1364376420, 1366919820, 1369455900, 1371987120, 1374516900, 1377049500, 1379589180, 1382139480, 1384701360, 1387272480,
        /* 2014 */ /* 16-Jan-2014 04:52 */ 1389847920, 1392421980, 1394989680, 1397547720, 1400094960, 1402632660, 1405164300, 1407694140, 1410226680, 1412765460, 1415312580, 1417868820,
        /* 2015 */ /* 05-Jan-2015 04:53 */ 1420433580, 1423004940, 1425578700, 1428149100, 1430710920, 1433261940, 1435803600, 1438339380, 1440873300, 1443408600, 1445947500, 1448491440, 1451041860,
        /* 2016 */ /* 24-Jan-2016 01:46 */ 1453599960, 1456165200, 1458734460, 1461302640, 1463865240, 1466420520, 1468968960, 1471512360, 1474052700, 1476591780, 1479131520, 1481673900,
        /* 2017 */ /* 12-Jan-2017 11:34 */ 1484220840, 1486773180, 1489330440, 1491890880, 1494452520, 1497013800, 1499573160, 1502129460, 1504681380, 1507228800, 1509772980, 1512316020,
        /* 2018 */ /* 02-Jan-2018 02:24 */ 1514859840, 1517405220, 1519951860, 1522499820, 1525049880, 1527603540, 1530161580, 1532722800, 1535284560, 1537843920, 1540399500, 1542951540, 1545500880,
        /* 2019 */ /* 21-Jan-2019 05:16 */ 1548047760, 1550591580, 1553132580, 1555672320, 1558213860, 1560760260, 1563313080, 1565872140, 1568435580, 1571000880, 1573565640, 1576127520,
        /* 2020 */ /* 10-Jan-2020 19:21 */ 1578684060, 1581233580, 1583776080, 1586313300, 1588848300, 1591384320, 1593924240, 1596470340, 1599024120, 1601586300, 1604155740, 1606728600, 1609298880,
        /* 2021 */ /* 28-Jan-2021 19:16 */ 1611861360, 1614413820, 1616957280, 1619494260, 1622027640, 1624560000, 1627094220, 1629633720, 1632182100, 1634741820, 1637312220, 1639888500,
        /* 2022 */ /* 17-Jan-2022 23:48 */ 1642463280, 1645030560, 1647587820, 1650135300, 1652674440, 1655207520, 1657737480, 1660268160, 1662803940, 1665348900, 1667905320, 1670472480,
        /* 2023 */ /* 06-Jan-2023 23:08 */ 1673046480, 1675621680, 1678192800, 1680755640, 1683308040, 1685850120, 1688384340, 1690914720, 1693445700, 1695981420, 1698524640, 1701076560
    ); /* end of fullMoons array */

    $Q3Moons = array(// unixtime values in UTC/GMT
        /* 2013 */ /* 05-Jan-2013 03:58 */ 1357358280, 1359899760, 1362433980, 1364963760, 1367493240, 1370026680, 1372567980, 1375119780, 1377682500, 1380254100, 1382830800, 1385407680, 1387979280,
        /* 2014 */ /* 24-Jan-2014 05:20 */ 1390540800, 1393089300, 1395625560, 1398153120, 1400677140, 1403203140, 1405735680, 1408278360, 1410833100, 1413400320, 1415978100, 1418561460,
        /* 2015 */ /* 13-Jan-2015 09:46 */ 1421142360, 1423713000, 1426268880, 1428810240, 1431340560, 1433864520, 1436387040, 1438912980, 1441446840, 1443992760, 1446553440, 1449128400,
        /* 2016 */ /* 02-Jan-2016 05:30 */ 1451712600, 1454297280, 1456873860, 1459437420, 1461986940, 1464523920, 1467051540, 1469574000, 1472096460, 1474624560, 1477163640, 1479717180, 1482285360,
        /* 2017 */ /* 19-Jan-2017 22:13 */ 1484863980, 1487446380, 1490025480, 1492595820, 1495153980, 1497699180, 1500233160, 1502759700, 1505283900, 1507811100, 1510346160, 1512892260,
        /* 2018 */ /* 08-Jan-2018 22:25 */ 1515450300, 1518018840, 1520594400, 1523171820, 1525745340, 1528309920, 1530863460, 1533406680, 1535942220, 1538473500, 1541004000, 1543537140, 1546076040,
        /* 2019 */ /* 27-Jan-2019 21:10 */ 1548623400, 1551180480, 1553746200, 1556317080, 1558888380, 1561455960, 1564017480, 1566572160, 1569120060, 1571661540, 1574197860, 1576731420,
        /* 2020 */ /* 17-Jan-2020 12:58 */ 1579265880, 1581805020, 1584351240, 1586904960, 1589464980, 1592029440, 1594596540, 1597164300, 1599729960, 1602290340, 1604843160, 1607387760,
        /* 2021 */ /* 06-Jan-2021 09:37 */ 1609925820, 1612460220, 1614994200, 1617530520, 1620071400, 1622618640, 1625173860, 1627737360, 1630307580, 1632880620, 1635451500, 1638016080, 1640571840,
        /* 2022 */ /* 25-Jan-2022 13:41 */ 1643118060, 1645655520, 1648186620, 1650714960, 1653244980, 1655781060, 1658326680, 1660883760, 1663451520, 1666026900, 1668605220, 1671180960,
        /* 2023 */ /* 15-Jan-2023 02:10 */ 1673748600, 1676304060, 1678846080, 1681377060, 1683901680, 1686425460, 1688953680, 1691490480, 1694038860, 1696600080, 1699173420, 1701755340
    ); /* end of Q3Moons array */

// ------------- end of USNO moon data -----------------------------

    if ($hh == 0)
        $hh = idate("H");
    if ($mm == 0)
        $mm = idate("i");
    if ($ss == 0)
        $ss = idate("s");
    if ($MM == 0)
        $MM = idate("m");
    if ($DD == 0)
        $DD = idate("d");
    if ($YY == 0)
        $YY = idate("Y");

    $date = mktime($hh, $mm, $ss, $MM, $DD, $YY);  // Unix date from local time
    if ($date < $newMoons[1])
        exit("Date must be after " . date("r", $newMoons[1]));
    if ($date > $newMoons[count($newMoons) - 1])
        exit("Date must be before " . date("r", $newMoons[count($newMoons) - 1]));

    foreach ($newMoons as $mi => $newMoon) // find next New Moon from given date
        if ($newMoon > $date)
            break;

    // Get Moon dates
    $NM = $newMoons [$mi - 1]; // previous new moon
    $Q1 = $Q1Moons [$mi - 1]; // 1st Q end
    $Q2 = $fullMoons[$mi - 1]; // 2nd Q end - Full moon
    $Q3 = $Q3Moons [$mi - 1]; // 3rd Q end
    $Q4 = $newMoons [$mi]; // 4th Q end - next new moon
    // Divide each phase into 7 periods (4 phases x 7 = 28 periods)
    $Q1p = round(($Q1 - $NM) / 7);
    $Q2p = round(($Q2 - $Q1) / 7);
    $Q3p = round(($Q3 - $Q2) / 7);
    $Q4p = round(($Q4 - $Q3) / 7);

    // Determine start and end times for major phases (lasting 1 period of 28)
    $NMe = $NM + ($Q1p / 2);                         //  0% .... - New moon
    $Q1s = $Q1 - ($Q1p / 2);
    $Q1e = $Q1 + ($Q2p / 2);   // 50% 1stQ - First Quarter
    $Q2s = $Q2 - ($Q2p / 2);
    $Q2e = $Q2 + ($Q3p / 2);   //100% 2ndQ - Full moon
    $Q3s = $Q3 - ($Q3p / 2);
    $Q3e = $Q3 + ($Q4p / 2);   // 50% 3rdQ - Last Quarter
    $NMs = $Q4 - ($Q4p / 2);                         //  0% 4thQ - New Moon
// Determine age of moon in days since last new moon
    $age = ($date - $newMoons[$mi - 1]) / 86400; // age in days since last new moon
    $dd = intval($age);
    $hh = intval(($age - $dd) * 24);
    $mm = intval(((($age - $dd) * 24) - $hh) * 60);
    $info->age = $dd . ' days, ' . $hh . ' hours, ' . $mm . ' minutes';

// Illumination
    switch (true) { // Determine moon age in degrees (0 to 360)
        case ($date <= $Q1): $ma = ($date - $NM) * (90 / ($Q1 - $NM)) + 0;
            break; // NM to Q1
        case ($date <= $Q2): $ma = ($date - $Q1) * (90 / ($Q2 - $Q1)) + 90;
            break; // Q1 to FM
        case ($date <= $Q3): $ma = ($date - $Q2) * (90 / ($Q3 - $Q2)) + 180;
            break; // FM to Q3
        case ($date <= $Q4): $ma = ($date - $Q3) * (90 / ($Q4 - $Q3)) + 270;
            break; // Q3 to NM
    }
    $info->ill = abs(round(100 * (1 + cos($ma * (M_PI / 180))) / 2) - 100);

// Deterime picture number (0-27) and moon phase
    switch (true) {
        case ($date <= $NMe): $pic = 0;
            $ph = 'NEW MOON';
            break;
        case ($date < $Q1s): $pic = 1 + (($date - $NMe) / $Q1p);
            $ph = 'Evening Crescent';
            break; // Waxing Crescent
        case ($date <= $Q1e): $pic = 7;
            $ph = 'FIRST QUARTER';
            break;
        case ($date < $Q2s): $pic = 7.5 + (($date - $Q1e) / $Q2p);
            $ph = 'Waxing Gibbous';
            break;
        case ($date <= $Q2e): $pic = 14;
            $ph = 'FULL MOON';
            break;
        case ($date < $Q3s): $pic = 14.5 + (($date - $Q2e) / $Q3p);
            $ph = 'Waning Gibbous';
            break;
        case ($date <= $Q3e): $pic = 21;
            $ph = 'LAST QUARTER';
            break;
        case ($date < $NMs): $pic = 21.5 + (($date - $Q3e) / $Q4p);
            $ph = 'Morning Crescent';
            break; // Waning Crecent
        default : $pic = 0;
            $ph = 'NEW MOON';
    }
    $info->pic = round($pic);
    $info->phase = $ph;
    $info->NM = $NM;
    $info->Q1 = $Q1;
    $info->FM = $Q2;
    $info->Q3 = $Q3;
    $info->Q4 = $Q4;
    $info->FM2 = $fullMoons[$mi];

    return $info;
}

function getMoonAge() {
    $mooninfo = getMoonInfo();
    return $mooninfo->age;
}

function getMoonPic() {
    $mooninfo = getMoonInfo();
    return $mooninfo->pic;
}

function getMoonIll() {
    $mooninfo = getMoonInfo();
    return $mooninfo->ill;
}

function getMoonPhase() {
    $mooninfo = getMoonInfo();
    return $mooninfo->phase;
}

// The following function are for the TIDE module

function next_tide_info($tideinfo, $day) {
    global $time_offset_next_tide;
//$tideinfo = '  High Tide:  2049   6.0';
//make an array out of the string, split on the space char after removing multiple spaces
    $t_arr = explode(" ", preg_replace('/\s\s+/', ' ', trim($tideinfo)));

//print_r ($t_arr);
//Array ( [0] => High [1] => Tide: [2] => 2049 [3] => 6.0 )
// now time in seconds
    $time_now = (time() - ($time_offset_next_tide * (60 * 60)));

// get tide time in seconds.
    $t_hr = substr($t_arr[2], 0, 2);
    $t_min = substr($t_arr[2], -2);
    $t_time = strtotime(date("Y-m-d", $time_now + (86400 * $day)) . " $t_hr:$t_min:" . date('s'));

// next tide in minutes
    $t_diff = round(($t_time - $time_now) / 60);
    $next_t1 = array(
        "$t_diff" => strtolower($t_arr[0]),
    );
    return $next_t1;
}

// end function mins_to_next_tide

function round_tide_time($mins_to_next_tide) {

    if ($mins_to_next_tide <= 0)
        return 0;
    if ($mins_to_next_tide >= 360)
        return 360;

    //echo "mins_to_next_tide: $mins_to_next_tide<br>";

    $mins_to_next_tide = (round($mins_to_next_tide / 15) * 15);

    return $mins_to_next_tide;
}

// end function round_tide_time

global $nexttidequarter;

function select_tide_image($mins_to_next_tide, $high_or_low) {
    global $nexttidequarter;

    if ($high_or_low == 'high')
        $nexttidequarter = "High$mins_to_next_tide";
    if ($high_or_low == 'low')
        $nexttidequarter = "Low$mins_to_next_tide";

    if ($mins_to_next_tide <= 0)
        return $high_or_low . '_0.jpg';
    if ($mins_to_next_tide >= 360)
        return $high_or_low . '_360.jpg';
    return $high_or_low . '_' . $mins_to_next_tide . '.jpg';
}

// end function select_tide_image

function time_till_tide($time) {
    // takes a time diff like 6740 secs and formats to '1 hour, 52 minutes'
    $hrs = (int) intval($time / 3600);
    $time = (int) intval($time - (3600 * $hrs));
    $mns = (int) intval($time / 60);
    $time = (int) intval($time - (60 * $mns));
    $secs = (int) intval($time / 1);
    $hrs == 1 and $string .= "$hrs hour, ";
    $hrs > 1 and $string .= "$hrs hours, ";
    $string .= sprintf("%01d minutes", $mns);
    return $string;
}

function tide_data($numb) {
    global $tidefile, $yearDate, $convertRF;

    $tideD = implode('', file($tidefile));

    $time = time();

    // Get tide records by date, cut to length and split
    // If year is included in date
    if ($yearDate == "yes") {
        $tidedate = strstr($tideD, date("l Y-m-d", $time + (86400 * $numb)));
        $tideEnd = strpos($tideD, date("l Y-m-d", $time + (86400 * ($numb + 1))));
    } else {
        $tidedate = strstr($tideD, date("l m-d", $time + (86400 * $numb)));
        $tideEnd = strpos($tideD, date("l m-d", $time + (86400 * ($numb + 1))));
        //$tidedate = strstr($tideD, date("D m-d", $time + (86400 * $numb)));
        //$tideEnd = strpos($tideD, date("D m-d", $time + (86400 * ($numb + 1))));
    }
// The last tide will not show because it is not followed by a date. So we will
    // set the end possition to a fixed leangth
    if (!$tideEnd == false) {
        $tide = substr($tidedate, 0, $tideEnd);
    } else {
        $tide = substr($tidedate, 0, 280);
    }
    // Check if tides are in rising/falling format
    if (strstr($tide, "Rising")) {
        $risingFormat = "yes";
        //$tide = substr($tidedate, 0, $tideEnd);
    }
    $tide = explode("\n", $tide);
    // Check if sunrise info is included
    if (strstr($tide[1], "Sunrise")) {
        $tide = $tide[0] . "|" . $tide[3] . "|" . $tide[4] . "|" . $tide[5] . "|" . $tide[6] . "|" . $tide[7] . "|" . $tide[8] . "|" . $tide[9] . "|" . $tide[10];
        $tide = explode("|", $tide);
    } else {
        $tide = $tide[0] . "|" . $tide[1] . "|" . $tide[2] . "|" . $tide[3] . "|" . $tide[4] . "|" . $tide[5] . "|" . $tide[6] . "|" . $tide[7] . "|" . $tide[8];
        $tide = explode("|", $tide);
    }
    return $tide;
}

// eof minutes to next tide
// END OF ALL TIDE FUNCTIONS


function CU_CBI($inTemp,$inTempUOM,$inHumidity) {
	// thanks to Chris from sloweather.com for the CBI calculation script
	// modified by Ken True for template usage
	
	preg_match('/([\d\.\,\+\-]+)/',$inTemp,$t); // strip non-numeric from inTemp if any
	$ctemp = $t[1];
	if(strpos($ctemp,',') !== false) {
		$ctemp = preg_replace('|,|','.',$ctemp);
	}
	if(!preg_match('|C|i',$inTempUOM)) {
	  $ctemp = ($ctemp-32.0) / 1.8; // convert from Fahrenheit	
	}
	preg_match('/([\d\.\,\+\-]+)/',$inHumidity,$t); // strip non-numeric from inHumidity if any
	$rh = $t[1];
	if(strpos($rh,',') !== false) {
		$rh = preg_replace('|,|','.',$rh);
	}

	// Start Index Calcs
	
	// Chandler Index
	$cbi = (((110 - 1.373 * $rh) - 0.54 * (10.20 - $ctemp)) * (124 * pow(10,-0.0142 * $rh) ))/60;
	// CBI = (((110 - 1.373*RH) - 0.54 * (10.20 - T)) * (124 * 10**(-0.0142*RH)))/60
	
	//Sort out the Chandler Index
	$cbi = round($cbi,1);
	if ($cbi > "97.5") {
		$cbitxt = "EXTREME";
		$cbiimg= "fdl_extreme.gif";
	
	} elseif ($cbi >="90") {
		$cbitxt = "VERY HIGH";
		$cbiimg= "fdl_vhigh.gif";
	
	} elseif ($cbi >= "75") {
		$cbitxt = "HIGH";
		$cbiimg= "fdl_high.gif";
	
	} elseif ($cbi >= "50") {
		$cbitxt = "MODERATE";
		$cbiimg= "fdl_moderate.gif";
	
	} else {
		$cbitxt="LOW";
		$cbiimg= "fdl_low.gif";
	}
	 $data = array($cbi,$cbitxt,$cbiimg);
	 return $data;
	 
} // end CU_CBI



// =============================================================================
//  Uncomment the next two functions if you get an error message that says
//  Fatal error: Call to undefined function langtransstr()
//
// function langtransstr($str) {  // added
//   return($str);
// }
//
// function langtrans($str) {  // added
//  echo $str;
//    return;
// }
// end of functions
//=========================================================================
?>