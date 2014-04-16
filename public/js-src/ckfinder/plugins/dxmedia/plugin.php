<?php
if (!defined('IN_CKFINDER')) exit;
require_once($_SERVER['DOCUMENT_ROOT'] . '/js-src/ckfinder/core/connector/php/constants.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/js-src/ckfinder/core/connector/php/php5/CommandHandler/XmlCommandHandlerBase.php');

class DxInit {
    public $appPath;
    public $modulePath;
    public $registry;
    public $acl;
    public function __construct() {
        
        // Define path to application directory
        defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', realpath(dirname($_SERVER['DOCUMENT_ROOT']).'/application') );
        
        // Define application environment
        defined('APPLICATION_ENV')
        || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));
        
        // Define these to test env
        defined('DEV') || define('DEV', 'development');
        defined('PROD') || define('PROD', 'production');
        
        // Ensure library/ is on include_path
        set_include_path(implode(PATH_SEPARATOR, array(
        realpath(APPLICATION_PATH . '/../library'),
        get_include_path(),
        )));
        
        /** Zend_Application */
        require_once 'Zend/Application.php';
        
        // Create application, bootstrap, and run
        $application = new Zend_Application(
                APPLICATION_ENV,
                APPLICATION_PATH . '/configs/application.ini'
        );
        $application->bootstrap();
        

         $this->acl = new User_Acl_Acl();

        return $this;
    }
    public function setupModel($moduleName) {

    }
    public function getSaveTime() {
        $date = new Zend_Date();
        return $date->getTimestamp();
    }
}

class DxDeleteFile extends CKFinder_Connector_CommandHandler_XmlCommandHandlerBase {

    function buildXml() {

    }
    function onBeforeExecuteCommand( &$command )
    {
        $init = new DxInit();
        if ( $command == 'DeleteFile' )
        {
        	$filter = new Zend_Filter_BaseName();

	        if (!$this->_currentFolder->checkAcl(CKFINDER_CONNECTOR_ACL_FILE_DELETE)) {
	            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
	        }
	        if (!isset($_GET["FileName"])) {
	            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_NAME);
	        }
    		$fileName = CKFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($_GET["FileName"]);

            if (isset($fileName) && !empty($fileName)) {
                $module = $this->_currentFolder->getResourceTypeName();

                $albumName = $filter->filter($this->_currentFolder->getClientPath());
                if (($count = strlen($albumName)) === 0) {
                	$albumName = $module;
                }
                switch ($module) {
                	case "Pages":

                	    $model = new Pages_Model_Files();
                	    $row = $model->fetchByFileName($fileName);
                	    if($row instanceof Pages_Model_Row_File) {
                	        try {
                	            $row->delete();
                	        } catch (Zend_Exception $e) {
                	            
                	        }
                	        
                	    }
                	    break;

                	case "Media":
                		$model = new Media_Model_Files();
                		$row = $model->fetchForCKFinderDeleteFile($fileName, $albumName);
                		if($row instanceof Zend_Db_Table_Row_Abstract) {
                		    try {
                	            $row->delete();
                	        } catch (Zend_Exception $e) {
                	            
                	        }
                		}
                		break;
                		
                	case "Slider":
                			$model = new Media_Model_Files();
                			$row = $model->fetchForCKFinderDeleteFile($fileName, $albumName);
                			if($row instanceof Zend_Db_Table_Row_Abstract) {
                			    try {
                	               $row->delete();
                	            } catch (Zend_Exception $e) {
                	            
                	            }
                			}
                			break;
                	default:
                	    $model = null;
                	    $row = null;
                }
            }

        }
        return true ;
    }
}

class DxRenameSave extends CKFinder_Connector_CommandHandler_XmlCommandHandlerBase {

