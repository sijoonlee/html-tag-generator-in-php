
## HTML tags are generated via Objects

- Tried to implement "Composite" design pattern 

- Basic information for the structure of HTML is stored in json file
    - the information is parsed and processed into HTML Tag objects

- Written in PHP

- Reference: https://designpatternsphp.readthedocs.io/en/latest/Structural/Composite/README.html

- Details below
--------------------------------------------------------------------
 Renderable.php
--------------------------------------------------------------------
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

