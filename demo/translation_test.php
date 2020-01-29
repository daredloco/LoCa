<!DOCTYPE html>
<html lang="en">
<?PHP
//includes the LoCa File (Change '../src/loca.php' to the location of the loca file)
require_once('../src/loca.php');

//Loads the languages from the directory (Change './localization/' to the directory of your localization files)
LoadLanguages('./localization/',true);

//Sets the Language to the Users language if logged in (use $_SESSION['ulang'] with an ISO-2 language to set the language)
SetUserLanguage();


?>
<head>
  <title>LoCa Test</title>
</head>

<body>
<?PHP
echo Trans("test");
?>
</body>
</html>
