<?php

/**
 * Files
 *
 * @author jsmith
 * @version
 */
class Media_Model_Files extends Media_Model_MediaCore
{
    const MEDIA_BASE_PATH = '/modules/media/images';
    const MEDIA_BASE_THUMB_PATH = '/modules/_thumbs/Media';

    /**
     * The default table name
     */
    protected $_name = 'mediafiles';

    protected $_primary = 'fileId';

    protected $_sequence = true;
    
    protected $_rowClass = 'Media_Model_Row_File';
    
    protected $_rowsetClass = 'Media_Model_Rowset_Files';

    public $album;
    
    public $pages;
    
    // protected $_rowClass = 'Media_Model_Row_File';
    public function init ()
    {
        parent::init();   
        $this->album = new Media_Model_Albums();
        //$this->pages = new Pages_Model_Pages();
    }
    public function fetchShowFirst($id) {
        $query = $this->select()
                        ->setIntegrityCheck(false)
                        ->from($this->_name)
                        ->join('mediaalbums', 'mediaalbums.albumId = mediafiles.albumId', array('albumId', 'albumName'))
                        ->where('mediafiles.fileId = ?', $id)
                        ;
        return $this->fetchRow($query);
        
    }
    public function fetchToAddDesc ($fileName, $albumName)
    {
        $albumId = $this->album->fetchIdByAlbumName($albumName);
        // Zend_Debug::dump($albumId);
        if (null == $albumId) {
            return null;
        }
        $query = $this->select()
            ->from($this->_name, 
                array(
                        'fileId',
                        'fileName',
                        'albumId',
                        'title',
                        'alt',
                        'description'
                ))
            ->where('fileName = ?', $fileName)
            ->where('albumId = ?', $albumId);
        return $this->fetchRow($query);
    }

    public function fetchByFileName ($fileName)
    {
        $query = $this->select()
            ->from($this->_name, 
                array(
                        'fileId',
                        'fileName',
                        'title',
                        'alt',
                        'description',
                        'timestamp'
                ))
            ->where('fileName = ?', $fileName);
        return $this->fetchRow($query);
    }

    public function fetchForCKFinderDeleteFile ($fileName, $albumName)
    {
        if ($albumName !== null) {
            $albumId = $this->album->fetchIdByAlbumName($albumName);
        }
        $query = $this->select()
            ->from($this->_name, array(
                'fileId',
                'albumId',
                'fileName'
        ))
            ->where('albumId = ?', $albumId)
            ->where('fileName = ?', $fileName);
        return $this->fetchRow($query);
    }

    public function fetchFilesForCkFinderDeleteAlbum ($albumId = null, $albumName = null)
    {
        if ($albumId == null && $albumName !== null) {
            $albumId = $this->album->fetchIdByAlbumName($albumName);
        }
        $query = $this->select()
            ->from($this->_name, array(
                'fileId',
                'albumId',
                'fileName'
        ))
            ->where('albumId = ?', $albumId);
        return $this->fetchAll($query);
    }

    public function fetchPage ($perPage = 6, $page, $albumId)
    {
        $query = $this->select()->where('albumId = ?', $albumId);
        $paginator = new Zend_Paginator(
                new Zend_Paginator_Adapter_DbTableSelect($query));
        $paginator->setItemCountPerPage($perPage);
        $paginator->setCurrentPageNumber($page);
        return $paginator;
    }

    public function fetchAllByAlbumName ($albumName = 'Media')
    {
        if ($albumName !== null) {
            $albumId = $this->album->fetchIdByAlbumName($albumName);
        }
        $query = $this->select()
            ->from($this->_name)
            ->where('albumId = ?', $albumId);
        return $this->fetchAll($query);
    }
    public function fetchRecentImages()
    {
        $date = Zend_Date::now();
        $limit = isset($this->settings->showRecentCount) ? $this->settings->showRecentCount : 4;
        $order = (isset($this->settings->showMostRecentFirst) && ( $this->settings->showMostRecentFirst === true )) ? 'DESC' : 'ASC';
        $pageType = 'media';
        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->distinct(true)
                    ->from($this->_name, array('albumId', 'fileName', 'title', 'alt', 'description', 'timestamp'))
                    ->join('mediaAlbums', 'mediaAlbums.albumId = mediaFiles.albumId', array('albumName', 'serverPath', 'role'))
                    ->limit($limit)
                    ->where('mediaFiles.albumId > ?', 0)
                    ->order("fileId $order");
        
        if($this->settings->showRecentByDate) {
            if(!isset($this->settings->showRecentNumDays) && !$this->settings->showRecentNumDays > 0)
            {
                break 2;
            }
            $cutOffDay = $date->sub($this->settings->showRecentNumDays, Zend_Date::DAY);
            $sql = $this->select()
                        ->setIntegrityCheck(false)
                        ->distinct(true)
                        ->from($this->_name, array('albumId', 'fileName', 'title', 'alt', 'description', 'timestamp'))
                        ->join('mediaalbums', 'mediaalbums.albumId = mediafiles.albumId', array('albumName', 'serverPath', 'role'))
                        ->where("$this->_name.timestamp >= ?", $cutOffDay->getTimestamp())
                        ->where('mediafiles.albumId > ?', 0)
                        ->order("$this->_name.timestamp $order")
                        ->limit($limit);
            $result = $this->fetchAll($sql);
            if($result !== null) {
                return $result;
            }
            else {
                return $this->fetchAll($sql);
            }
        }
        else {
            return $this->fetchAll($sql);
        }
        return null;
    }
}