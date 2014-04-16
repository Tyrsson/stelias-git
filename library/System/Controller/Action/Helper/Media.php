<?php
/**
 *
 * @author Joey
 * @version
 */
require_once 'Zend/Loader/PluginLoader.php';
require_once 'Zend/Controller/Action/Helper/Abstract.php';
/**
 * Media Action Helper
 *
 * @uses actionHelper System_Controller_Action_Helper
 */
class System_Controller_Action_Helper_Media extends Zend_Controller_Action_Helper_Abstract
{

    public $settings;
    /**
     *
     * @var Zend_Loader_PluginLoader
     */
    public $pluginLoader;
    /**
     * Constructor: initialize plugin loader
     *
     * @return void
     */
    protected $view;

    protected $_request;

    protected $albums;
    protected $files;
    protected $get;
    protected $albumName;
    public $page;
    protected $filter;
    protected $input;
    protected $action;
    protected $data;
    protected $albumCount;
    protected $fileCount;
    protected $template;
    public $albumPath;
    protected $isSubAlbum = false;
    public $thumbPath;
    public $parent;
    public $parents = array();
    public $hasParent = false;
    public $subAlbumsPerPage = 4; // temp value, will need to be changed

    public function preDispatch()
    {
        if (null === ($this->controller = $this->getActionController())) {
            return;
        }

        $this->renderer = $this->controller->getHelper('viewRenderer');

        $this->view = self::getView();
        $this->_request = $this->getRequest();
        $this->_response = $this->getResponse();
        $this->data = new stdClass();
        $this->view->slideShow = false;
        $this->thumbPath = '/modules/_thumbs/media';

        // Only run this once our pageName is set
        if(isset($this->_request->pageUrl))
        {
        	$pages = new Page_Model_Page();
            $this->page = $pages->fetchByUrl($this->_request->pageUrl);

            try {
            	if($this->page == null) {
            		throw new Zend_Controller_Action_Exception('The requested page does not exist', 404);
            	}
            } catch (Exception $e) {
            	return;
            }

            $type = $this->page->pageType;

            // do we want to show recent files on home page?
            if((bool)$this->settings->showRecentOnHomePage && $type == 'home') {
                $this->recentImages();
            }
            if($type !== 'media') {
                return;
            }
            if((bool) $this->settings->showRecentInGallery) {
                $this->recentImages();
            }
            $this->data->pageName = $this->page->pageName;
            $this->data->pageUrl = $this->page->pageUrl;

            $navContainer = Zend_Registry::get('Zend_Navigation');
            //Zend_Debug::dump($navContainer);
            $mediaPage = $navContainer->findOneById($this->data->pageUrl);
            //Zend_Debug::dump($mediaPage);
            //$mediaPage->setActive(true);

            $tags = new Zend_Filter_StripTags();
            $pattern = '/^[A-Za-z0-9_ ]+$/';
            $alpha = new Zend_Validate_Regex($pattern);
            $digits = new Zend_Filter_Digits();

            $filterRules = array('*' => $tags, 'page' => $digits);
            $validatorRules = array('albumName' => $alpha);

            $page = $this->_request->getQuery('page', 1);
            $page = $tags->filter($page);
            $page = $digits->filter($page);
            if(empty($page) || $page === "") {
            	$page = '1';
            }
            $this->page = $page;

            $action = $this->_request->getQuery('action', 'showAlbums');
            $this->action = $tags->filter($action);

            $subAction = $this->_request->getQuery('subAction', 'showFiles');
            $this->subAction = $tags->filter($subAction);

            $albumCount = $this->_request->getQuery('albumCount', 12);
            $this->albumCount = $tags->filter($albumCount);

            $fileCount = $this->_request->getQuery('fileCount', 6);
            $this->fileCount = $tags->filter($fileCount);

            $this->subAlbumPage = $this->_request->getQuery('subAlbumPage', 1);

            $this->fileId = $this->_request->getQuery('fileId', 0);

            if($this->_request->isGet()) {
            	$this->input = new Zend_Filter_Input($filterRules, $validatorRules, $this->_request->getQuery());
            	if($this->input->isValid('albumName')) {
            		$this->albumName = $this->input->albumName;
            	} else {
            		$this->albumName = 'Media';
            	}

            } else {
            	$this->albumName = 'Media';
            }

            $album = $this->albums->fetchAlbumByName($this->albumName);

            if($this->albumName === 'Media') {
            	$this->albumPath = '/modules/' . strtolower($this->albumName) . '/images';
            	$this->data->thumbPath = '/modules/_thumbs/media';
            } else {
                if($album->isChild()) {

                    $this->parent = $this->albums->fetchParentByChildName($this->albumName);
                    $this->albumPath = IMG_BASE_PATH.$album->serverPath;
                    $this->data->thumbPath = THUMB_BASE_PATH.$album->serverPath;
                }
            }
            $this->data->albumName = $this->albumName;
            $this->data->albumPath = $this->albumPath;
            $this->data->page = $this->page;
            $this->data->action = $this->action;
            $this->data->breadCrumbs = $this->getbreadCrumbs($album->serverPath);
            $this->data->subAlbumPage = $this->subAlbumPage;
            $this->data->subAlbumsPerPage = $this->subAlbumsPerPage;
            $this->data->fileCount = $this->fileCount;
            $this->data->fileId = $this->fileId;

            $this->buildWidget();
        }
    }
    public function recentImages()
    {
        $data = array();
        $this->recentImages = $this->files->fetchRecentImages();
        //Zend_Debug::dump($this->files->fetchRecentImages());
        //$this->view->partialLoop()->setObjectKey('model');
        return $this->view->partial('recentImages.phtml', 'media', $this->recentImages);


    }
    public function getbreadCrumbs($serverPath, $separator = '&raquo;') {
        $crumbs = '<span class="media-gallery-breadcrumbs">Current Location:&nbsp;';
        $crumbs .= '&nbsp;'.$separator.'<a href="/">&nbsp;Home</a>';
        $crumbs .= '&nbsp;'.$separator.'<a href="/'.$this->data->pageName.'">&nbsp;'.$this->data->pageName.'</a>';
        $paths = explode('/', $serverPath);
        $current = end($paths);
        $count = count($paths);
        $i = 0;
        if($count >= 1) {
            foreach($paths as $path) {
                if($paths[$i] == $current) {
                    $crumbs .= '&nbsp;&raquo;<a>&nbsp;'.$paths[$i].'</a>';
                } else {
                    $crumbs .= '&nbsp;&raquo;<a href="/'.$this->data->pageName.'?albumName='.$paths[$i].'&amp;action=album&amp;subAlbumPage='.$this->subAlbumPage.'#media-gallery">&nbsp;'.$paths[$i].'</a>';
                }
                $i++;
                continue;
            }
        }
        $crumbs .= '</span>';
        return $crumbs;
    }
    public function buildWidget()
    {

    	switch ($this->action) {
            // This will show when you first enter the media page ie /$mediaPageName
    		case "showAlbums":
    			// show paginated album view
    			$this->data->paginator = $this->albums->fetchPage($this->albumCount, $this->page);
    			//TODO replace this with value from settings
    			$this->template = 'mediaalbumview-default.' . $this->renderer->getViewSuffix();
    			break;

//     		case "showFiles":
//     			// show paginated file view
//     			$albumId = $this->albums->fetchIdByAlbumName($this->albumName);
//     			$this->data->paginator = $this->files->fetchPage($this->fileCount, $this->page, $albumId);
//     			//TODO replace this with value from settings
//     			$this->template = 'mediafileview-default.' . $this->renderer->getViewSuffix();
//     			break;

    		case "album":

    		    if($this->fileId > 0) {
    		       // we have a fileId so we need to show it first
    		       $this->data->showFirst = $this->files->fetchShowFirst($this->fileId);
    		       //Zend_Debug::dump($this->files->fetchShowFirst($this->fileId));
    		    }
    		    $albumId = $this->albums->fetchIdByAlbumName($this->albumName);
    		    $this->data->subAlbums = $this->albums->fetchSubAlbums($albumId);
    		    $this->data->paginator = $this->files->fetchPage($this->fileCount, $this->page, $albumId);
    		    $this->data->subAlbumPaginator = $this->albums->fetchSubAlbumPage($this->subAlbumsPerPage, $this->subAlbumPage, $albumId);
    		    //TODO replace this with value from settings
    		    //$this->recentFiles();
    		    $this->template = 'album.' . $this->renderer->getViewSuffix();
    		    $this->view->slideShow = true;
    		    break;


//     		case "slideShow":
//     			    // show paginated file view
//     			    $albumId = $this->albums->fetchIdByAlbumName($this->albumName);
//     			    $this->data->paginator = $this->files->fetchPage($this->fileCount, $this->page, $albumId);
//     			    //TODO replace this with value from settings
//     			    $this->template = 'slideshow.' . $this->renderer->getViewSuffix();
//     			    $this->view->slideShow = true;
//     			break;

    		default:
    		    // show paginated file view
//     		    $albumId = $this->albums->fetchIdByAlbumName($this->albumName);
//     		    //$this->data->subAlbums = $this->albums->fetchChildren($this->albumName);
//     		    $this->data->paginator = $this->files->fetchPage($this->fileCount, $this->page, $albumId);
//     		    //TODO replace this with value from settings
//     		    $this->template = 'slideshow.' . $this->renderer->getViewSuffix();
//     		    $this->view->slideShow = true;
            break;
    	}

        $this->renderWidget("$this->template", $this->data);
    }
    public function renderWidget($template, $data)
    {
		$this->view->media = $this->view->partial("$template", 'media', array('data' => $data));
    }
    public function __construct ()
    {
        defined('IMG_BASE_PATH') || define('IMG_BASE_PATH', '/modules/media/images/');
        defined('THUMB_BASE_PATH') || define('THUMB_BASE_PATH', '/modules/_thumbs/Media/');
        $this->settings = Zend_Registry::get('appSettings');
        $this->settings->mediashowAlbums = 'mediaalbumview-default.phtml';
        $this->settings->mediashowFiles = 'mediafileview-default.phtml';
        $this->albums = new Media_Model_Albums();
        $this->files = new Media_Model_Files();
    }
    public function getView()
    {
        if(null !== $this->view) {
            return $this->view;
        }
        $controller = $this->getActionController();
        $view = $controller->view;
        if(!$view instanceof Zend_View_Abstract) {
            return;
        }
        return $view;
    }
    /**
     * Strategy pattern: call helper as broker method
     */
    public function direct ()
    {
        // TODO Auto-generated 'direct' method
    }
}