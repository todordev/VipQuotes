<?php
/**
 * @package      VipQuotes
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * VipQuotes is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');

class VipQuotesModelImport extends JModelForm {
    
    /**
     * Method to get the record form.
     *
     * @param   array   $data       An optional array of data for the form to interogate.
     * @param   boolean $loadData   True if the form is to load its own data (default case), false if not.
     * @return  JForm   A JForm object on success, false on failure
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true){
        
        // Get the form.
        $form = $this->loadForm($this->option.'.import', 'import', array('control' => 'jform', 'load_data' => $loadData));
        if(empty($form)){
            return false;
        }
        
        return $form;
    }
    
    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed   The data for the form.
     * @since   1.6
     */
    protected function loadFormData(){
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState($this->option.'.edit.import.data', array());
        return $data;
    }
    
    public function extractFile($file, $destFolder) {
        
        // extract type
        $zipAdapter   = JArchive::getAdapter('zip'); 
        $zipAdapter->extract($file, $destFolder);
        
        $dir          = new DirectoryIterator($destFolder);
        
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                $filePath     = $destFolder .DIRECTORY_SEPARATOR. JFile::makeSafe($fileinfo->getFilename());
            }
        }
            
        return $filePath;
    }
    
    /**
     * 
     * Import locations from TXT or XML file.
     * The TXT file comes from geodata.org
     * The XML file is generated by the current extension ( VipQuotes )
     * 
     * @param string    $file 	 	A path to file
     * @param bool  	$resteId	Reset existing IDs with new ones.
     */
    public function importQuotes($file, $resteId = false) {
        
        $ext      = JString::strtolower( JFile::getExt($file) );
        
        switch($ext) {
            case "xml":
                $this->importQuotesXml($file, $resteId);
                break;
            default: // CSV ( Joomla! 1.5 )
                $this->importQuotesCsv($file, $resteId);
                break;
        }
    }
    
    /**
     * 
     * Import data from CSV file ( Joomla! 1.5 )
     * @param string $file
     * @param bool $resteId
     */
    protected function importQuotesCsv($file, $resteId) {
        
        // Load file
        $handle =   fopen($file, "r");
        if( !is_resource($handle) ) {
            throw new RuntimeException(JText::sprintf("COM_VIPQUOTES_ERROR_FILE_CANT_BE_LOADED", $file));
        }
        
        // Help fgetcsv() to read in UTF8
        $data = array();
        setlocale( LC_ALL, 'en_US.UTF-8' );
        while ( false !== ($row = fgetcsv($handle, 1024, ";"))) {
                $data[] =  $row;
        }
        fclose($handle);
        
        if(!empty($data)) {
            
            $authors = VipQuotesHelper::getAuthors();
            
            $items  = array();
            $db     = JFactory::getDbo();
            $userId = JFactory::getUser()->id;
            
            for( $i=0, $max = count($data); $i < $max; $i++ ) {
                
                $quote  = JString::trim( JArrayHelper::getValue($data[$i], 0) );
                if(empty($quote)) {
                    continue;
                }
                
                $author   = JString::trim( JArrayHelper::getValue($data[$i], 1) );
                $authorId = array_search($author, $authors);
                
                if(!$authorId){
                    $authorTable        = $this->getTable("Author", "VipQuotesTable");
                    $authorTable->name  = $author;
                    $authorTable->alias = JApplication::stringURLSafe($author);
                    
                    $authorTable->store();
                    $authorId = $authorTable->id;
                    
                    // Add the new author to the list with others
                    $authors[$authorId] = $author;
                }
                
                $table            = $this->getTable("Quote", "VipQuotesTable");
                $table->quote     = $quote;
                $table->user_id   = (int)$userId;
                $table->author_id = (int)$authorId;
                $table->store();
                
            }
            
            unset($items);
            unset($data);
        }
        
    }
    
    /**
     * 
     * Import data from XML file 
     * @param string $file
     * @param bool   $resteId
     */
    protected function importQuotesXml($file, $resteId) {
        
        $xmlstr  = file_get_contents($file);
        
        if(!empty($xmlstr)) {
            
            $content = new SimpleXMLElement($xmlstr);
            
            $items  = array();
            $db     = JFactory::getDbo();
            $userId = JFactory::getUser()->id;
            
            $items = $content->quotes->children();
            
            foreach($items as $item) {
                
                $table = $this->getTable("Quote", "VipQuotesTable");
                
                $table->quote       = (string)$item->quote;
                $table->created     = (string)$item->created;
                $table->hits        = (int)$item->hits;
                $table->published   = (int)$item->published;
                $table->ordering    = (int)$item->ordering;
                $table->user_id     = (int)$userId;
                
                $table->store();
                
            }
            
            unset($content);
            
        }
        
    }
    
    public function validateFileType($file) {
        
        // Get file extension
        $ext          = JString::strtolower( JFile::getExt($file) );
        
        // Get MIME type of the file
        $finfo        = finfo_open(FILEINFO_MIME_TYPE);
        $fileMimeType = finfo_file($finfo, $file);
        finfo_close($finfo);
        
        $allowedExt   = array("csv", "xml");
        if(!in_array($ext, $allowedExt)) {
            throw new RuntimeException(JText::sprintf("COM_VIPQUOTES_ERROR_INVALID_MIME_TYPE", "XML, CSV"));
        }
        
        // Validate CSV
        if( (strcmp("csv", $ext) == 0) AND (strcmp("text/plain", $fileMimeType) != 0) ) {
            throw new RuntimeException(JText::sprintf("COM_VIPQUOTES_ERROR_INVALID_MIME_TYPE", "XML, CSV"));
        }
        
        // Validate XML
        if((strcmp("xml", $ext) == 0) AND (strcmp("application/xml", $fileMimeType) != 0)) {
            throw new RuntimeException(JText::sprintf("COM_VIPQUOTES_ERROR_INVALID_MIME_TYPE", "XML, CSV"));
        }
        
    }
}