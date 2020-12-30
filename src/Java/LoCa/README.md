
## Requirements

JAVA 1.7

## Usage

Initialize
```java
daredloco.LoCa loca = new daredloco.LoCa(new File("localizationtest").getAbsolutePath()); //reads all txt files inside the folder localizationtest with 'en' as default language
daredloco.LoCa loca = new daredloco.LoCa(new File("localizationtest").getAbsolutePath(), "de"); //reads all txt files inside the folder localizationtest with 'de' as default language
```

Set languages
```java
loca.setLanguage("de"); //Sets the selected language to 'de'
loca.setDefaultLanguage("de"); //Sets the default language to 'de'
```

Translate
```java
loca.translate("test"); //Translates the key "test"
loca.translate("test2", new KeyValuePair<String,String>("{placeholder}","working as well!")); //Translates the key "test2" with the placeholder {placeholder}

//Translates the key "test3" with an ArrayList of placeholders
ArrayList<KeyValuePair<String,String>> alist = new ArrayList<KeyValuePair<String,String>>();
alist.add(new KeyValuePair<String,String>("{p1}", "It's"));
alist.add(new KeyValuePair<String,String>("{p2}", "working!"));
loca.translate("test3", alist);
```

For informations about the format of the localization file, check out the [this file](../LoCa/localizationtest/english.txt)!
