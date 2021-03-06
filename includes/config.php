<?php //Important stuff is defined here. This file must be placed at the beginning of every php file for proper operation.
//ini_set("session.save_handler", "files");
$fileInfo = array('fileName' => 'config.php');
if(isset($_REQUEST['message'])){
	$message = $_REQUEST['message'];// == 'Please Login' ? 'Please Login' : $_REQUEST['message'];
}else{
	$message = '';
}
/*
When trying to make a site work on a production server and local server without having to edit code, there
are many things to consider. The production server will surely have a different directory structure than the remote.
For example, for ServInt the publicly accessible directory would be: /home/name of the account/public_html
A web browser sees things differently. It expects http://domain name/folder or filename.
*/
define('DOMAIN','adrlist.com',true);//The base domain of the website, without the http://.
define('LOCALDOMAIN','adrlist.dev');//The base domain of the local website, without the http://.

//Begin with a / and follow with the entire path from the root of the server. For ServInt this would be /home/name_of_the_account/includes
define('PRODUCTIONINCLUDEDIRECTORY',$_SERVER['HTTP_PRODUCTION_INCLUDE_DIRECTORY'],true);
define('LOCALINCLUDEDIRECTORY',$_SERVER['HTTP_LOCAL_INCLUDE_DIRECTORY'],true);

//The home directory is the base home directory following the domain where the public facing site is found. Begin with a /
define('PRODUCTIONHOMEDIRECTORY','',true);
define('LOCALHOMEDIRECTORY','',true);

define('LOCALIP','192.168.11.27',true);//When using virtual machine software enter the ip address of the local machine.
define('THENAMEOFTHESITE','Adrlist.com',true);//This is shown at the top of each page. If your address is www.mysite.com, name it My Site.
define('READABLEDOMAIN','Adrlist.com',true);
define('REMEMBERME','adrlistRememberMe',true);//The cookie used to autofill the user's email address at the login page.
define('UNIQUECOOKIE','adrlistUniqueId');//The cookie used to autofill the user's email address at the login page.
session_cache_limiter('nocache');
session_name('adrlistsession');//use a unique session name
$rdbHost = 'localhost';//The hostname or IP address for the remote database. Get this information from your web hosting company. Example: mysql5.yourhostingcompany.com for shared hosting, 'localhost' for dedicated hosting.
$remoteDbPort = 3306;//The remote database port number.
$remoteDbName = $_SERVER['HTTP_ADRLIST_DATABASE'];//The name of the remote database.
$remoteDbUser = $_SERVER['HTTP_ADRLIST_DATABASE_USER'];//The username for the remote database.
$remoteDbPassword = $_SERVER['HTTP_ADRLIST_DATABASE_PASSWORD'];//The password for the remote database.
$ldbHost = 'localhost';//The hostname or IP address for the local database hostname, usually localhost.
$ldbPort = 3306;//The local database port number.
$ldbName = $_SERVER['HTTP_ADRLIST_DATABASE'];//The name of the local database.
$ldbUser = 'root';//The username for the local database. Often this is root.
$ldbPass = '';//The password for the local database. Often this is root.
$errorDbHost = 'localhost';//The error reporting database.
$errorDbName = 'database_name';
$errorDbPort = '3306';
$errorDbUser = 'username';
$errorDbPass = 'password';
$errorDbHostLocal = 'localhost';//The error reporting database for local connections.
$errorDbNameLocal = 'database';
$errorDbPortLocal = 3306;
$errorDbUserLocal = 'root';
$errorDbPassLocal = 'root';
define('EMAILDONOTREPLY','donotreply@' . DOMAIN);
define('EMAILSUPPORT','support@' . DOMAIN);
define('GOOGLEANALYTICS',$_SERVER['GOOGLEANALYTICS'],1);//The unique Google Analytics Web Property Id. Format: UA-XXXXX-X.
define('RECAPTCHAPRIVATEKEY', $_SERVER['RECAPTCHAPRIVATEKEY']);

