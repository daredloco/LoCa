# LoCa
 Localization Script for PHP

## Requirements
* PHP 5.4+
* Sessions enabled

## Usage
1) Add the loca.php to your code
```php
include("loca.php");
```

2) Load the localization files from a directory
```php
LoadLanguages("./localization/);
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

## Using loca-json
The JSON version works a little bit different than the standard version.
Point 1-3 are the same, but to translate the word I'll give you an example here:

1) Create the JSON File
```json
{
    "Settings": [       
        {
            "key": "en",
            "english": "English",
            "local": "English",
            "author": "Roman Wanner",
            "version": "1.0"
        }
    ],

    "Page1": [
        {            
            "test": "[b]Singleline:[/b][n]Its working!",
            "bb": "[b]Mutliline with bbcodes:[/b][n][i]With[/i][n][u]bb[/u][n][url=https://www.rowa-digital.ch]codes[/url]!"
        }
    ]
}
```
The "Settings" part is necessary and needs to be added to every translation. After that comes the different pages (in this case "Page1").

2) To translate you have 2 choices:
```php
Trans("Page1_test"); //translates the "test" inside the "Page1" parent.
Trans("test","Page1"); //You should use this version, the second argument is the "page", the first is the "key".
```
This way you can use the same keys inside diferent pages and sort them easily

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

## Usable BBCodes
### Usage: [bbcode]innertext[/bbcode]
b = bold text [b]text[/b]

i = italic text [i]text[/i]

u = underlined text [u]text[/u]

size = sized text [size=20]text[/size]

color = colored text [color=red]text[/color]

center = centered text [center]text[/center]

quote = quoted text [quote=daredloco]text[/quote]

url = link [url=https://www.github.com]GitHub[/url]

img = picture [img]https://images-americanas.b2w.io/produtos/01/00/img/1339479/8/1339479807_1GG.jpg[/img]

p = paragraphed text [p]text[/p]


### Usage: text[bbcode]
n = new line

