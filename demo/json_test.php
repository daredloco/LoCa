<!DOCTYPE html>
<html lang="en">
<?PHP
//includes the LoCa File (Change '../src/loca-json.php' to the location of the loca file)
require_once('../src/loca-json.php');

//Sets the bbcodes (if not set it will be TRUE)
$loca_bbcodes = true;

//Sets the bbcodes script location
$loca_bbcodelocation = dirname(dirname(__FILE__))."/src/bbcodes.php";

//Loads the languages from the directory as grouped (Change './grouped-json/' to the directory of your localization files)
LoadLanguages('./grouped-json/',true , true);

//Sets the Language to the Users language if logged in (use $_SESSION['ulang'] with an ISO-2 language to set the language)
SetUserLanguage();


?>
<head>
  <title>LoCa Test</title>
</head>

<body>
<?PHP

echo "Grouped:<br>";
echo Trans("test","Page1");
echo '<br><br>';
echo Trans("Page1_bb");
echo '<br><br><br><br>';

//Loads the languages from the directory as ungrouped (Change './ungrouped-json/' to the directory of your localization files)
LoadLanguages('./ungrouped-json/', false, true);
SetUserLanguage();
echo "Ungrouped:<br>";
echo Trans("test");
echo '<br><br>';
echo Trans("bb");
?>
</body>
</html>
