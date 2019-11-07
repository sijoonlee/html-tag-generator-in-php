<!--  http://localhost/LeeSijoonAsst1/Asst1Main.php -->
<?php   

require_once("Asst1Include.php");

/* INTERFACE AND CLASSES

 Tried to implement "Composite" design pattern 
 
 Renderable
 - Interface
 - abstract render() method

 Interface Renderable <- Class SelfClosingTag
 Interface Renderable <- Class ClosingTag
 Interface Renderable <- Class Text

 Class Text
 - represent pure text (without html)
 - render() method
 
 Class SelfClosingTag
 - represent self-closing html tags (e.g. input)
 - render() method to generate html tags
 
 Class Non-selfClosingTag
 - represent non-self-closing html tags (e.g. div)
 - have the array to keep nested html tags, which are Renderable with render() method 
 - provide function addElement(Renderable obj);
   which means that classes are able to form tree structure.
 - render() method to generate HTML including nested html tags
 
 
 Inheritance
 SelfClosingTag <- Img, Input
 NonSelfClosingTag <- Div, Form, Button, Label, and so on 

 Usage
 1. Create image button
      $img_button = new Button( ["class"=>"classB"] , [new Img(["src"=>"home.png"])]);
 2. Create text button
      $text_button = new Button( ["class"=>"classB"] , [new Text("save")]);
 3. Create div and have two buttons above nested
      $div_data_pair = new Div( ["class"=>"datapair"], [$img_button, $text_button]);
 4. Render div
      echo $div_data_pair->render();
*/

interface Renderable
{
    public function render(): string;
}

class Text implements Renderable
{
    private $text;
    
    public function __construct($text)
    {
        $this->text = $text;    
    }
    
    public function render(): string
    {
        return $this->text;
    }
}
class SelfClosingTag implements Renderable
{
    private $tag;
    private $attributes;

    public function __construct($tag, $attributes)
    {
        $this->tag = $tag;
        $this->attributes = $attributes;
    }
    
    public function render(): string
    {
        $element = "<$this->tag ";
        if($this->attributes !== null)
        {
            foreach ( $this->attributes as $key => $value)            
                $element .= "$key=\"$value\" ";
        }
            
        $element .= "/>";
        return $element;
    }
}

class NonSelfClosingTag implements Renderable
{
    private $tag;
    private $attributes;
    private $elements;

    public function __construct($tag, $attributes, $elements)
    {
        $this->tag = $tag;
        $this->attributes = $attributes;
        if($elements !== null)
            foreach ( $elements as $element)
                $this->addElement($element);
    }
    
    public function addElement(Renderable $element)
    {
        $this->elements[] = $element;
    }
    
    public function render(): string
    {
        $element = "<$this->tag ";
        if($this->attributes !== null)
            foreach ( $this->attributes as $key => $value)            
                $element .= "$key=\"$value\" ";
        $element .= ">";
        if( $this->elements !== null)
            foreach( $this->elements as $nested )
                $element .= $nested->render();
        $element .= "</$this->tag>";
        
        return $element;
    }
}

class Div extends NonSelfClosingTag
{
    public function __construct($attributes, $elements)
    {
        parent::__construct("div", $attributes, $elements);
    }    
}

class Form extends NonSelfClosingTag
{
    public function __construct($attributes, $elements)
    {
        parent::__construct("form", $attributes, $elements);
    }    
}

class Button extends NonSelfClosingTag
{
    public function __construct($attributes, $elements)
    {
        parent::__construct("button", $attributes, $elements);
    }
}

class Label extends NonSelfClosingTag
{
    public function __construct($attributes, $elements)
    {
        parent::__construct("label", $attributes, $elements);
    }
}

class Table extends NonSelfClosingTag
{
    public function __construct($attributes, $elements)
    {
        parent::__construct("table", $attributes, $elements);
    }
}

class Tr extends NonSelfClosingTag
{
    public function __construct($attributes, $elements)
    {
        parent::__construct("tr", $attributes, $elements);
    }
}

class Th extends NonSelfClosingTag
{
    public function __construct($attributes, $elements)
    {
        parent::__construct("th", $attributes, $elements);
    }
}

class Td extends NonSelfClosingTag
{
    public function __construct($attributes, $elements)
    {
        parent::__construct("td", $attributes, $elements);
    }
}

class Datalist extends NonSelfClosingTag
{
    public function __construct($attributes, $elements)
    {
        parent::__construct("datalist", $attributes, $elements);
    }
}

class Option extends NonSelfClosingTag
{
    public function __construct($attributes, $elements)
    {
        parent::__construct("option", $attributes, $elements);
    }
}

class Img extends SelfClosingTag
{
    public function __construct($attributes)
    {
        parent::__construct("img", $attributes);
    }
}

class Input extends SelfClosingTag
{
    public function __construct($attributes)
    {
        parent::__construct("input", $attributes);
    }
}


?>