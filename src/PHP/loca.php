<?PHP
/*
LoCa Localization System
Version: 3.1
© 2015-2020 Roman Wanner
*/
class LoCa{
	//STARTS THE SESSION IF NOT STARTED ALREADY
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	//INCLUDES THE bbcodes.php FILE
	$loca_usebbcode = true;
	$loca_bbcodelocation = dirname(__FILE__)."/bbcodes.php";
	if($loca_usebbcode){
		if(!@include_once($loca_bbcodelocation)){ $loca_usebbcode = false; echo "<script>console.log('LoCa: [WARNING] Couldn't find bbcodes.php!');</script>";}
	}

	//LOADS THE LANGUAGES FROM DIRECTORY $dir and puts the Language Objects inside the $_SESSION['languages'] array
	function LoadLanguages($dir, $reload = false){
		global $loca_usebbcode;
		
		if(!$reload && !is_null($_SESSION['languages'])){
			return;
		}
		$files = array_diff(scandir($dir), array('.', '..'));
		$_SESSION['languages'] = [];
		foreach($files as &$fname){
			$lang = new Language($dir.'/'.$fname, $loca_usebbcode);
			if($lang->lkey != null && $lang->lkey != "" && $lang->english != "" && $lang->local != "" && $lang->author != "" && $lang->version != "" && count($lang->dict) > 0){
				$_SESSION['languages'][$lang->lkey] = $lang;
			}
		}
	}

	//SETS THE USERS LANGUAGE FOR THIS SESSION (CHANGE IT SO IT WILL READ OUT THE LANGUAGE OF THE LOGGED IN USER OR THE BROWSER LANGUAGE)
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
	function Trans($key, $libarr = null){
		if(is_null(@$_SESSION['language'])){
			return '{NO_LANGUAGE_FILE}';
		}
		return $_SESSION['language']->Trans($key, $libarr);		
	}

	//LANGUAGE OBJECT, READS OUT THE FILE AND TRANSFORMS IT TO AN USABLE OBJECT
	class Language{
		public $lkey;
		public $english;
		public $local;
		public $author;
		public $version;
		public $dict = [];
		
		public function __construct($file, $usebbcode = false){
			if(!file_exists($file)){
				return;
			}
			$handle = fopen($file, "r");
			if ($handle) {
				$multiline = false;
				while (($line = fgets($handle)) !== false) {
					if(startsWith($line,"language_key=")){
						$this->lkey = trim(str_replace("language_key=","",$line));
					}elseif(startsWith($line,"#") || $line == "" || strpos($line,"=") === false){
					//IGNORE COMMENTS, INVALID LINES AND EMPTY LINES
					}elseif(startsWith($line,"language_english=")){
						$this->english = trim(str_replace("language_english=","",$line));
					}elseif(startsWith($line,"language_local=")){
						$this->local = trim(str_replace("language_local=","",$line));
					}elseif(startsWith($line,"language_author=")){
						$this->author = trim(str_replace("language_author=","",$line));
					}elseif(startsWith($line,"language_version=")){
						$this->version = trim(str_replace("language_version=","",$line));
					}else{
						$wkey = explode("=",$line)[0];
						$wval = str_replace($wkey."=","",$line);
						//bbcodes handler
						if($usebbcode){
							$wval = bb_parse($wval);
						}
						$this->dict[$wkey] = $wval;
					}
				}
				fclose($handle);
			} else {
				return;
			} 
		}
		
		public function Trans($key, $libarr = null){
			if(array_key_exists($key,$this->dict) === true){
				if(is_null($libarr)){
					return $this->dict[$key];	
				}else{
					$str = $this->dict[$key];
					foreach($libarr as $lkey=>$lval){
						$str = str_replace($lkey,$lval,$str);
					}
					return $str;
				}
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
}

?>