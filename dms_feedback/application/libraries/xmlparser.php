 <?php
/**
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU LesserGeneral Public License as published
    by the Free Software Foundation; either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    For support, please visit http://www.criticaldevelopment.net/xml/
*/

/**
 * XML Parser Class (php5)
 * 
 * Parses an XML document into an object structure much like the SimpleXML extension.
 *
 * @author Adam A. Flynn <adamaflynn@criticaldevelopment.net>
 * @copyright Copyright (c) 2005-2007, Adam A. Flynn
 *
 * @version 1.3.0
 */
include("application/libraries/xmltag.php"); 

class XMLParser 
{
    /**
     * The XML parser
     *
     * @var resource
     */
    private $parser;

    /**
    * The XML document
    *
    * @var string
    */
    private $xml;

    /**
    * Document tag
    *
    * @var object
    */
    public $document;

    /**
    * Current object depth
    *
    * @var array
    */
    private $stack;
    
    /**
     * Whether or not to replace dashes and colons in tag
     * names with underscores.
     * 
     * @var bool
     */
    private $cleanTagNames;

    
    /**
     * Constructor. Loads XML document.
     *
     * @param string $xml The string of the XML document
     * @return XMLParser
     */
    function __construct($xml = '', $cleanTagNames = true)
    {
        //Load XML document
        $this->xml = $xml;

        //Set stack to an array
        $this->stack = array();
        
        //Set whether or not to clean tag names
        $this->cleanTagNames = $cleanTagNames;
    }

    /**
     * Initiates and runs PHP's XML parser
     */
    public function Parse()
    {
        //Create the parser resource
        $this->parser = xml_parser_create();
        
        //Set the handlers
        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, 'StartElement', 'EndElement');
        xml_set_character_data_handler($this->parser, 'CharacterData');

        //Error handling
        if (!xml_parse($this->parser, $this->xml))
            $this->HandleError(xml_get_error_code($this->parser), xml_get_current_line_number($this->parser), xml_get_current_column_number($this->parser));

        //Free the parser
        xml_parser_free($this->parser);
    }
    
    /**
     * Handles an XML parsing error
     *
     * @param int $code XML Error Code
     * @param int $line Line on which the error happened
     * @param int $col Column on which the error happened
     */
    private function HandleError($code, $line, $col)
    {
        trigger_error('XML Parsing Error at '.$line.':'.$col.'. Error '.$code.': '.xml_error_string($code));
    }

    
    /**
     * Gets the XML output of the PHP structure within $this->document
     *
     * @return string
     */
    public function GenerateXML()
    {
        return $this->document->GetXML();
    }

    /**
     * Gets the reference to the current direct parent
     *
     * @return object
     */
    private function GetStackLocation()
    {
        //Returns the reference to the current direct parent
        return end($this->stack);
    }

    /**
     * Handler function for the start of a tag
     *
     * @param resource $parser
     * @param string $name
     * @param array $attrs
     */
    private function StartElement($parser, $name, $attrs = array())
    {
        //Make the name of the tag lower case
        $name = strtolower($name);
        
        //Check to see if tag is root-level
        if (count($this->stack) == 0) 
        {
            //If so, set the document as the current tag
            $this->document = new XMLTag($name, $attrs);

            //And start out the stack with the document tag
            $this->stack = array(&$this->document);
        }
        //If it isn't root level, use the stack to find the parent
        else
        {
            //Get the reference to the current direct parent
            $parent = $this->GetStackLocation();
            
            $parent->AddChild($name, $attrs, count($this->stack), $this->cleanTagNames);

            //If the cleanTagName feature is on, clean the tag names
            if($this->cleanTagNames)
                $name = str_replace(array(':', '-'), '_', $name);

            //Update the stack
            $this->stack[] = end($parent->$name);        
        }
    }

    /**
     * Handler function for the end of a tag
     *
     * @param resource $parser
     * @param string $name
     */
    private function EndElement($parser, $name)
    {
        //Update stack by removing the end value from it as the parent
        array_pop($this->stack);
    }

    /**
     * Handler function for the character data within a tag
     *
     * @param resource $parser
     * @param string $data
     */
    private function CharacterData($parser, $data)
    {
        //Get the reference to the current parent object
        $tag = $this->GetStackLocation();

        //Assign data to it
        $tag->tagData .= trim($data);
    }
}
