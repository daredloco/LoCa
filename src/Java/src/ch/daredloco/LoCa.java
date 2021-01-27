package ch.daredloco;

import java.io.*;
import java.net.URI;
import java.nio.charset.Charset;
import java.nio.file.*;
import java.util.*;

import ch.daredloco.helpers.LoCaException;

public class LoCa {

    /*
    * TODO:
    * Make errorhandling (if language not exists for example or if no language exists at all)
    */
    private Language language;
    private Language defaultLanguage;
    private HashMap<String, Language> languages;

    public LoCa(String location) throws LoCaException
    {
        loadLanguages(location, "en");
    }

    public LoCa(String location, String defaultLanguage) throws LoCaException
    {
        loadLanguages(location, defaultLanguage);
    }

    //private methods
    /**
     * Loads the languages from the location
     * @param location The directory the language files are in
     * @param defaultLanguage The default language key, if empty it won't set a default language
     */
    private void loadLanguages(String location, String defaultLanguage) throws LoCaException
    {
        languages = new HashMap<String, Language>();

        File folder = new File(location);
        var folderFiles = folder.listFiles();
        if(folderFiles == null)
        {
            throw new LoCaException("Couldn't find any files inside folder \"" + location + "\"");
        }
        for(File file : folderFiles)
        {
            if(file.getAbsolutePath().endsWith(".txt"))
            {
                Language newLanguage = new Language(file);
                if(newLanguage.isValid())
                {
                    languages.put(newLanguage.key, newLanguage);
                }
            }
        }
        if(defaultLanguage != null)
        {
            //Set default language
            this.defaultLanguage = getLanguage(defaultLanguage);
            this.language = this.defaultLanguage;
        }
    }

    //Getter methods
    /**
     * Returns the language with the key 'k'
     * @param k The key of the language, if null returns the actually used language
     * @return Language object or null if couldn't be found
     */
    public Language getLanguage(String k)
    {
        if(k == null)
        {
            return language;
        }
        if(languages.containsKey(k))
        {
            return languages.get(k);
        }
        return null;
    }

    //Setter methods
    /**
     * Sets the language to use
     * @param k The key of the language
     */
    public void setLanguage(String k) throws LoCaException
    {
        Language lang = getLanguage(k);
        if(lang != null)
        {
            this.language = lang;
            return;
        }
        throw new LoCaException("Can't set language '" + k + "' because it couldn't be found!");
    }

    /**
     * Sets the default language
     * @param k The key of the default language
     */
    public void setDefaultLanguage(String k) throws LoCaException
    {
        Language lang = getLanguage(k);
        if(lang != null)
        {
            this.defaultLanguage = lang;
        }
        throw new LoCaException("Can't set default language '" + k + "' because it couldn't be found!");
    }

    //Translation methods
    /**
     * Translates key 'k'
     * @param k The key to translate
     * @return The translated value, the translated value from the default language or a placeholder if it couldn't be found
     */
    public String translate(String k)
    {
        String translated = language.translate(k);
        if(translated == null && defaultLanguage == null)
        {
            return "{" + k + "}";
        }
        else if(translated == null && defaultLanguage != null)
        {
            String translatedDefault = defaultLanguage.translate(k);
            if(translatedDefault == null)
            {
                return "{" + k + "}";
            }
            return translatedDefault;
        }
        else{
            return translated;
        }
    }

    /**
     * Translates key 'k' with placeholder
     * @param k The key to translate
     * @param placeHolder The placeholder KeyValuePair
     * @return The translated value, the translated value from the default language or a placeholder if it couldn't be found
     */
    public String translate(String k, ch.daredloco.helpers.KeyValuePair<String, String> placeHolder)
    {
        String translated = translate(k);
        translated = translated.replace(placeHolder.key, placeHolder.value);   
        return translated;
    }

    /**
     * Translates key 'k' with placeholders
     * @param k The key to translate
     * @param placeHolders The placeholder KeyValuePair
     * @return The translated value, the translated value from the default language or a placeholder if it couldn't be found
     */
    public String translate(String k, List<ch.daredloco.helpers.KeyValuePair<String, String>> placeHolders)
    {
        String translated = translate(k);
        for (ch.daredloco.helpers.KeyValuePair<String,String> kvp : placeHolders) {
            translated = translated.replace(kvp.key, kvp.value);   
        }
        return translated;
    }

    public class Language
    {
        private String key;
        private String english;
        private String local;
        private String author;
        private String version;
        private HashMap<String, String> dict;

        //Constructor
        /**
         * Creates a Language object out of the content of a file
         * @param file The file containing the language data
         */
        public Language(File file) throws LoCaException
        {
            dict = new HashMap<String, String>();

            try{
                URI fUrl = file.toURI();
                for(String fline : Files.readAllLines(Paths.get(fUrl), Charset.defaultCharset()))
                {
                    if(!fline.startsWith("#") && fline.contains("="))
                    {
                        if(fline.startsWith("language_key="))
                        {
                            this.key = fline.replace("language_key=","");
                        }else if(fline.startsWith("language_english=")){                          
                            this.english = fline.replace("language_english=","");
                        }else if(fline.startsWith("language_local=")){                           
                            this.local = fline.replace("language_local=","");
                        }else if(fline.startsWith("language_author=")){                           
                            this.author = fline.replace("language_author=","");
                        }else if(fline.startsWith("language_version")){ 
                            this.version = fline.replace("language_version=","");
                        }else{
                            String key = fline.split("=")[0];
                            String value = fline.replace(key + "=", "");
                            dict.put(key,value);
                        }
                    }
                }
            }catch(Exception ex)
            {
                throw new LoCaException(ex.getMessage());
            }
        }

        //Getter Methods
        /**
         * Returns the key of the language
         * @return key as String
         */
        public String getKey()
        {
            return key;
        }

        /**
         * Returns the english name of the language
         * @return english name as String
         */
        public String getEnglish()
        {
            return english;
        }

        /**
         * Returns the local name of the language
         * @return local name as String
         */
        public String getLocal()
        {
            return local;
        }

        /**
         * Returns the author of the localization
         * @return author as String
         */
        public String getAuthor()
        {
            return author;
        }

        /**
         * Returns the localization version as string
         * @return version as String
         */
        public String getVersion()
        {
            return version;
        }

        //Translation method
        /**
         * Translates key 'k'
         * @param k The key to translate
         * @return The translated value or null if it couldn't be found
         */
        public String translate(String k)
        {
            if(dict.containsKey(k))
            {
                return dict.get(k);
            }
            return null;
        }

        //Validation check
        /**
         * Checks if the language is valid or not
         * @return true if language is valid, false if its not
         */
        public boolean isValid()
        {
            if(key == null || english == null || local == null || author == null || version == null || dict == null)
            {
                //Localization informations are invalid
                return false;
            }
            else if(dict.size() < 1)
            {
                //Dictionary has no content
                return false;
            }
            return true;
        }
    }
}

