<?php
/**
* @package      VipQuotes
* @subpackage   Libraries
* @author       Todor Iliev
* @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
* @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

defined('JPATH_PLATFORM') or die;

/**
 * This is a base class for quotes statistics.
 */
abstract class VipQuotesStatisticsAuthors {
    
    /**
     * Database driver
     * 
     * @var JDatabaseMySQLi
     */
    protected $db;
    
    /**
     * Initialize the object.
     * 
     * @param JDatabase   Database Driver
     */
    public function __construct(JDatabase $db) {
        $this->db = $db;
    }

    protected function getQuery() {
        
        $query = $this->db->getQuery(true);
        
        $query->select("a.id, a.alias, a.name, a.image, a.thumb, a.bio, a.copyright, a.hits");
        $query->select($query->concatenate(array("a.id", "a.alias"), "-") . " AS slug");
        $query->from($this->db->quoteName("#__vq_authors", "a"));
        
        return $query;
        
    }
    
}