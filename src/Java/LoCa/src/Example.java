import java.io.File;
import java.util.*;
import daredloco.helpers.KeyValuePair;

public class Example {
    public static void main(String[] args) throws Exception {
        daredloco.LoCa loca = new daredloco.LoCa(new File("localizationtest").getAbsolutePath());
        System.out.println(loca.translate("test"));
        System.out.println(loca.translate("test2", new KeyValuePair<String,String>("{placeholder}","working as well!")));
        ArrayList<KeyValuePair<String,String>> alist = new ArrayList<KeyValuePair<String,String>>();
        alist.add(new KeyValuePair<String,String>("{p1}", "It's"));
        alist.add(new KeyValuePair<String,String>("{p2}", "working!"));
        System.out.println(loca.translate("test3", alist));
    }
}
