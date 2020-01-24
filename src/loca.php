<?PHP
/*
LoCa Localization System
Version: 2.0
© 2015-2020 Roman Wanner
*/

//STARTS THE SESSION IF NOT STARTED ALREADY
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//LOADS THE LANGUAGES FROM DIRECTORY $dir and puts the Language Objects inside the $_SESSION['languages'] array
function LoadLanguages($dir, $reload = false){
	if(!$reload && !is_null($_SESSION['languages'])){
		return;
	}
	$files = array_diff(scandir($dir), array('.', '..'));
	$_SESSION['languages'] = [];
	foreach($files as &$fname){
		$lang = new Language($dir.'/'.$fname);
		if($lang->lkey != null && $lang->lkey != "" && count($lang->dict) > 0){
			$_SESSION['languages'][$lang->lkey] = $lang;
		}
	}
}

//SETS THE USERSL LANGUAGE FOR THIS SESSION (CHANGE IT SO IT WILL READ OUT THE LANGUAGE OF THE LOGGED IN USER OR THE BROWSER LANGUAGE)
//RETURNS true IF LANGUAGE WAS SET AND false IF NOT
function SetLanguage($key = ""){
	if($key == ""){
		$key = "en";
	}
	if(array_key_exists($key,$_SESSION['languages']) === TRUE){
		$_SESSION['language'] = $_SESSION['languages'][$key];
		return true;
	}
	return false;
}

//SETS THE USERS LANGUAGE FOR THIS SESSION TO THE LANGUAGE OF THE BROWSER OR, IF LOGGED IN, TO THE LANGUAGE OF THE USER
function SetUserLanguage(){
	//TRIES TO SET THE BROWSER LANGUAGE IF NO LANGUAGE IS SET AT $_SESSION['language']
	if(is_null(@$_SESSION['ulang'])){
		$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		$acceptLang = ['de', 'en']; //Change the languages here to the accepted languages!!! <= IMPORTANT!
		$lang = in_array($lang, $acceptLang) ? $lang : 'en';
		if(array_key_exists($lang,$_SESSION['languages']) === TRUE){
			$_SESSION['language'] = $_SESSION['languages'][$lang];
			return true;
		}		
	}else{
		//TRIES TO SET THE USERLANGUAGE AS SELECTED LANGUAGE
		if(SetLanguage($_SESSION['ulang']) === true){
			return true;
		}
	}
	SetLanguage();
	return false;
}

//GETS THE USERS LANGUAGE OR SETS THE DEFAULT LANGUAGE 'english'
function GetLanguage(){
	if(is_null(@$_SESSION['language'])){
		return $_SESSION['languages']['en']; //Change the language 'en' to any language to set the default language
	}
	return $_SESSION['language'];
}

//TRANSLATES THE WORD WITH THE KEY $key INSIDE THE ACTUAL LANGUAGE AT $_SESSION['language']
function Trans($key){
	if(is_null(@$_SESSION['language'])){
		return '{NO_LANGUAGE_FILE}';
	}
	return $_SESSION['language']->Trans($key);
}

//LANGUAGE OBJECT, READS OUT THE FILE AND TRANSFORMS IT TO AN USABLE OBJECT
class Language{
	public $lkey;
	public $english;
	public $local;
	public $dict = [];
	
	public function __construct($file){
		//require_once(dirname(__FILE__).'/functions.php');
		if(!file_exists($file)){
			return;
		}
		$handle = fopen($file, "r");
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				if(startsWith($line,"language_key=")){
					$this->lkey = trim(str_replace("language_key=","",$line));
				}elseif(startsWith($line,"#") || $line == "" || strpos($line,"=") === false){
					//IGNORE COMMENTS, INVALID LINES AND EMPTY LINES
				}elseif(startsWith($line,"language_english=")){
					$this->english = trim(str_replace("language_english=","",$line));
				}elseif(startsWith($line,"language_local=")){
					$this->local = trim(str_replace("language_local=","",$line));
				}else{
					$wkey = explode("=",$line)[0];
					$wval = str_replace($wkey."=","",$line);
					$this->dict[$wkey] = $wval;
				}
			}
			fclose($handle);
		} else {
			return;
		} 
	}
	
	public function Trans($key){
		if(array_key_exists($key,$this->dict) === true){
			return $this->dict[$key];
		}
		return "{".$key."}";
	}
}

	//Checks if the $haystack starts with $needle and if so returns true, if not returns false
	function startsWith($haystack, $needle)
	{
		 $length = strlen($needle);
		 return (substr($haystack, 0, $length) === $needle);
	}

	//Checks if the $haystack ends with $needle and if so returns true, if not returns false
	function endsWith($haystack, $needle)
	{
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}

		return (substr($haystack, -$length) === $needle);
	}
?>