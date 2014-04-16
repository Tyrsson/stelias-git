<?php
class System_SkinInstaller
{
	public $archiveType = 'Zip'; // the only supported archive type (for the moment)
	public $archive;
	public $archiveHandler;
	public $templateHandler;
	public $styleHandler;
	public $fileHandler;
	public $templatePath;
	private $stylePath;
	private $tempDir;
	public $targetPath;
	public $db;
	public $config;
	public $installFile = '';
	private $step;
	public $options;
	public $skinName;
	public $front;

	public function __construct($options = null)
	{
	    if (is_array($options)) {
	        $this->setOptions($options);
	    }
	    if(!isset($this->skinName)) {
	        throw new Zend_Application_Exception('$options["skinName"] must be set!');
	    }
	    $this->front = Zend_Controller_Front::getInstance();
	}
	public function moveTree($source, $treeType)
	{
	    $movedCount = 0;
	    $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
	    foreach($objects as $name => $object){
	        if($object->isDir()) {
	            if( ! is_dir( $this->getTarget( $treeType, $object->getRealPath() ) ) ) {
	                $created = @mkdir($this->getTarget( $treeType, $object->getRealPath() ), 0777, true);
	            }
	        }
	        elseif($object->isFile()) {
	            $moved = rename($object->getRealPath(), $this->getTarget($treeType, $object->getRealPath() ));
	            if($moved) {
	                $movedCount++;
	                @chmod($this->getTarget($treeType, $object->getRealPath() ), 0777);
	            }
	        }
	    }
	    return $movedCount;
	}
	protected function getTarget($treeType, $tempPath)
	{

	    $parts = explode('/tmp/skins/' . $this->getSkinName() . '/' . $treeType, $tempPath);
	    if(isset($parts[1])) {
	        switch($treeType) {
	            case 'style' :
	            case 'styles' :
	                $baseTarget = $_SERVER['DOCUMENT_ROOT'] . $this->front->getBaseUrl() . '/skins/' . $this->getSkinName();
	                return $baseTarget . $parts[1];
	                break;
	            case 'template' :
	            case 'templates' :
	                return APPLICATION_PATH . '/skins/' . $this->getSkinName() . '/' . $parts[1];
	                break;
	        }
	    }
	}
	/**
     * @return the $config
     */
    public function getConfig ()
    {
        return $this->config;
    }

	/**
     * @param Zend_Config_Xml $config
     */
    public function setConfig (Zend_Config_Xml $config)
    {
        if($config instanceof Zend_Config_Xml)
        {
           return $this->setOptions($config->toArray());
        }
    }

	/**
     * @return the $options
     */
    public function getOptions ()
    {
        return $this->options;
    }

	/**
     * @param field_type $options
     */
    public function setOptions ($options)
    {
        $forbidden = array();

        foreach ($options as $key => $value) {

            $normalized = ucfirst($key);
            if (in_array($normalized, $forbidden)) {
                continue;
            }
            $method = 'set' . $normalized;
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }

	/**
     * @return the $skinName
     */
    public function getSkinName ()
    {
        return $this->skinName;
    }

	/**
     * @param field_type $skinName
     */
    public function setSkinName ($skinName)
    {
        $this->skinName = $skinName;
    }

	/**
     * @return the $templateHandler
     */
    public function getTemplateHandler ()
    {
        return $this->templateHandler;
    }

	/**
     * @param field_type $templateHandler
     */
    public function setTemplateHandler ($templateHandler)
    {
        $this->templateHandler = $templateHandler;
    }

	/**
     * @return the $styleHandler
     */
    public function getStyleHandler ()
    {
        return $this->styleHandler;
    }

	/**
     * @param field_type $styleHandler
     */
    public function setStyleHandler ($styleHandler)
    {
        $this->styleHandler = $styleHandler;
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
     * @return the $templatePath
     */
    public function getTemplatePath ()
    {
        return $this->templatePath;
    }

	/**
     * @param field_type $templatePath
     */
    public function setTemplatePath ($templatePath)
    {

        $this->templatePath = $templatePath;
    }

	/**
     * @return the $stylePath
     */
    public function getStylePath ()
    {
        return $this->stylePath;
    }

	/**
     * @param field_type $stylePath
     */
    public function setStylePath ($stylePath)
    {
        $front = Zend_Controller_Front::getInstance();
        //$boostrap = $front->getBaseUrl();
        if($stylePath !== $_SERVER['DOCUMENT_ROOT'] . $front->getBaseUrl() . DIRECTORY_SEPARATOR . 'skins' . DIRECTORY_SEPARATOR . $this->getSkinName());
        $this->stylePath = $stylePath;
    }

	/**
     * @return the $tempDir
     */
    public function getTempDir ()
    {
        return $this->tempDir;
    }

	/**
     * @param string $tempDir
     */
    public function setTempDir ($tempDir)
    {
        if($tempDir !== APPLICATION_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'tmp')
        {
            //throw new Zend_Application_Exception('$tempDir is not correct');
            $tempDir = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'tmp';
        }
        $this->tempDir = $tempDir;
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
     * @return the $installFile
     */
    public function getInstallFile ()
    {
        return $this->installFile;
    }

	/**
     * @param string $installFile
     */
    public function setInstallFile ($installFile)
    {
        $this->installFile = $installFile;
    }

	/**
     * @return the $step
     */
    public function getStep ()
    {
        return $this->step;
    }

	/**
     * @param field_type $step
     */
    public function setStep ($step)
    {
        $this->step = $step;
    }


}