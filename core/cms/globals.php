<?php

/**
 * This is the shortcut to DIRECTORY_SEPARATOR
 */
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

/**
 * This is the shortcut to Yii::app()
 */
function app() {
    return Yii::app();
}

/**
 * This is the shortcut to Yii::app()->clientScript
 */
function cs() {
    // You could also call the client script instance via Yii::app()->clientScript
    // But this is faster
    return Yii::app()->getClientScript();
}

/**
 * This is the shortcut to Yii::app()->user.
 */
function user() {
    return Yii::app()->getUser();
}

/**
 * This is the shortcut to Yii::app()->createUrl()
 */
function url($route, $params = array(), $ampersand = '&') {
    return Yii::app()->createUrl($route, $params, $ampersand);
}

/**
 * This is the shortcut to CHtml::encode
 */
function h($text) {
    return htmlspecialchars($text, ENT_QUOTES, Yii::app()->charset);
}

/**
 * Set the key, value in Session
 * @param object $key
 * @param object $value
 * @return boolean 
 */
function setSession($key, $value) {
    return Yii::app()->getSession()->add($key, $value);
}

/**
 * Get the value from key in Session
 * @param object $key
 *
 * @return object
 */
function getSession($key) {
    return Yii::app()->getSession()->get($key);
}

/**
 * This is the shortcut to CHtml::link()
 */
function l($text, $url = '#', $htmlOptions = array()) {
    return CHtml::link($text, $url, $htmlOptions);
}

/**
 * This is the shortcut to Yii::t() with default category = 'stay'
 */
function t($category = 'cms', $message, $params = array(), $source = null, $language = null) {
    return Yii::t($category, $message, $params, $source, $language);
}

/**
 * This is the shortcut to Yii::app()->request->baseUrl
 * If the parameter is given, it will be returned and prefixed with the app baseUrl.
 */
function bu($url = null) {
    static $baseUrl;
    if ($baseUrl === null)
        $baseUrl = Yii::app()->getRequest()->getBaseUrl();
    return $url === null ? $baseUrl : $baseUrl . '/' . ltrim($url, '/');
}

/**
 * Returns the named application parameter.
 * This is the shortcut to Yii::app()->params[$name].
 */
function param($name) {
    return Yii::app()->params[$name];
}

/**
 * var_dump($varialbe) and exit
 * 
 */
function dump($a) {
    var_dump($a);
    exit;
}

/**
 * Convert local timestamp to GMT
 * 
 */
function local_to_gmt($time = '') {
    if ($time == '')
        $time = time();
    return mktime(gmdate("H", $time), gmdate("i", $time), gmdate("s", $time), gmdate("m", $time), gmdate("d", $time), gmdate("Y", $time));
}

/**
 * Return the settings Component
 * @return type 
 */
function settings() {
    return Yii::app()->settings;
}

/**
 * Get extension of a file
 * 
 */
function getExt($filename) {
    return strtolower(substr(strrchr($fileName, '.'), 1));
}

/**
 * Get the current IP of the connection
 * 
 */
function ip() {
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } else {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } else {
            $ip = getenv('REMOTE_ADDR');
        }
    }
    return $ip;
}

/**
 * Generate Unique File Name for the File Upload
 * 
 */
function gen_uuid($len = 8) {

    $hex = md5(param('salt-file') . uniqid("", true));

    $pack = pack('H*', $hex);
    $tmp = base64_encode($pack);

    $uid = preg_replace("/[^A-Za-z0-9]/", "", $tmp);

    $len = max(4, min(128, $len));

    while (strlen($uid) < $len)
        $uid .= gen_uuid(22);

    $res = substr($uid, 0, $len);
    return $res;
}

/**
 * Get array of subfolders name
 */
function get_subfolders_name($path, $file = false) {

    $list = array();
    $results = scandir($path);
    foreach ($results as $result) {
        if ($result === '.' or $result === '..' or $result === '.svn')
            continue;
        if (!$file) {
            if (is_dir($path . '/' . $result)) {
                $list[] = trim($result);
            }
        } else {
            if (is_file($path . '/' . $result)) {
                $list[] = trim($result);
            }
        }
    }

    return $list;
}

