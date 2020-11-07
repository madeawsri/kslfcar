<?PHP
class HTML {
    protected $content;
    protected $tagname;
    protected $attrib;
    public function __construct($content,$attrib = array()){
         $this->content = $content;
         $this->attrib = $attrib;
    }
    public function getTag(){
        return '<'.$this->tagname.$this->getAttrib().'>'.$this->content.'</'.$this->tagname.'>';
    }
    public function getAttrib(){
        $attr = '';
        if(count($this->attrib))
        foreach($this->attrib as $k=>$v){
            $attr .= ' '.$k.'="'.$v.'"';
        }
        return $attr;
    }
    public function __toString(){
        return $this->getTag();
    }
}