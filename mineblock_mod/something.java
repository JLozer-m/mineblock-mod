public class something {

    private String something;
    private int nm;

    public something(String something, int nm) {
        this.something = something;
        this.nm = nm;
    }
    public String getSomething() {
        return something;
    }

    public int getNm() {
        return nm;
    }
    @Override
    public String toString() {
            return super.toString();
    }
    public static void main(String[] args) {
        something obj = new something("example", 42);
        obj.getSomething();
        obj.getNm();
        System.out.println(obj.toString());
    }
}