/**
 * Return Combine Url
 */
function InternetCombineUrl($absolute, $relative) {
    if (substr($absolute, strlen($absolute) - 1) != '/') {
        $absolute.='/';
    }
    $p = parse_url($relative);
    if (isset($p["scheme"]))
        return $relative;

    extract(parse_url($absolute));

    //$path = dirname($path); 

    if ($relative{0} == '/') {
        $cparts = array_filter(explode("/", $relative));
    } else {
        $aparts = array_filter(explode("/", $path));
        $rparts = array_filter(explode("/", $relative));
        $cparts = array_merge($aparts, $rparts);
        foreach ($cparts as $i => $part) {
            if ($part == '.') {
                $cparts[$i] = null;
            }
            if ($part == '..') {
                $cparts[$i - 1] = null;
                $cparts[$i] = null;
            }
        }
        $cparts = array_filter($cparts);
    }

    $path = implode("/", $cparts);
    $url = "";
    if (isset($scheme)) {
        $url = "$scheme://";
    }

    if (isset($host)) {
        $url .= "$host/";
    }
    $url .= $path;
    return $url;
}

/**
 * Convert a string to slug-type
 */
function toSlug($string, $force_lowercase = true, $anal = false) {
    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
        "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
        "â€”", "â€“", ",", "<", ".", ">", "/", "?");
    $clean = trim(str_replace($strip, "", strip_tags($string)));
    $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean;
    return ($force_lowercase) ?
            (function_exists('mb_strtolower')) ?
                    mb_strtolower($clean, 'UTF-8') :
                    strtolower($clean)  :
            $clean;
}

/**
 * Get youtube video id
 */
function get_youtube_id($url, $need_curl = true) {
    $url_modified = strtolower(str_replace('www.', '', $url));
    if (strpos($url_modified, 'http://youtube.com') !== false) {
        parse_str(parse_url($url, PHP_URL_QUERY));

        /** end split the query string into an array* */
        if (!isset($v))
            return false; //fast fail for links with no v attrib - youtube only

        if ($need_curl) {
            $checklink = 'http://gdata.youtube.com/feeds/api/videos/' . $v;


            //** curl the check link ***//
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $checklink);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $result = curl_exec($ch);
            curl_close($ch);

            $return = $v;
            if (trim($result) == "Invalid id")
                $return = false; //you tube response
            return $return; //the stream is a valid youtube id.
        }
        return $v;
    }
    return false;
}

/**
 * Check current app is console or not
 */
function isConsoleApp() {
    return get_class(Yii::app()) == 'CConsoleApplication';
}

/**
 * Replace Tags
 */
function replaceTags($startPoint, $endPoint, $newText, $source) {
    return preg_replace('#(' . preg_quote($startPoint) . ')(.*)(' . preg_quote($endPoint) . ')#si', '${1}' . $newText . '${3}', $source);
}

/**
 * Encode the text into a string which all white spaces will be replaced by $rplChar
 * @param string $text  text to be encoded
 * @param Char $rplChar character to replace all the white spaces
 * @param boolean upWords   set True to uppercase the first character of each word, set False otherwise
 */
function encode($text, $rplChar = '', $upWords = true) {
    $encodedText = null;
    if ($upWords) {
        $encodedText = ucwords($text);
    } else {
        $encodedText = strtolower($text);
    }

    if ($rplChar == '') {
        $encodedText = preg_replace('/\s[\s]+/', '', $encodedText);    // Strip off multiple spaces
        $encodedText = preg_replace('/[\s\W]+/', '', $encodedText);    // Strip off spaces and non-alpha-numeric
    } else {
        $encodedText = preg_replace('/\s[\s]+/', $rplChar, $encodedText);    // Strip off multiple spaces
        $encodedText = preg_replace('/[\s\W]+/', $rplChar, $encodedText);    // Strip off spaces and non-alpha-numeric
        $encodedText = preg_replace('/^[\\' . $rplChar . ']+/', '', $encodedText); // Strip off the starting $rplChar
        $encodedText = preg_replace('/[\\' . $rplChar . ']+$/', '', $encodedText); // // Strip off the ending $rplChar
    }
    return $encodedText;
}

