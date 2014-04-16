<?php
class Installer_Service_Skin extends Installer_Service_InstallerAbstract
{
	public function __construct()
	{
		parent::__construct();
		

	}
	public function setArchive($archive)
	{
		$this->archive = $archive;
	}
	
}