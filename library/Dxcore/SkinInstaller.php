<?php
class Dxcore_SkinInstaller
{
	public $archiveType = 'Zip'; // the only supported archive type (for the moment)
	public $archive;
	public $archiveHandler;
	public $fileHandler;
	public $skinTemplatePath = ''; //<-default path for templates, not supported at this time.
	private $skinInstallPath;
	private $tempDir;
	public $skinCssPath;
	public $targetPath;
	public $db;
	public $conf;
	public $confFile = 'skin.ini';
	public $tempSkinName;
	public $installHandler;

	public function __construct()
	{
		$this->init();
	}
	public function install() {

		$continue = false;

		if(!isset($this->archive)) {
			return false;
		}
		$parts = explode('.', $this->archive);
		$skinDir = $parts[0];
		//$this->db = new Zend_Db_Table('skins');
		$this->db = new Admin_Model_Skins();
		$this->skinInstallPath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'skins';
		$this->temp = $this->skinInstallPath . DIRECTORY_SEPARATOR . 'temp';

		$this->archiveHandler = new Zend_Filter_Decompress(array('adapter' => $this->archiveType, 'options' => array('target' => $this->skinInstallPath)));

		$decompressed = $this->archiveHandler->filter($this->skinInstallPath . DIRECTORY_SEPARATOR . $this->archive);
		if($decompressed) {

			$this->conf = new Zend_Config_Ini($this->skinInstallPath . DIRECTORY_SEPARATOR . $skinDir . DIRECTORY_SEPARATOR . $this->confFile);
			//Zend_Debug::dump($this->conf);
			if($this->conf instanceof Zend_Config_Ini) {
				if(isset($this->conf->skin->name) && $this->conf->skin->name !== '') {

					if($chmodDir = chmod($this->skinInstallPath . DIRECTORY_SEPARATOR . $this->conf->skin->name , 0777)) {
						if($chmodFile = chmod($this->skinInstallPath . DIRECTORY_SEPARATOR . $this->conf->skin->name .  DIRECTORY_SEPARATOR . $this->conf->skin->styleSheet , 0777))
						{
							//$continue = true;
						}
						if($chmodmobileFile = chmod($this->skinInstallPath . DIRECTORY_SEPARATOR . $this->conf->skin->name .  DIRECTORY_SEPARATOR . $this->conf->skin->mobiStyleSheet , 0777))
						{
						    //$continue = true;
						}
						$continue = true;
					}
					if(! $continue) {
						throw new Zend_Application_Exception('Skin directory or files could not be chomd to the correct permissions!');
					}
					$row = $this->db->fetchNew();
					//$row->refresh();
					//Zend_Debug::dump($row, '$row');
					$row->skinName = $this->conf->skin->name;
					//$row->skinStyleSheet = $this->conf->skin->styleSheet;
					$row->skinCssPath = 'skins/' . $this->conf->skin->name . '/' . $this->conf->skin->styleSheet;
					$row->skinCssMobiPath = 'skins/' . $this->conf->skin->name . '/' . $this->conf->skin->mobiStyleSheet; 
				}
				if(! $this->conf->skin->includeDefault ) {
					$row->includeDefault = (int)  $this->conf->skin->includeDefault;
				}
				$result = $row->save();
				if($result > 0) {
					//return true;
					// lets clean up here cause we have everything we need

					$removeArchive = unlink($this->skinInstallPath . DIRECTORY_SEPARATOR . $this->archive);
					//$removeTempSkinDir = unlink($this->temp . DIRECTORY_SEPARATOR . $skinDir);
					if($removeArchive) {
						return true;
					} else {
						throw new Zend_Application_Exception('Temp install directories could not be removed, please remove them manually!');
					}
				} else {
					throw new Zend_Application_Exception('There was a problem installing this skin');
				}
			}
			return true;
		}
	}
	public function init() {

	}

	public function setArchive($archive) {
		$this->archive = $archive;
	}
}