// Query Filter String from Litpi.com   
function queryFilterString($str) {
    //Use RegEx for complex pattern
    $filterPattern = array(
        '/select.*(from|if|into)/i', // select table query, 
        '/0x[0-9a-f]*/i', // hexa character
        '/\(.*\)/', // call a sql function
        '/union.*select/i', // UNION query
        '/insert.*values/i', // INSERT query
        '/order.*by/i'              // ORDER BY injection
    );
    $str = preg_replace($filterPattern, '', $str);

    //Use normal replace for simple replacement
    $filterHaystack = array(
        '--', // query comment
        '||', // OR operator
        '\*', // OR operator
    );

    $str = str_replace($filterHaystack, '', $str);
    return $str;
}

//XSS Clean Data Input from Litpi.com
function xss_clean($data) {
    return $data;
    // Fix &entity\n;
    $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

    // Remove any attribute starting with "on" or xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

    // Only works in IE: <span style="width: expression(alert('cms','Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

    // Remove namespaced elements (we do not need them)
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

    do {
        // Remove really unwanted tags
        $old_data = $data;
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
    } while ($old_data !== $data);

    // we are done...
    return $data;
}

function curl_post_async($url, $params) {
    foreach ($params as $key => &$val) {
        if (is_array($val))
            $val = implode(',', $val);
        $post_params[] = $key . '=' . urlencode($val);
    }
    $post_string = implode('&', $post_params);

    $parts = parse_url($url);

    $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);

    $out = "POST " . $parts['path'] . " HTTP/1.1\r\n";
    $out.= "Host: " . $parts['host'] . "\r\n";
    $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out.= "Content-Length: " . strlen($post_string) . "\r\n";
    $out.= "Connection: Close\r\n\r\n";
    if (isset($post_string))
        $out.= $post_string;

    fwrite($fp, $out);
    fclose($fp);
}

function curl_get_async($url, $params) {
    foreach ($params as $key => &$val) {
        if (is_array($val))
            $val = implode(',', $val);
        $post_params[] = $key . '=' . urlencode($val);
    }
    $post_string = implode('&', $post_params);

    $parts = parse_url($url);

    $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);

    $out = "GET " . $parts['path'] . " HTTP/1.1\r\n";
    $out.= "Host: " . $parts['host'] . "\r\n";
    $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out.= "Content-Length: " . strlen($post_string) . "\r\n";
    $out.= "Connection: Close\r\n\r\n";
    if (isset($post_string))
        $out.= $post_string;

    fwrite($fp, $out);
    fclose($fp);
}

/*
 * validate text integrity with crc64
 */
function crc64($text) {
    $crc64 = sprintf('%u', hash('crc32', $text)) . sprintf('%u', hash('crc32b', $text));
    return base_convert($crc64, 16, 10); // 64bit INT
}

function plaintext($s) {
    $s = strip_tags($s);
    $s = xss_clean($s);
    return $s;
}

