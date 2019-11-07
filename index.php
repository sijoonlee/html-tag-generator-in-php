<!--  http://localhost/LeeSijoonAsst1/Asst1Main.php -->
<?php   

require_once("Renderable.php");

function WriteHeaders($Heading = "Welcome", $TitleBar = ""){
    echo "
        <!doctype html>
        <html lang = \"en\">
        <head>
            <meta charset = \"UTF-8\">
            <title> $TitleBar </title>
            <link rel =\"stylesheet\" type = \"text/css\" href=\"Asst1Style.css\"/>
        </head>
        <body>
            <h1 id=\"heading\">$Heading</h1>
    ";
}

function WriteFooters(){
    echo "<footer>Contact: shijoonlee@gmail.com<footer>"
    echo "</body></html>";
}


// Display message without form structure
function DisplayMessageOnly($message="Assignment 1 by Sijoon Lee")
{   
    $div = new Div(["class"=>"sub_form"], null);
    $div->AddElement(new Div(null, null));
    $div->AddElement(ResultMessage($message, "result_message"));
    $div->AddElement(new Div(null, null));
    echo $div->render();
}

// Generate small message box, which fits in form structure
function ResultMessage($message, $class)
{
    return new Div(["class"=>"$class"], [new Text($message)]);
}

// load json file and parse
function LoadFormTemplate($filename)
{
    $strJsonFileContents = file_get_contents($filename);
    $form_template = json_decode($strJsonFileContents, true, 50);
    return ($form_template);
}

// Generate html tag sub-tree structure recursively
// $injection is the array of html elements
function DisplayFormRecursive(&$parent, $children_info, &$injection) 
{ 
    foreach($children_info as $key => $value)
    {   
        $injection_length = $injection === null ? 0 : count($injection);
        $injection_index = 0;
        
        if($key === "injection" && $injection !== null)
        {   
            if($injection_index < $injection_length)
            {
                $parent->addElement($injection[$injection_index]);
                $injection_index = $injection_index + 1;
            }
            else
            {
                $parent->addElement($injection[0]);
                $injection_index = 1;
            }
                
        }
        elseif($key === "text")
        {
            $parent->addElement(new Text($value));
        }
        elseif($key === "non-self-closing")
        {
            $dom = new NonSelfClosingTag($value["tag"], $value["attr"], null);
            if(isset($value["elements"]))
            {
                foreach($value["elements"] as $elem )
                    DisplayFormRecursive($dom, $elem, $injection);
            }
            $parent->addElement($dom);
        }
        elseif($key === "self-closing")
        {
            $dom = new SelfClosingTag($value["tag"], $value["attr"]);
            $parent->addElement($dom);
        }
    }
}

// Generate form structure from form template
function DisplayForm($form_file="home_form.json", $action="?", $method="post", 
                     $class=null, $id=null, $injection = null)
{
    $form_template = LoadFormTemplate($form_file);
    
    $form = new Form(["action"=>"$action", "method"=>"$method", 
                      "class"=>"$class", "id"=>"$id"], null);

    foreach( $form_template as $elem )
    {
        DisplayFormRecursive($form, $elem, $injection);
    }
    echo $form->render();
}


// Retrive all data from the table and show
function ShowCalendar()
{
    $table = new Table(null, null);
    
    $headingRow = new Tr(null, null);
    $headingRow->AddElement(new Th(null, [new Text("Name")]));
    $headingRow->AddElement(new Th(null, [new Text("Github")]));
    $headingRow->AddElement(new Th(null, [new Text("Email")]));

    $table->AddElement($headingRow);

    $newRow = new Tr(null, null);
    $newRow->AddElement(new Td(null, [new Text("Sijoon Lee")]));
    $newRow->AddElement(new Td(null, [new Text("github/sijoonlee")]));
    $newRow->AddElement(new Td(null, [new Text("shijoonlee@gmail.com")]));

    $table->AddElement($newRow);

    $injection = new Div(null, [$table]);
    
    DisplayForm("form_json/test.json", "?", "post", "sub_form", "show_record_form", [$injection]);    
    
}

WriteHeaders("HTML Creator test", "HTML Creator test");

ShowCalendar();

WriteFooters();


?>