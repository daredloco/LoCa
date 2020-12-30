<?PHP
/*
LoCa Localization System with JSON
Version: 1.1
© 2015-2020 Roman Wanner
*/
class LoCa{
	//STARTS THE SESSION IF NOT STARTED ALREADY
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	//INCLUDES THE bbcodes.php FILE
	if(is_null(@$loca_usebbcode)){ $loca_usebbcode = true;}
	if(is_null(@$loca_bbcodelocation)){ $loca_bbcodelocation = dirname(__FILE__)."/bbcodes.php";}
	if($loca_usebbcode){
		if(!@include_once($loca_bbcodelocation)){ $loca_usebbcode = false; echo "<script>console.log('LoCa: [WARNING] Couldn't find bbcodes.php!');</script>";}
	}

	//LOADS THE LANGUAGES FROM DIRECTORY $dir and puts the Language Objects inside the $_SESSION['languages'] array
	function LoadLanguages($dir, $grouped = true, $reload = false){
		global $loca_usebbcode;
		
		if(!$reload && !is_null($_SESSION['languages'])){
			return;
		}
		$files = array_diff(scandir($dir), array('.', '..'));
		$_SESSION['languages'] = [];
		foreach($files as &$fname){
			$lang = new Language($dir.'/'.$fname, $grouped, $loca_usebbcode);
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
	function Trans($key, $parent = "", $libarr = null){
		if(is_null(@$_SESSION['language'])){
			return '{NO_LANGUAGE_FILE}';
		}
		return $_SESSION['language']->Trans($key,$parent, $libarr);
	}

	//LANGUAGE OBJECT, READS OUT THE FILE AND TRANSFORMS IT TO AN USABLE OBJECT
	class Language{
		public $lkey;
		public $english;
		public $local;
		public $author;
		public $version;
		public $dict = [];
		public $isgrouped;
		
		public function __construct($file, $grouped = true, $usebbcode = true){
			if(!file_exists($file)){
				return;
			}
			$content = file_get_contents($file); //Reads out the data of the file as a string
			$json = json_decode($content,true); //decodes the $content string into an array
			$this->isgrouped = $grouped;
			//Load as ungrouped
			if(!$grouped){
				foreach($json as $key=>$val){
					if($key == "language_key"){
						$this->lkey = $val;
					}else if($key == "language_english"){
						$this->english = $val;
					}else if($key == "language_local"){
						$this->local = $val;
					}else if($key == "language_author"){
						$this->author = $val;
					}else if($key == "language_version"){
						$this->version = $val;
					}else{
						if($usebbcode){ $this->dict[$key] = bb_parse($val);}else{ $this->dict[$key] = $val;}
					}
				}
				return;
			}
			//Load as grouped
			foreach($json as $key=>$val) {
				if($key == "settings" || $key == "Settings"){
					$this->lkey = $val[0]["key"];
					$this->english = $val[0]["english"];
					$this->local = $val[0]["local"];
					$this->author = $val[0]["author"];
					$this->version = $val[0]["version"];
				}else{			
					foreach($val[0] as $dkey=>$dval){
						if($usebbcode){ $this->dict[$key."_".$dkey] = bb_parse($dval);}else{ $this->dict[$key."_".$dkey] = $dval;}
					}
				}
			}
		}
		
		public function Trans($key, $parent = "", $libarr = null){
			if($parent != "" && $this->isgrouped){ $key = $parent."_".$key;}
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