function isValidURL($url) {
    return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

/**
 * Generate a random number between floor and ceiling
 *
 * @param int $floor
 * @param int $ceiling
 * @return int
 */
function RandomNumber($floor, $ceiling) {
    srand((double) microtime() * 1000000);
    return rand($floor, $ceiling);
}

/**
 * Format string of filesize
 *
 * @param string $s
 * @return string
 */
function formatFileSize($s) {
    if ($s >= "1073741824") {
        $s = number_format($s / 1073741824, 2) . " GB";
    } elseif ($s >= "1048576") {
        $s = number_format($s / 1048576, 2) . " MB";
    } elseif ($s >= "1024") {
        $s = number_format($s / 1024, 2) . " KB";
    } elseif ($s >= "1") {
        $s = $s . " bytes";
    } else {
        $s = "-";
    }

    return $s;
}

function stripslashes_deep($value) {
    $value = is_array($value) ?
            array_map('stripslashes_deep', $value) :
            stripslashes($value);

    return $value;
}

/**
 * Fix back button on IE6 (stupid) browser
 * @author khanhdn
 */
function fixBackButtonOnIE() {
    //drupal_set_header("Expires: Sat, 27 Oct 1984 08:52:00 GMT GMT");  // Always expired (1)
    //drupal_set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified (2)
    header("Cache-Control: no-store, no-cache, must-revalidate");   // HTTP/1.1 (3)
    header("Cache-Control: public");    //(4)
    header("Pragma: no-cache"); // HTTP/1.0   (5)
    ini_set('cms', 'session.cache_limiter', 'private');   // (6)
}

/**
 * Get Alphabet only
 */
function alphabetonly($string = '') {
    $output = $string;
    //replace no alphabet character
    $output = preg_replace("/[^a-zA-Z0-9]/", "-", $output);
    $output = preg_replace("/-+/", "-", $output);
    $output = trim($output, '-');

    return $output;
}

function itemsDate($time) {
    $timediff = time() - $time;
    if ($timediff < 60)
        $date = $timediff . ' sec ago';
    elseif ($timediff < 3600 - 60) // within last hour
        $date = ceil($timediff / 60) . ' min ago';
    else if ($time > strtotime('today')) // today
        $date = date('H:i', $time);
    else
        $date = date('d/m/Y', $time); // last year or more
    return $date;
}

//***eturn complex date
function complex_date($timestr) {
    return $requested = date("d/m/Y - H:i", $timestr);
}

function simple_date($timestr) {
    return $requested = date("d/m/Y", $timestr);
}

function niceDate($timestring, $separator = ' /') {
    return date('j M Y' . $separator . ' H:i', $timestring);
}

/**
 * Convert date string in format 'dd/mm/yyyy' and time string in format 'hh:mm'to timestamp                      
 * @param string $datestring
 * @param string $timestring  
 */
function datedmyToTimestamp($datestring = '01/01/1970', $timestring = '00:01') {
    $timegroup = explode(':', $timestring);
    $dategroup = explode('/', $datestring);
    return mktime((int) trim($timegroup[0]), (int) trim($timegroup[1]), 1, (int) trim($dategroup[1]), (int) trim($dategroup[0]), (int) trim($dategroup[2]));
}

/** takes two dates formatted as YYYY-MM-DD and creates an
 *  inclusive array of the dates between the from and to dates.
 */
function createDateRangeArray($strDateFrom, $strDateTo) {

    $aryRange = array();

    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

    if ($iDateTo >= $iDateFrom) {
        array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry

        while ($iDateFrom < $iDateTo) {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }
    return $aryRange;
}

function truncate($phrase, $max_words, $end_char = '&#8230;') {
    $phrase_array = explode(' ', $phrase);
    if (count($phrase_array) > $max_words && $max_words > 0)
        $phrase = implode(' ', array_slice($phrase_array, 0, $max_words)) . $end_char;

    return strip_tags($phrase);
}

function str_trim($phrase, $max_chars, $end_char = '&#8230;') {
    if (strlen($phrase) > $max_chars && $max_chars > 0)
        $phrase = substr($phrase, 0, $max_chars) . $end_char;
    return preg_replace('/[\n\r]+/', '', $phrase);
}

/**
 * Smart trim an array by comma to a max chars phrase
 * @param type $array
 * @param type $max_chars
 * @param type $elipsis
 * @return type 
 */
function arr_strim($array, $max_chars, $elipsis = '&#8230;') {
    $phrase = implode(', ', $array);
    if (strlen($phrase) > $max_chars) {
        $phrase = substr($phrase, 0, $max_chars - strlen($elipsis));
        $r = array_slice(explode(', ', $phrase), 0, -1);
        return implode(', ', $r) . ', ' . $elipsis;
    } else
        return $phrase;
}

function array_splice_assoc(&$input, $offset, $length, $replacement) {
    $replacement = (array) $replacement;
    $key_indices = array_flip(array_keys($input));
    if (isset($input[$offset]) && is_string($offset)) {
        $offset = $key_indices[$offset];
    }
    if (isset($input[$length]) && is_string($length)) {
        $length = $key_indices[$length] - $offset;
    }

    $input = array_slice($input, 0, $offset, TRUE)
            + $replacement
            + array_slice($input, $offset + $length, NULL, TRUE);
}

/**
 * change the key position in array
 * @param type $which
 * @param type $where
 * @param type $array
 * @return type 
 */
function array_move($which, $where, $array) {
    $tmpWhich = $which;
    $j = 0;
    $keys = array_keys($array);

    for ($i = 0; $i < count($array); $i++) {
        if ($keys[$i] == $tmpWhich)
            $tmpWhich = $j;
        else
            $j++;
    }
    $tmp = array_splice($array, $tmpWhich, 1);
    array_splice_assoc($array, $where, 0, $tmp);
    return $array;
}

/**
 * Get a substring starting from the last occurrence of a character/string
 *
 * @param  string $str The subject string
 * @param  string $last Search the subject for this string, and start the substring after the last occurrence of it.
 * @return string A substring from the last occurrence of $startAfter, to the end of the subject string.  If $startAfter is not present in the subject, the subject is returned whole.
 */
function substrAfter($str, $last) {
    $startPos = strrpos($str, $last);
    if ($startPos !== false) {
        $startPos++;
        return ($startPos < strlen($str)) ? substr($str, $startPos) : '';
    }
    return $str;
}

function recursive_remove_directory($directory, $empty = FALSE) {
    // if the path has a slash at the end we remove it here
    if (substr($directory, -1) == '/') {
        $directory = substr($directory, 0, -1);
    }

    // if the path is not valid or is not a directory ...
    if (!file_exists($directory) || !is_dir($directory)) {
        // ... we return false and exit the function
        return FALSE;

        // ... if the path is not readable
    } elseif (!is_readable($directory)) {
        // ... we return false and exit the function
        return FALSE;

        // ... else if the path is readable
    } else {

        // we open the directory
        $handle = opendir($directory);

        // and scan through the items inside
        while (FALSE !== ($item = readdir($handle))) {
            // if the filepointer is not the current directory
            // or the parent directory
            if ($item != '.' && $item != '..') {
                // we build the new path to delete
                $path = $directory . '/' . $item;

                // if the new path is a directory
                if (is_dir($path)) {
                    // we call this function with the new path
                    recursive_remove_directory($path);

                    // if the new path is a file
                } else {
                    // we remove the file
                    unlink($path);
                }
            }
        }
        // close the directory
        closedir($handle);

        // if the option to empty is not set to true
        if ($empty == FALSE) {
            // try to delete the now empty directory
            if (!rmdir($directory)) {
                // return false if not possible
                return FALSE;
            }
        }
        // return success
        return TRUE;
    }
}

function b64_serialize($data) {
    return base64_encode(serialize($data));
}

function b64_unserialize($data) {
    return unserialize(base64_decode($data));
}

//makes links from http|https|ftp|ftps text
function makeLinks($text) {
    //$text = preg_replace('~\b(?:http://www.|http://(?!www.)|(?<!http://)www.)(?:\.|)(\S+)\b~', '<a href="http://www.$1">$0</a>', $text);
    // make the urls hyper links
    $text = preg_replace("/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/", "<a target=\"_blank\" href=\"$0\">$0</a>", $text);
    //make all emails hot links
    $text = preg_replace("/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?))/i", "<a href=\"mailto:$1\">$1</a>", $text);
    return $text;
}

// get the numeric value from the end of the string
// usefull to get id from urls
function numFromString($string) {
    $split = explode('-', $string);
    if (is_numeric(end($split)))
        return end($split);
}

// add params from urls
function add_url_param($addparams = array()) {
    $route = Yii::app()->urlManager->parseUrl(Yii::app()->getRequest());
    $params = $_GET;
    //we remove the pagination param
    if (isset($params['page'])) {
        unset($params['page']);
    }

    foreach ($addparams as $k => $val) {
        $params[$k] = $val;
    }
    $url = Yii::app()->createUrl('/' . $route, $params);
    return $url;
}

// remove params from urls
function remove_url_param($remparams = array()) {
    $route = Yii::app()->urlManager->parseUrl(Yii::app()->getRequest());
    $params = $_GET;
    //we remove the pagination param
    if (isset($params['page'])) {
        unset($params['page']);
    }

    foreach ($remparams as $p) {
        unset($params[$p]);
    }
    $url = Yii::app()->createUrl('/' . $route, $params);
    return $url;
}

// get meddian price (product ranges)
function get_median($arr) {
    sort($arr);
    $count = count($arr); //total numbers in array
    $middleval = floor(($count - 1) / 2); // find the middle value, or the lowest middle value
    if ($count % 2) { // odd number, middle is the median
        $median = $arr[$middleval];
    } else { // even number, calculate avg of 2 medians
        $low = $arr[$middleval];
        $high = $arr[$middleval + 1];
        $median = (($low + $high) / 2);
    }
    return $median;
}

function remove_querystring_var($url, $key) {
    $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
    $url = substr($url, 0, -1);
    return ($url);
}

//remove the .00 from the second price when necessary.
//round2(123.45) return 123.45
//round2(123.00) return 123
//function round2($decimal, $places = 2) {
//    $decimal = round($decimal, $places);
//    if (floor($decimal) == $decimal)
//        return (string) floor($decimal);
//    return $decimal;
//}
function round2($val) {
    if (is_numeric($val) && floor($val) != $val) {
        return $val;
    } else {
        return floatval($val);
    }
}

/**
 * ex: 4.2 to 4, 4.3 to 4.5 and 4.7 to 5
 */
function round_nearest_half($num) {
    if ($num >= ($half = ($ceil = ceil($num)) - 0.5) + 0.25)
        return $ceil;
    else if ($num < $half - 0.25)
        return floor($num);
    else
        return $half;
}

/**
 * add an item to array without modifying the keys value
 */
function array_put_to_position(&$array, $object, $position, $name = null) {
    $count = 0;
    $return = array();
    foreach ($array as $k => $v) {
        // insert new object
        if ($count == $position) {
            if (!$name)
                $name = $count;
            $return[$name] = $object;
            $inserted = true;
        }
        // insert old object
        $return[$k] = $v;
        $count++;
    }
    if (!$name)
        $name = $count;
    if (!$inserted)
        $return[$name];
    $array = $return;
    return $array;
}

/**
 * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
 * keys to arrays rather than overwriting the value in the first array with the duplicate
 * value in the second array, as array_merge does. I.e., with array_merge_recursive,
 * this happens (documented behavior):
 *
 * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
 *     => array('key' => array('org value', 'new value'));
 *
 * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
 * Matching keys' values in the second array overwrite those in the first array, as is the
 * case with array_merge, i.e.:
 *
 * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
 *     => array('key' => array('new value'));
 *
 * Parameters are passed by reference, though only for performance reasons. They're not
 * altered by this function.
 *
 * @param array $array1
 * @param array $array2
 * @return array
 * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
 * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
 */
function array_merge_recursive_distinct(array &$array1, array &$array2) {
    $merged = $array1;

    foreach ($array2 as $key => &$value) {
        if (is_array($value) && isset($merged [$key]) && is_array($merged [$key])) {
            $merged [$key] = array_merge_recursive_distinct($merged [$key], $value);
        } else {
            $merged [$key] = $value;
        }
    }

    return $merged;
}

/**
 * merge the values of arrays added as arguments
 */
function array_merge_numeric_values() {
    $arrays = func_get_args();
    $merged = array();
    foreach ($arrays as $array) {
        foreach ($array as $key => $value) {
            if (!is_numeric($value)) {
                continue;
            }
            if (!isset($merged[$key])) {
                $merged[$key] = $value;
            } else {
                $merged[$key] += $value;
            }
        }
    }
    return $merged;
}

/** multi-sort function
 *
 * @param type $array
 * @param type $column
 * @param type $order
 * @return type 
 */
function array_qsort(&$array, $column = 0, $order = "ASC") {
    $oper = ($order == "ASC") ? ">" : "<";
    if (!is_array($array))
        return;
    usort($array, create_function('$a,$b', "return (\$a['$column'] $oper \$b['$column']);"));
    reset($array);
}