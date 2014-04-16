<?php
if (!defined('IN_CKFINDER')) exit;
require_once CKFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/XmlCommandHandlerBase.php";

class CKFinder_Connector_CommandHandler_ImageDesc extends CKFinder_Connector_CommandHandler_XmlCommandHandlerBase {

	public $mediaModel;
	public $row;
	public $isChild = false;

	public function __construct() {
		parent::__construct();

		$init = new DxInit();
	}
	function buildXml() {
		$desc = isset($_POST['value']) ? $_POST['value'] : isset($_GET['value']) ? $_GET['value'] : 'value';
		$desc = 'This is a default description';
		$oNode = new CKfinder_Connector_Utils_XmlNode("ImageDesc");
		$oNode->addAttribute("curDesc", $desc);
		$this->_connectorNode->addChild($oNode);
	}
	function onInitCommand( &$connectorNode )
	{
		// "@" protects against E_STRICT (Only variables should be assigned by reference)
		@$pluginsInfo = &$connectorNode->getChild("PluginsInfo");
		$desc = new CKFinder_Connector_Utils_XmlNode("ImageDesc");
		$pluginsInfo->addChild($desc);
		return true ;
	}
	function onBeforeExecuteCommand( &$command )
	{
		if ( $command == 'ImageDesc' )
		{
			$this->sendResponse();
			//return false;
		}
		return true ;
	}
}

class CKFinder_Connector_CommandHandler_ImageDescInfo extends CKFinder_Connector_CommandHandler_XmlCommandHandlerBase {

	public $media;

	public function __construct() {
		parent::__construct();
		$init = new DxInit();
		$this->media = new Media_Model_Files();
	}
	function buildXml() {
		$desc = isset($_GET['imgdesc']) ? $_GET['imgdesc'] : '';
		$fileName = isset($_GET['fileName']) ? $_GET['fileName'] : '';
		$altText = isset($_GET['altText']) ? $_GET['altText'] : '';
		$titleText = isset($_GET['titleText']) ? $_GET['titleText'] : '';
		$curFolder = $_GET['currentFolder'];
		
		if($curFolder === '/') {
		    $album = $this->_currentFolder->getResourceTypeName();
		} else {
		    
		    $dirCount = count($dirArray = explode('/', substr($_GET['currentFolder'], 1, -1) ) );
		    
		    if($dirCount > 1) {
		        $this->isChild = true;
		        $album = array_pop($dirArray);
		    } else {
		        $album = substr($_GET['currentFolder'], 1, -1);
		    }
		}
		if ( (isset($desc) && $desc !== '') && (isset($fileName) && $fileName !== '') ) {
			// we should have a file name and description to work with here.
			$row = $this->media->fetchToAddDesc($fileName, $album);

			if($row !== null) {
			    $row->alt = $altText;
			    $row->title = $titleText;
				$row->description = $desc;
				$row->save();
			}
		}
		$oNode = new CKfinder_Connector_Utils_XmlNode("ImageDesc");
		$oNode->addAttribute("imgdesc", $desc);
		$oNode->addAttribute('fileName', $fileName);
		$this->_connectorNode->addChild($oNode);
	}
	function onInitCommand( &$connectorNode )
	{
		// "@" protects against E_STRICT (Only variables should be assigned by reference)
		@$pluginsInfo = &$connectorNode->getChild("PluginsInfo");
		$desc = new CKFinder_Connector_Utils_XmlNode("ImageDesc");
		$pluginsInfo->addChild($desc);
		return true ;
	}
	function onBeforeExecuteCommand( &$command )
	{
		if ( $command == 'ImageDescInfo' )
		{
			$this->sendResponse();
			//return false;
		}
		return true ;
	}
}

$dxSaveDesc = new CKFinder_Connector_CommandHandler_ImageDesc();
$dxSaveDescInfo = new CKFinder_Connector_CommandHandler_ImageDescInfo(); // process info

$config['Hooks']['InitCommand'][] = array($dxSaveDesc, 'onInitCommand');
$config['Hooks']['BeforeExecuteCommand'][] = array($dxSaveDesc, 'onBeforeExecuteCommand');
$config['Hooks']['BeforeExecuteCommand'][] = array($dxSaveDescInfo, 'onBeforeExecuteCommand');
$config['Plugins'][] = 'imagedesc';