class AdrlistAutoloader{
     public static function autoload($className){
		global $debug;
 		$includePath = 'Classes/' . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
		if(is_readable(__DIR__ . '/' . $includePath)){
			require $includePath;
			//$debug->add('Included: ' . $includePath);
		}else{
			die('Could not include: ' . get_include_path() . $includePath);
		}
    }
}

spl_autoload_register(null, false);
spl_autoload_register('AdrlistAutoloader::autoload');

if(empty($debug)){
	$debug = new Adrlist_Debug();
}
$debug->newFile($fileInfo['fileName']);

/*
The settings below here generally do not need to be changed.

Define HTTPS and redirect to http or https.
To force an https connection add define('FORCEHTTPS',true); at the top of the page before including this file. Conversely, add define('FORCEHTTPS',false); to force an http connection.
*/
if(!defined('FORCEHTTPS')){
	define('FORCEHTTPS',false,true);
}
if((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443){
	//Using https:. This does not mean the connection is actually secure, just that the protocol is HTTPS.
	define('HTTPS',true,true);
}else{
	//Not using https://
	define('HTTPS',false,true);
}

$virtualMachine = strstr($_SERVER['SERVER_NAME'], LOCALIP) === false ? false : true;
//Define the includes folder, starting directory, and other constants depending on local or production.
if(!defined('LOCAL')){
	if(stripos($_SERVER['SERVER_NAME'],LOCALDOMAIN) === false && !$virtualMachine){
		//Production server.
		define('LOCAL',false,true);
		if(PRODUCTIONHOMEDIRECTORY == ''){
			$currentPage = $_SERVER['PHP_SELF'];
		}else{
			$currentPageParts = explode(PRODUCTIONHOMEDIRECTORY,$_SERVER['REQUEST_URI']);
			$currentPage = isset($currentPageParts[1]) ? $currentPageParts[1] : '' ;//The path and filename, minus the domain, for the currently loaded page.
		}
		//Redirect for http or https.
		if(HTTPS){
			if(FORCEHTTPS === false){
				//Redirect to http (non-secure).
				header('Location: http://' . DOMAIN . PRODUCTIONHOMEDIRECTORY . $currentPage);
			}
			define('AUTOLINK','https://' . DOMAIN . PRODUCTIONHOMEDIRECTORY,true);
			define('CURRENTPAGE','https://' . DOMAIN . PRODUCTIONHOMEDIRECTORY . $currentPage,true);
		}else{
			$debug->add('PRODUCTIONHOMEDIRECTORY: ' . PRODUCTIONHOMEDIRECTORY);
			$debug->printArray($_SERVER,'$_SERVER');
			//die($debug->output());
			if(FORCEHTTPS !== false){
				//Redirect to https (secure).
				header('Location: https://' . DOMAIN . PRODUCTIONHOMEDIRECTORY . $currentPage);
			}
			define('AUTOLINK','http://' . DOMAIN . PRODUCTIONHOMEDIRECTORY,true);
			define('CURRENTPAGE','http://' . DOMAIN . PRODUCTIONHOMEDIRECTORY . $currentPage,true);
		}
		set_include_path(PRODUCTIONINCLUDEDIRECTORY . '/');
		define('COOKIEDOMAIN','.' . DOMAIN,true);
		define('COOKIEPATH','/',true);//The / is the default so the session cookie functions on all folders of the site.
		$dbHost = $rdbHost;
		$dbName = $remoteDbName;
		$dbPort = $remoteDbPort;
		$dbUser = $remoteDbUser;
		$dbPass = $remoteDbPassword;
	}else{
		//Local server.
		define('LOCAL',true,true);
		if(LOCALHOMEDIRECTORY == ''){
			$currentPage = $_SERVER['PHP_SELF'];
		}else{
			$currentPageParts = explode(LOCALHOMEDIRECTORY,$_SERVER['PHP_SELF']);
			$currentPage = isset($currentPageParts[1]) ? $currentPageParts[1] : '' ;//The path and filename, minus the domain, for the currently loaded page.
		}
		if(HTTPS){
			if(FORCEHTTPS === false){
				//Redirect to http (non-secure).
				if($virtualMachine){
					header('Location: http://' . LOCALIP . '/' . LOCALDOMAIN . LOCALHOMEDIRECTORY . $currentPage);
				}else{
					header('Location: http://' . LOCALDOMAIN . LOCALHOMEDIRECTORY . $currentPage);
				}
			}
			define('AUTOLINK','https://' . LOCALDOMAIN . LOCALHOMEDIRECTORY,true);
			define('CURRENTPAGE','https://' . LOCALDOMAIN . LOCALHOMEDIRECTORY . $currentPage,true);
		}else{
			if(FORCEHTTPS !== false){
				//Redirect to https (secure).
				if($virtualMachine){
					header('Location: https://' . LOCALIP . '/' . LOCALDOMAIN . LOCALHOMEDIRECTORY . $currentPage);
				}else{
					header('Location: https://' . LOCALDOMAIN . LOCALHOMEDIRECTORY . $currentPage);
				}
			}
			define('AUTOLINK','http://' . LOCALDOMAIN . LOCALHOMEDIRECTORY,true);
			define('CURRENTPAGE','http://' . LOCALDOMAIN . LOCALHOMEDIRECTORY . $currentPage,true);
		}
		set_include_path(LOCALINCLUDEDIRECTORY . '/');
		define('COOKIEDOMAIN','.' . LOCALDOMAIN,true);
		define('COOKIEPATH','/',true);//This limits the session cookie to the current domain.
		$dbHost = $ldbHost;
		$dbName = $ldbName;
		$dbPort = $ldbPort;
		$dbUser = $ldbUser;
		$dbPass = $ldbPass;
		$errorDbHost = $errorDbHostLocal;
		$errorDbName = $errorDbNameLocal;
		$errorDbPort = $errorDbPortLocal;
		$errorDbUser = $errorDbUserLocal;
		$errorDbPass = $errorDbPassLocal;
	}
}

//Define the current local time.
date_default_timezone_set('UTC');
setlocale(LC_ALL,'en_US');
setlocale(LC_CTYPE,'C');//Downgrades the character type locale to the POSIX (C) locale.
list($micro, $sec) = explode(" ", microtime());
define('TIMESTAMP', $sec);//Unix timestamp of the default timezone in config.php (UTC), so all time functions refer to that timezone.
define('MICROTIME',(int)str_replace('0.' ,'',$micro));//Microseconds displayed as an eight digit integer (47158200).
define('DATETIME', date('Y-m-d H:i:s', TIMESTAMP));//This time is used for entry into a MYSQL database as a datetime format: YYYY-MM-DD HH:MM:SS
$PHPErrorHandler = new Adrlist_ErrorHandler(NULL, true);
$useStrictDebugging = false;
if($useStrictDebugging){
	function my_exception_handler(Exception $e){
		global $debug;
		//error_log($debug->printArrayOutput($e),3,__DIR__ . '/../customError.log');
//		$myFile = __DIR__ . '/../customError.log';
		$myFile = '../customError.log';
		if(is_writable($myFile)){
			$filesize = filesize($myFile);
			$mode = $filesize > 100000 ? 'w' : 'a';
			$fh = fopen($myFile, $mode);
			fwrite($fh, DATETIME . '
' . $debug->printArray($e) . $debug->output());
		}else{
			echo $myFile . ' is not readable.
	';
		}
		$path = LOCAL ? LOCALDOMAIN : DOMAIN;
		echo '<html lang="en" xml:lang="en">
<body style="text-align:center;font-family:Helvetica,Arial,Verdana,sans-serif;	font-size:0.75em;font-size-adjust:none;font-stretch:normal;font-style:normal;font-variant:normal;font-weight:normal;line-height:1.2em;">
<div style="text-align:left">
	<img src="' . LINKIMAGES . '/logo.png" style="height:68px;width:245px">
</div>
<div>
	We apologize, but we encountered an error we couldn\'t recover from.<br>
<br>
Please <a href="' . $_SERVER['PHP_SELF'] . '">refresh this page</a> and try again.
</div>
', $debug->output(true),'
</body>
</html>';
	}
	
	function customError($errno, $errstr){
		echo "<b>Error:</b> [$errno] $errstr<br>";
		echo "Ending Script";
		die();
	} 
	
	function exception_error_handler($errorNumber,$errorMessage,$filename,$lineNumber){
		global $debug;
		$debug->printArray($errorNumber,'$errorNumber');
		throw new ErrorException($errorMessage, $errorNumber,0,$filename,$lineNumber);
	}

	function userErrorHandler($errno, $errmsg, $filename, $linenum, $vars){
		// timestamp for the error entry
		$dt = date("Y-m-d H:i:s (T)");
	
		// define an assoc array of error string
		// in reality the only entries we should
		// consider are E_WARNING, E_NOTICE, E_USER_ERROR,
		// E_USER_WARNING and E_USER_NOTICE
		$errortype = array (
					E_ERROR              => 'Error',
					E_WARNING            => 'Warning',
					E_PARSE              => 'Parsing Error',
					E_NOTICE             => 'Notice',
					E_CORE_ERROR         => 'Core Error',
					E_CORE_WARNING       => 'Core Warning',
					E_COMPILE_ERROR      => 'Compile Error',
					E_COMPILE_WARNING    => 'Compile Warning',
					E_USER_ERROR         => 'User Error',
					E_USER_WARNING       => 'User Warning',
					E_USER_NOTICE        => 'User Notice',
					E_STRICT             => 'Runtime Notice',
					E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
					);
		// set of errors for which a var trace will be saved
		$user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
		
		$err = "<errorentry>\n";
		$err .= "\t<datetime>" . $dt . "</datetime>\n";
		$err .= "\t<errornum>" . $errno . "</errornum>\n";
		$err .= "\t<errortype>" . $errortype[$errno] . "</errortype>\n";
		$err .= "\t<errormsg>" . $errmsg . "</errormsg>\n";
		$err .= "\t<scriptname>" . $filename . "</scriptname>\n";
		$err .= "\t<scriptlinenum>" . $linenum . "</scriptlinenum>\n";
	
		if (in_array($errno, $user_errors)) {
			$err .= "\t<vartrace>" . wddx_serialize_value($vars, "Variables") . "</vartrace>\n";
		}
		$err .= "</errorentry>\n\n";
		
		// for testing
		echo $err;
	
		// save to the error log, and e-mail me if there is a critical user error
		error_log($err, 3, "../customError.log");
		if ($errno == E_USER_ERROR) {
			mail("mark@markproaudio.com", "Critical User Error", $err);
		}
	}
	set_exception_handler('my_exception_handler');
	//set_error_handler("my_exception_handler");//Do not use on a production server. This is only for debugging.

}

//Define MAGICQUOTES
if(get_magic_quotes_gpc()){
	if(!ini_set('magic_quotes_gpc', 'Off')){
		define('MAGICQUOTES',true,true);
		$debug->add('Just set magic_quotes_gpc: ' . MAGICQUOTES . '<br>');
	}
}else{
	define('MAGICQUOTES',false,true);
}

define('DSN', "mysql:host=$dbHost;dbname=$dbName;port=$dbPort{user}$dbUser{pass}$dbPass",true);
define('ERRORDBC', "mysql:host=$errorDbHost;dbname=$errorDbName;port=$errorDbPort{user}$errorDbUser{pass}$errorDbPass");

//define('ERRORDBC', "mysql:host=$errorDbHost;dbname=$errorDbName;port=$errorDbPort{user}$errorDbUser{pass}$errorDbPass");
session_set_cookie_params(7776000, COOKIEPATH, COOKIEDOMAIN,HTTPS,HTTPS);
session_start();
$_SESSION['dateFormat'] = 'M j, Y';

$debug->add('AUTOLINK: ' . AUTOLINK . '<br>
COOKIEDOMAIN: ' . COOKIEDOMAIN . '<br>
COOKIEPATH: ' . COOKIEPATH . '<br>
LOCAL: ' . LOCAL . '<br>
HTTPS: ' . HTTPS . '<br>
FORCEHTTPS: ' . FORCEHTTPS . '<br>');
$debug->printArray($_SERVER,'$_SERVER');
if(isset($_COOKIE)){
	$debug->printArray($_COOKIE, '$_COOKIE');
}
if(isset($_SESSION)){
	$debug->printArray($_SESSION, '$_SESSION');
}
$debug->add('session_name: ' . session_name() . '<br>
session_id: ' . session_id() . '<br>
DATETIME: ' . DATETIME . '<br>
MICROTIME: ' . MICROTIME);

define('LINKADMIN', AUTOLINK . '/admin', 1);
define('LINKADMINTOOLS', LINKADMIN . '/adminTools.php', 1);
define('LINKADRLISTS', AUTOLINK . '/adrLists', 1);
define('LINKCONTACT', AUTOLINK . '/support', 1);
define('LINKCREATEACCOUNT', AUTOLINK . '/createAccount', 1);
define('LINKCSS', AUTOLINK . '/css', 1);
define('LINKEDITLIST', LINKADRLISTS . '/listEdit.php', 1);
define('LINKERRORREPORTING', AUTOLINK . '/errors/report.php', 1);
define('LINKFAQ', AUTOLINK . '/faq', 1);
define('LINKFAQEDIT', LINKADMIN . '/faqEdit.php', 1);
define('LINKFEATURES', AUTOLINK . '/features', 1);
define('LINKFORGOTPASSWORD', AUTOLINK . '/forgotPassword', 1);
define('LINKIMAGES', AUTOLINK . '/images', 1);
define('LINKJOIN', AUTOLINK . '/join', 1);
define('LINKJS', AUTOLINK . '/js', 1);
define('LINKLEGAL', AUTOLINK . '/terms', 1);
define('LINKLOGIN', AUTOLINK . '/login', 1);
define('LINKMYACCOUNT', AUTOLINK . '/myAccount', 1);
define('LINKPLANS', AUTOLINK . '/plans', 1);
define('LINKPAYPAL', LINKADMIN . '/payPal.php', 1);
define('LINKPRIVACY', AUTOLINK . '/privacy', 1);
define('LINKSCENES', AUTOLINK . '/scenes', 1);
define('LINKSUPPORT', AUTOLINK . '/support', 1);
define('LINKSITEMAP', '/sitemap.php', 1);
define('LINKUSERMANAGEMENT', LINKADMIN . '/userManagement.php', 1);
define('COLORBLACK', '000000', 1);
define('COLORBLUE', '00BCDC', 1);
define('COLORGRAY', 'F5F5F5', 1);
define('COLORLIGHTRED', 'FF7070', 1);
define('COLORTEXT', '333333', 1);
define('FONT', 'Helvetica Neue,​Helvetica,​Verdana,​Arial,​sans-serif', 1);
define('SIZE1', '1', 1);
define('SIZE2', '2', 1);
define('SIZE3', '3', 1);
define('SIZE4', '4', 1);
define('SIZE5', '5', 1);
$success = false;
$Dbc = new Adrlist_Dbc(DSN);
try{
	if(!$Dbc){
		throw new Adrlist_CustomException('There is no database connection.','Could not connect to the database.','');
	}
}catch(Adrlist_CustomException $e){
	print $e->getMessage();
	$PHPErrorHandler->addErrorMessage($e->getMessage());
}
if(empty($_REQUEST['mode'])){
	define('MODE', '',true);
}else{
	define('MODE', $_REQUEST['mode'],true);
}
$debug->add('MODE: ' . MODE);
require_once('functions.php');