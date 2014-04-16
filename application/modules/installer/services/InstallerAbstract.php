<?php
abstract class Installer_Service_InstallerAbstract
{
    
    public $archiveHandler;
    
    public $fileHandler;
    
    public $skinInstallPath;
    
    public $tempDir;
    
    public $skinCssPath;
    
    public $layoutPath;
    
    public $targetPath;
    
    public $db;
    
    public $conf;
    
    public $confFile = 'skin.ini';
    
    public $tempSkinName;
    
    public $archiveType = 'Zip'; // the only supported archive type (for the moment)
    
	public $docRoot;
	
	public $archive;
	
	public $appSettings;

	public function __construct()
	{


	}

	public function run() {}
	/**
     * @return the $archiveHandler
     */
    public function getArchiveHandler ()
    {
        return $this->archiveHandler;
    }

	/**
     * @param field_type $archiveHandler
     */
    public function setArchiveHandler ($archiveHandler)
    {
        $this->archiveHandler = $archiveHandler;
    }

	/**
     * @return the $fileHandler
     */
    public function getFileHandler ()
    {
        return $this->fileHandler;
    }

	/**
     * @param field_type $fileHandler
     */
    public function setFileHandler ($fileHandler)
    {
        $this->fileHandler = $fileHandler;
    }


	/**
     * @return the $skinInstallPath
     */
    public function getSkinInstallPath ()
    {
        return $this->skinInstallPath;
    }

	/**
     * @param field_type $skinInstallPath
     */
    public function setSkinInstallPath ($skinInstallPath)
    {
        $this->skinInstallPath = $skinInstallPath;
    }

	/**
     * @return the $tempDir
     */
    public function getTempDir ()
    {
        return $this->tempDir;
    }

	/**
     * @param field_type $tempDir
     */
    public function setTempDir ($tempDir)
    {
        $this->tempDir = $tempDir;
    }

	/**
     * @return the $skinCssPath
     */
    public function getSkinCssPath ()
    {
        return $this->skinCssPath;
    }

	/**
     * @param field_type $skinCssPath
     */
    public function setSkinCssPath ($skinCssPath)
    {
        $this->skinCssPath = $skinCssPath;
    }

	/**
     * @return the $layoutPath
     */
    public function getLayoutPath ()
    {
        return $this->layoutPath;
    }

	/**
     * @param field_type $layoutPath
     */
    public function setLayoutPath ($layoutPath)
    {
        $this->layoutPath = $layoutPath;
    }

	/**
     * @return the $targetPath
     */
    public function getTargetPath ()
    {
        return $this->targetPath;
    }

	/**
     * @param field_type $targetPath
     */
    public function setTargetPath ($targetPath)
    {
        $this->targetPath = $targetPath;
    }

	/**
     * @return the $db
     */
    public function getDb ()
    {
        return $this->db;
    }

	/**
     * @param field_type $db
     */
    public function setDb ($db)
    {
        $this->db = $db;
    }

	/**
     * @return the $conf
     */
    public function getConf ()
    {
        return $this->conf;
    }

	/**
     * @param field_type $conf
     */
    public function setConf ($conf)
    {
        $this->conf = $conf;
    }

	/**
     * @return the $confFile
     */
    public function getConfFile ()
    {
        return $this->confFile;
    }

	/**
     * @param string $confFile
     */
    public function setConfFile ($confFile)
    {
        $this->confFile = $confFile;
    }

	/**
     * @return the $tempSkinName
     */
    public function getTempSkinName ()
    {
        return $this->tempSkinName;
    }

	/**
     * @param field_type $tempSkinName
     */
    public function setTempSkinName ($tempSkinName)
    {
        $this->tempSkinName = $tempSkinName;
    }

	/**
     * @return the $archiveType
     */
    public function getArchiveType ()
    {
        return $this->archiveType;
    }

	/**
     * @param string $archiveType
     */
    public function setArchiveType ($archiveType)
    {
        $this->archiveType = $archiveType;
    }

	/**
     * @return the $docRoot
     */
    public function getDocRoot ()
    {
        return $this->docRoot;
    }

	/**
     * @param string $docRoot
     */
    public function setDocRoot ()
    {
        $this->docRoot = $docRoot;
        
        // standard project structure
        if(is_dir(dirname(APPLICATION_PATH) . DIRECTORY_SEPARATOR . 'public')) {
            $this->docRoot = dirname(APPLICATION_PATH) . DIRECTORY_SEPARATOR . 'public';
        }
        // most common server setup
        elseif(is_dir(dirname(APPLICATION_PATH) . DIRECTORY_SEPARATOR . 'public_html'))
        {
            $this->docRoot = dirname(APPLICATION_PATH) . DIRECTORY_SEPARATOR . 'public_html';
        }
        
    }

	/**
     * @return the $archive
     */
    public function getArchive ()
    {
        return $this->archive;
    }

	/**
     * @param field_type $archive
     */
    public function setArchive ($archive)
    {
        $this->archive = $archive;
    }

	/**
     * @return the $appSettings
     */
    public function getAppSettings ()
    {
        return $this->appSettings;
    }

	/**
     * @param field_type $appSettings
     */
    public function setAppSettings ($appSettings)
    {
        $this->appSettings = $appSettings;
    }

}