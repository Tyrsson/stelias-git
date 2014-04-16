<?php
/**
 * News
 * 
 * @author jsmith
 * @version 
 */
require_once 'Zend/Db/Table/Abstract.php';
//require_once 'Zend/Acl/Resource/Interface';
class System_Model_News extends Zend_Db_Table_Abstract 
{
    /**
     * The default table name 
     * 
     * Zend_Db_Table_Abstract
     */
    protected $_name = 'news';
        
    protected $_referenceMap = array(
        'NewsCreatedBy' => array(
            'columns'           => 'created_by',
            'refTableClass'     => 'System_Model_Users',
            'refColumns'        => array('userId', 'username')
        ),
    );
       
    public function getOnePage($page = 1)
    {
        $query = $this->select()
                      ->from('news', array('news_id', 'news_name', 'date_added', 'date_updated', 'created_by', 'news_content', 'status'))
                      ->where('status = ?', 1);
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($query));
        $paginator->setItemCountPerPage(2);
        $paginator->setCurrentPageNumber($page);
        return $paginator;
    }
    public function getOneById ($id)
    {
        $this->_id = $id;
        $query = $this->select()
                      ->from('news', array('news_id', 'news_name', 'date_added', 'date_updated', 'created_by', 'news_content', 'status'))
                      ->where('news_id = ?', $this->_id);
                      
        $result = $this->fetchAll($query);
        return $result;
    }
    public function getLatestNews ()
    {
        $id = 1;
        $start = 0;
        $limit = 1;
        $joinTable = 'users';
        $joinCond = ''; 
        $offset = 0;
        $query = $this->select()
                      ->setIntegrityCheck(false)
                      ->from('news', array('news_id', 'news_name', 'date_added', 'date_updated', 'created_by', 'news_content', 'status'))
                      ->join('users', 'users.userId = news.created_by', 'username')
                      ->where('news_id > ?', $start)
                      ->order('news_id DESC')
                      ->limit($limit,  $offset);
                      
        $result = $this->fetchAll($query);
            
        return $result;
    }
    public function toArray()
    {
        return (array)$this->_data;
    }

}