    function buildXml() {

    }
    function onBeforeExecuteCommand( &$command )
    {
        $init = new DxInit();
        if ( $command == 'RenameFile' )
        {

            if (!isset($_GET["fileName"])) {
                $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_NAME);
            }
            if (!isset($_GET["newFileName"])) {
                $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_NAME);
            }
            $_config =& CKFinder_Connector_Core_Factory::getInstance("Core_Config");
            $fileName = CKFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($_GET["fileName"]);
            $newFileName = CKFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($_GET["newFileName"]);

            if (isset($_GET["newFileName"]) && isset($_GET["fileName"])) {
	            $module = $this->_currentFolder->getResourceTypeName();

	            switch ($module) {
	            	case "Pages":
	            	    	$model = new Pages_Model_Files();
	            	    	$row = $model->fetchByFileName($fileName);
	            	    	break;
	            	case "Media":
	            	    	$model = new Media_Model_Files();
	            	    	$row = $model->fetchByFileName($fileName);
	            	    	break;
	            	case "Slider":
	            	    	$model = new Media_Model_Files();
	            	    	$row = $model->fetchByFileName($fileName);
	            	    	break;
	            	default:
	            	    throw new Zend_Application_Exception('Unknown CkFinder resource type' . __FILE__ . '::' . __LINE__);
	            }
	            
	            $row->fileName = $newFileName;
	            $row->timestamp = $init->getSaveTime();
	            $row->save();
            }

        }
        return true ;
    }
}

class DxUploadSave {

