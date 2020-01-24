# LoCa
 Localization Script for PHP

## Requirements
* >=PHP 5.4
* Sessions enabled

## Usage
1) Add the loca.php to your code
```php
include(PATH_TO_LOCA);
```

2) Load the localization files from a directory
```php
LoadLanguages(DIRECTORY_OF_THE_FILES);
```

3) Set the language to the browser language if available
```php
SetUserLanguage();
```

4) Translate a word
```php
Trans("key");
Translate("key");
```

## Advanced Usage
* Change the language manually
```php
SetLanguage(LANGUAGE_KEY);
```

* Get the language object saved inside the $_SESSION["language"]
```php
GetLanguage();
```

## Language file example
```
language_key=en
language_english=English
language_local=English

test=It works!
#Add more words by adding key=word
```
