## RoWa.LoCa.cs

### Usage:

#### Initialize files
##### Simple:
```cs
RoWa.LoCa.Init(LOCALIZATION_FILES_DIRECTORY);

RoWa.LoCa.Init("/localization");
```

##### With default language:
```cs
RoWa.LoCa.Init(LOCALIZATION_FILES_DIRECTORY,DEFAULT_LANGUAGE);

RoWa.LoCa.Init("/localization","en");
```

##### With extension:
```cs
RoWa.LoCa.Init(LOCALIZATION_FILES_DIRECTORY,DEFAULT_LANGUAGE,EXTENSION);
RoWa.LoCa.Init("/localization","en",".txt");
```

#### Translate
```cs
RoWa.LoCa.Trans(KEY);

RoWa.LoCa.Trans("word");

RoWa.LoCa.Translate("word");
```

#### Set languages
##### Default:
```cs
SetDefault(KEY);
SetDefault("en");
SetDefault(LANGUAGE_OBJECT);
```

##### User:
```cs
SetLanguage(KEY);
SetLanguage("en");
SetLanguage(LANGUAGE_OBJECT);
```

#### Handle placeholders
LanguageFile:
```
test1=This is a {TEST}
test2=You can set different {KEY1} with a {KEY2}
test3=You can also set unlimited {KEY1} with the new {KEY2}
```

Code:
```cs
KeyValuePair<string,string> kvp = new KeyValuePair<string,string>("{TEST}","Test");
RoWa.Xamarin.LoCa.Trans("test1",kvp); //output: This is a Test

Dictionary<string,string> dict = new Dictionary<string,string>();
dict.add("{KEY1}","keys");
dict.add("{KEY2}","dictionary");
RoWa.Xamarin.LoCa.Trans("test2",dict); //output: You can set different keys with a dictionary

KeyValuePair<string,string> vp1 = new KeyValuePair<string,string>("{KEY1}","KeyValuePairs");
KeyValuePair<string,string> vp2 = new KeyValuePair<string,string>("{KEY2}","system");
RoWa.Xamarin.LoCa.Trans("test3"),vp1,vp2); //output: You can slo set unlimited KeyValuePairs with the new system
```