	function onAfterFileUpload($currentFolder, $uploadedFile, $sFilePath)
    {
        global $config;
        $mediaSettings = $config['Plugin_Dxmedia'];
        $result = $this->saveToDb($uploadedFile, $currentFolder);
        return $result;
    }
    function saveToDb ($uploadedFile, $currentFolder = null, $sFilePath = null)
    {
        $init = new DxInit();
        $auth = Zend_Auth::getInstance();

        if($auth->hasIdentity()) {
            $userId = $auth->getIdentity()->userId;
        } 
        $filter = new Zend_Filter_BaseName();
        $module = $currentFolder->getResourceTypeName();
        $i = 0;
        switch ($module) {
        	case "Pages":
        	    	$model = new Pages_Model_Files();
        	    	$row = $model->fetchNew();
        	    	//$ns = new Zend_Session_Namespace(strtolower($module));
        	    	if(!isset($ns->pageId)) {
        	    	    return false;
        	    	}
        	    	foreach($uploadedFile as $fileName) {
        	    		$row->fileName = $fileName;
        	    		$row->pageId = $ns->pageId;
        	    		$row->userId = $userId;
        	    		$row->timestamp = $init->getSaveTime();
        	    		if($i === 0) {
        	    			$row->isMainImage = 1;
        	    		}
        	    		$save = $row->save();
        	    		if(!$save) {
        	    			return false;
        	    		}
        	    		$i++;
        	    		usleep(20000);
        	    		continue;
        	    	}
        	    break;
        	case "Media":
        			//$ns = new Zend_Session_Namespace(strtolower($module));
        			$albumName = $filter->filter($currentFolder->getClientPath());
        			if (($count = strlen($albumName)) === 0) {
        				$albumName = $module;
        			}
        	    	$album = new Media_Model_Albums();
        	    	$albumId = $album->fetchIdByAlbumName($albumName);
        	    	$model = new Media_Model_Files();
        	    	$row = $model->fetchNew();
        	    	$filename = $uploadedFile['name'];

        	    	foreach($uploadedFile as $k => $v) {
        	    	    //$date = new Zend_Date();
        	    	    $row->fileName = $filename;
        	    	    $row->albumId =  $albumId;
        	    	    $row->description = '';
        	    	    $row->timestamp = $init->getSaveTime();
        	    	    $save = $row->save();
        	    	    if(!$save) {
        	    	        return false;
        	    	    }
        	    	    $i++;
        	    	    usleep(20000);
        	    	    continue;
        	    	}
        	    break;
        	case "Slider":
	        		$albumName = $filter->filter($currentFolder->getClientPath());
	        		if (($count = strlen($albumName)) === 0) {
	        			$albumName = $module;
	        		}
	        		$album = new Media_Model_Albums();
	        		$albumId = $album->fetchIdByAlbumName($albumName);
	        		$model = new Media_Model_Files();
	        		$row = $model->fetchNew();
	        		$filename = $uploadedFile['name'];
	        		
	        		foreach($uploadedFile as $k => $v) {
	        			$row->fileName = $filename;
	        			$row->albumId =  $albumId;
	        			$row->description = '';
	        			$row->timestamp = $init->getSaveTime();
	        			$save = $row->save();
	        			if(!$save) {
	        				return false;
	        			}
	        			$i++;
	        			usleep(20000);
	        			continue;
	        		}
        	default:
        	    $model = null;
        	    $row = null;
        	    $album = null;
        }
		return true;
    }
}
class DxCreateAlbum extends CKFinder_Connector_CommandHandler_XmlCommandHandlerBase {
    function buildXml() {

    }
    function onBeforeExecuteCommand( &$command )
    {
        $init = new DxInit();
        if ( $command == 'CreateFolder' )
        {
        	$isChild = false;
        	$album = new Media_Model_Albums();
        	$row = $album->fetchNew();
        	$row->userId = Zend_Auth::getInstance()->getIdentity()->userId;
        	
        	// is this the Media album? if so we do not have a parent
        	if($_GET['currentFolder'] === '/' && $_GET['type'] == 'Media') {

        		$parentAlbum = $_GET['type'];
        		$parentId = $album->fetchIdByAlbumName($parentAlbum);
        		$row->albumName = $_GET['NewFolderName'];
        		$row->serverPath = $_GET['NewFolderName'];
        		$row->parentId = $parentId;
        	}
        	elseif(!empty($_GET['currentFolder'])) {
        	    
        	    
        	    $dirCount = count($dirArray = explode('/', substr($_GET['currentFolder'], 1, -1) ) );
        	    
        	    if($dirCount >= 1) {
        	        $this->isChild = true;
        	        $parentAlbum = array_pop($dirArray);
        	        $dirArray[] = $parentAlbum;
        	        // $log->info('$dirArray' . Zend_Debug::dump($dirArray));
        	        $newPath = implode('/', $dirArray);
        	        $newPath = $newPath . '/' . $_GET['NewFolderName'];
        	        $parentId = $album->fetchIdByAlbumName($parentAlbum);
        	        //Zend_Debug::dump($dirArray);
        	    } 
//         	    else {
//         	        $newPath = $_GET['currentFolder'] . $_GET['NewFolderName'];
//         	        $parentAlbum = substr($_GET['currentFolder'], 1, -1);
//         	        $parentId = $album->fetchIdByAlbumName($parentAlbum);
//         	    }
        	    $row->albumName = $_GET['NewFolderName'];
        	    $row->serverPath = $newPath;
        	    $row->parentId = $parentId;
        	}
        	        
                $result = (bool) $row->save();
                return $result;
        }
        
        return true;
    }
}
class DxRenameAlbum extends CKFinder_Connector_CommandHandler_XmlCommandHandlerBase {
    function buildXml() {

    }
    function onBeforeExecuteCommand( &$command )
    {
        $init = new DxInit();
        if ( $command == 'RenameFolder' )
        {
            
            if(Zend_Registry::isRegistered('log')) {
                $log = Zend_Registry::get('log');
            }

            if(isset($_GET['NewFolderName']) && isset($_GET['currentFolder'])) {
                
                $album = new Media_Model_Albums();
                
                $dirCount = count($dirArray = explode('/', substr($_GET['currentFolder'], 1, -1) ) );
                
                if($dirCount > 1) {
                    $this->isChild = true;
                    $oldAlbum = array_pop($dirArray);
                   // $log->info('$dirArray' . Zend_Debug::dump($dirArray));
                    $newPath = implode('/', $dirArray);
                    $newPath = $newPath . '/' . $_GET['NewFolderName'];
                    //Zend_Debug::dump($dirArray);
                } else {
                    $oldAlbum = substr($_GET['currentFolder'], 1, -1);
                    $newPath = $_GET['NewFolderName'];
                }
                
                $row = $album->fetchForCkFinderRename($oldAlbum);
                $children = $album->fetchAllByParentId($row->albumId);
                ////////////
                foreach($children as $child) {
                    $newChildPath = str_replace($oldAlbum, $_GET['NewFolderName'], $child->serverPath);
                    $child->serverPath = $newChildPath;
                    $child->save();
                }/////////////////
                
				$row->albumName = $_GET['NewFolderName'];
				$row->serverPath = $newPath;
                $row->userId = Zend_Auth::getInstance()->getIdentity()->userId;
                if((bool) $row->save()) {
                    return true;
                } else {
                    return false;
                }
            }
            
            
        }
        return true;
    }
}
class DxDeleteAlbum extends CKFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
	function buildXml() {

	}
	function onBeforeExecuteCommand( &$command )
	{
		$init = new DxInit();
		if ( $command == 'DeleteFolder' )
		{
				$filter = new Zend_Filter_BaseName();
				$albumName = $filter->filter($this->_currentFolder->getServerPath());
				$album = new Media_Model_Albums();
				
				$row = $album->fetchForCkFinderDelete($albumName);
				if($row instanceof Zend_Db_Table_Row_Abstract) {
					if($init->acl->isAllowed('admin', 'media.albums', 'admin.media.delete') || $row->userId === Zend_Auth::getInstance()->getIdentity()->userId) {

						$fileModel = new Media_Model_Files();
						$files = $fileModel->fetchFilesForCkFinderDeleteAlbum($row->albumId, $albumName = null);
						if(count($files) >= 1 && $files instanceof Zend_Db_Table_Rowset_Abstract) {
							foreach($files as $file) {
							    
							    try {
							        $file->delete();
							        continue;
							    } catch (Zend_Exception $e) {
							        
							    }
							    continue;
								
							}
						}
						//TODO: Test this routine
					    $subAlbums = $album->fetchAllByParentId($row->albumId);
					    if($subAlbums !== null) {
    					    foreach($subAlbums as $child) {
    					        
    					        $childrenFiles = $fileModel->fetchFilesForCkFinderDeleteAlbum($child->albumId, null);
    					        if($childrenFiles !== null) {
        					        foreach($childrenFiles as $cFile) {
        					            try {
							                 $file->delete();
							                 continue;
							            } catch (Zend_Exception $e) {
							        
							            }
							            continue;
        					        }
    					        }
    					        try {
							        $file->delete();
							        continue;
							    } catch (Zend_Exception $e) {
							        
							    }
							    continue;
    					    }
					    }
					    try {
					        $result = $row->delete();
					        return true;
					    } catch (Zend_Exception $e) {
					    }
						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}
		}
		return true;
	}
}

$dxUploadSave = new DxUploadSave();
$dxRenameSave = new DxRenameSave();
$dxDeleteFile = new DxDeleteFile();
$DxCreateAlbum = new DxCreateAlbum();
$DxRenameAlbum = new DxRenameAlbum();
$DxDeleteAlbum = new DxDeleteAlbum();
$config['Hooks']['AfterFileUpload'][] = array($dxUploadSave, 'onAfterFileUpload');
$config['Hooks']['BeforeExecuteCommand'][] = array($dxRenameSave, 'onBeforeExecuteCommand');
$config['Hooks']['BeforeExecuteCommand'][] = array($dxDeleteFile, 'onBeforeExecuteCommand');
$config['Hooks']['BeforeExecuteCommand'][] = array($DxCreateAlbum, 'onBeforeExecuteCommand');
$config['Hooks']['BeforeExecuteCommand'][] = array($DxRenameAlbum, 'onBeforeExecuteCommand');
$config['Hooks']['BeforeExecuteCommand'][] = array($DxDeleteAlbum, 'onBeforeExecuteCommand');
