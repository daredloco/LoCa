package ch.daredloco.helpers;

public class LoCaException extends Exception {
    private static final long serialVersionUID = 1L;
    
    public LoCaException()
    {
        super("Unhandled localization exception!");
    }

    public LoCaException(String message)
    {
        super(message);
    }
}
