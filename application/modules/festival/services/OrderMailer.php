<?php
/**
 * @author Joey
 *
 */
class Festival_Service_OrderMailer
{
    public $order;
    public $mService; // zend mail object
    public $pdfService; //
    public $savePath = '/modules/festival/orders';
    public $date;
    public $emailOnly = true;
    public $bcc;
    public $appSettings;
    public $tableClass;
    public $log;
    public $pages;
    public $page = 1;
    public $pageCount = 0;
    public $paginator;
    public $archiveDir;
    public $orderId;
    public $itemClass;

    public $itemsPerPage = 14;
    public $curPage = 0;
	public $items;
	public $left = 55;
	public $top = 787;
	public $x;
	public $y;
	private $receiptId;

    public function __construct(Zend_Mail $mService = null, Zend_Pdf $pdfService = null, Zend_Db_Table_Row_Abstract $order = null, $orderId = null, $itemClass = null, $receiptId = null)
    {
    	try {
    		$this->getLog();
    		$this->setAppSettings(Zend_Registry::get('appSettings'));
    		$this->setItemClass($itemClass);
    		if($mService !== null) {
    			$this->setMService($mService);
    		}
    		if($pdfService !== null) {
    			$this->setPdfService($pdfService);
    		}
    		if($order !== null) {
    			$this->setOrder($order);
    		}

    		if(isset($this->appSettings->bccOrdersTo) && !empty($this->appSettings->bccOrdersTo))
    		{
    			$pos = strpos($this->appSettings->bccOrdersTo, ',');
    			if($pos === false) {
    				$this->setBcc($this->appSettings->bccOrdersTo);
    			}
    			elseif($pos >= 1) {
    				$this->setBcc(explode(',', $this->appSettings->bccOrdersTo));
    			}
    		}
    		if($this->orderId !== null) {
    			$this->setOrderId($orderId);
    		}
    		if(isset($this->order) && $this->order !== null) {
    			$this->buildPdf($this->order);
    		}
    		if($receiptId !== null) {
    			$this->receiptId = $receiptId;
    		}
    		$this->oTable = new Festival_Model_FestivalOrders();

    	} catch (Exception $e) {
    		$this->log->warn($e->getMessage() . ' :: ' . 'File: ' . $e->getFile() . ' :: ' . 'Line: ' . ' :: ' . $e->getLine());
    	}

    }

    /**
	 * @return the $itemClass
	 */
	public function getItemClass() {
		return $this->itemClass;
	}

	/**
	 * @param field_type $itemClass
	 */
	public function setItemClass($itemClass) {

		if(is_string($itemClass)) {
			$this->itemClass = new $itemClass();
		}
		elseif (is_object($itemClass)) {
			$this->itemClass = $itemClass;
		}
	}

	public function send()
    {
    	$this->mService->send();
    }
    /*
     * @param $order
     * @type  array | object
     */
    public function getPages($order)
    {

    }
    public function getPaginator(array $array, $pageNumber = 1, $itemCountPerPage = 14) {

    	$this->setPaginator(new Zend_Paginator(new Zend_Paginator_Adapter_Array($array)));
    	//$this->paginator->setCurrentPageNumber($pageNumber);
    	if($itemCountPerPage !== null) {
    		$this->paginator->setItemCountPerPage($itemCountPerPage);
    	}

    	return $this->paginator;
    }
    public function setPaginator(Zend_Paginator $paginator)
    {
    	$this->paginator = $paginator;
    }
    public function getArchiveYear()
    {
        $date = Zend_Date::now();
        return $date->get(Zend_Date::YEAR);
    }
    public function buildPdf($order = null)
    {
        try {
            if($order == null) {
                $order = $this->getOrder();
            }
            if (is_array($order) || is_object($order)) {
                /**
                 * Target directory where PDF files are stored
                 */

                //$targetDir = $_SERVER['DOCUMENT_ROOT'] . $this->savepath . DIRECTORY_SEPARATOR . $this->getArchiveYear();
                //$this->log->debug(Zend_Debug::dump(APPLICATION_PATH));

                $this->archiveDir = $_SERVER['DOCUMENT_ROOT'] . $this->savePath . DIRECTORY_SEPARATOR . $this->getArchiveYear();
                if (!is_dir($this->archiveDir)) {
                    if (mkdir($this->archiveDir)) {
                        chmod($this->archiveDir, 0777);
                    } else {
                        //die('Error: could not create directory: ' . $targetDir); /* FIXME */
                    }
                }
                // get paginator
                if(is_object($order)) {
                    if(isset($order->order) && is_array($order->order)) {
                        //$this->getPaginator($order->order);
                        $this->items = $order->order;
                        unset($order->order);
                    }
                }
                elseif(is_array($order)) {
                    if(isset($order['order']) && is_array($order['order'])) {
                        //$this->getPaginator($order['order']);
                        $this->items = $order['order'];
                        unset($order['order']);
                    }
                }

                $this->pageCount = ( count($order) + count($this->items));

                $pdf = $this->getPdfService();

                $page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
                $pdf->pages[] = $page;
                $this->curPage++;

                /**
                 * Coordinates to top left corner of PDF page (units)
                 */
                $left = 55;
                $top  = 787;

                $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 8);
                $page->drawText('Page 1 of ' . count($this->pageCount), 277, 34);

                $x = $this->x = $this->left;
                $y = $this->y = $this->top;

                $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);
                $page->drawText('Order Date', $x, $y);
                $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
                $date = new Zend_Date();
                Zend_Date::setOptions(array('format_type' => 'php'));
                $page->drawText($date->get('F j, Y, g:i a'), $x + 144, $y);
                $y -= 21;

                $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);
                $page->drawText('Receipt Number', $x, $y);
                $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
                $page->drawText($order->receiptId, $x + 144, $y);
                $y -= 21;

                // delivery pickup time
                if(isset($order->time) && !empty($order->time)) {

                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);
                	$page->drawText('Delivery/ Pickup Time', $x, $y);
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
                	$page->drawText($order->time, $x + 144, $y);
                	$y -= 21;
                }
                // name
                $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);
                $page->drawText('Purchaser Name:', $x, $y);
                $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
                $page->drawText(trim(ucwords($order->name)), $x + 144, $y);
                $y -= 21;
                // email address
                $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);
                $page->drawText('Email', $x, $y);
                $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
                $page->drawText($order->email, $x + 144, $y);
                $y -=21;
                // phone
                if(isset($order->phone) && !empty($order->phone) )
                {
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);
                	$page->drawText('Phone', $x, $y);
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
                	$page->drawText(trim(ucwords($order->phone)), $x + 144, $y);
                	$y -= 21;
                }

                // cell number, optional
                if(isset($order->cell) && !empty($order->cell) )
                {
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);
                	$page->drawText('Cell Number', $x, $y);
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
                	$page->drawText(trim(ucwords($order->cell)), $x + 144, $y);
                	$y -= 21;
                }
                // econtant Emergency Contact, optional
                if(isset($order->econtact) && !empty($order->econtact) )
                {
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);
                	$page->drawText('Cell Number', $x, $y);
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
                	$page->drawText(trim(ucwords($order->econtact)), $x + 144, $y);
                	$y -= 21;
                }
                /*
                 * All delivery options are optional as they can order it for pickup
                 */
                // street 1
                if(isset($order->street1) && !empty($order->street1) )
                {
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);
                	$page->drawText('Street:', $x, $y);
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
                	$page->drawText(trim(ucwords($order->street1)), $x + 144, $y);
                	$y -= 21;
                }
                /*
                 * the following two do not get a heading as they will be part of the street address, never saw these used though :P
                 */
                if(isset($order->street2) && !empty($order->street2) )
                {
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);
                	//$page->drawText('Street:', $x, $y);
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
                	$page->drawText(trim(ucwords($order->street2)), $x + 144, $y);
                	$y -= 21;
                }
                if(isset($order->street3) && !empty($order->street3) )
                {
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);
                	//$page->drawText('Street:', $x, $y);
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
                	$page->drawText(trim(ucwords($order->street3)), $x + 144, $y);
                	$y -= 21;
                }
                // the apartment number if one if included, will need this from time to time
                if(isset($order->apt) && !empty($order->apt) )
                {
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);
                	$page->drawText('Apartment Number', $x, $y);
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
                	$page->drawText(trim(ucwords($order->apt)), $x + 144, $y);
                	$y -= 21;
                }
                // City
                if(isset($order->city) && !empty($order->city) )
                {
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);
                	$page->drawText('City', $x, $y);
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
                	$page->drawText(trim(ucwords($order->city)), $x + 144, $y);
                	$y -= 21;
                }

                // state
                if(isset($order->state) && !empty($order->state) )
                {
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);
                	$page->drawText('State', $x, $y);
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
                	$page->drawText(trim(ucwords($order->state)), $x + 144, $y);
                	$y -= 21;
                }

                // zip, cant find ya without this DOH
                if(isset($order->zip) && !empty($order->zip) )
                {
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);
                	$page->drawText('Postal Code', $x, $y);
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
                	$page->drawText($order->zip, $x + 144, $y);
                	$y -= 21;
                }

                if(isset($order->directions) && !empty($order->directions) )
                {
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);
                	$page->drawText('Directions', $x, $y);
                	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
                	$page->drawText($order->directions, $x + 144, $y);
                	$y -= 21;
                }



                $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);
                $page->drawText('Item Name', $x, $y);
                $page->drawText('Quantity', $x + 322, $y);
                $page->drawText('Item Cost', $x + 411, $y);
                $y -= 21;

                $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);


                $currency = new Zend_Currency('en_US');
                $currencyHelper = new Zend_View_Helper_Currency($currency);

                foreach ($this->items as $item) {

                        $page->drawText($this->itemClass->fetchName($item['itemId']), $x, $y);
                        $page->drawText($item['qty'], $x + 322, $y);
                        $page->drawText($currencyHelper->currency($this->itemClass->fetchPrice($item['itemId'])), $x + 411, $y);
                        $y -= 13;

                }

                // after all items have been written to pdf we write the total
                $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);
                $page->drawText('Order Total', $x, $y);
                //$page->drawText('Price', $x + 322, $y);
                $page->drawText($currencyHelper->currency($order->total), $x + 411, $y);
                $y -= 21;

                /**
                 * Target name of the PDF file
                 */
                $fileName = $this->archiveDir . DIRECTORY_SEPARATOR . 'order-' . $date->get('Y-m-d-H-i') . '.pdf';

                $pdf->save($fileName);


                $this->mService->setBodyText("Thank you for your recent order at the St. Elias Food Festival.");

                $this->mService->setFrom('noreply@stelias.org', 'St Elias Food Festival');
                /*if (!$this->contactSettings->showLocations) {
                 $mail->setFrom($this->contactSettings->sendFrom, $_SERVER['HTTP_HOST']);
                } elseif ($this->contactSettings->showLocations) {
                $mail->setFrom($values['email'], $values['name']);
                }*/

                $this->mService->addTo($this->appSettings->siteEmail);
                $this->mService->addBcc($this->bcc);
                $this->mService->setSubject('Food Festival Receipt');

                $at = new Zend_Mime_Part($pdf->render());

                $at->type        = 'application/pdf';
                $at->disposition = Zend_Mime::DISPOSITION_INLINE;
                $at->encoding    = Zend_Mime::ENCODING_BASE64;
                $at->filename    = basename($fileName);

                $this->mService->addAttachment($at);

                $this->send();
            }
        } catch (Exception $e) {
            $this->log->debug('Error Message:'. $e->getMessage() . ' :: Trace: ' . $e->getTraceAsString() );
        }

    }

	/**
	 * @return the $bcc
	 */
	public function getBcc() {
		return $this->bcc;
	}

	/**
	 * @return the $appSettings
	 */
	public function getAppSettings() {
		return $this->appSettings;
	}

	/**
	 * @return the $tableClass
	 */
	public function getTableClass() {
		return $this->tableClass;
	}

	/**
	 * @param field_type $bcc
	 */
	public function setBcc($bcc) {
		$this->bcc = $bcc;
	}

	/**
	 * @param mixed $appSettings
	 */
	public function setAppSettings($appSettings) {
		$this->appSettings = $appSettings;
	}

	/**
	 * @param field_type $tableClass
	 */
	public function setTableClass($tableClass) {
		$this->tableClass = $tableClass;
	}

	/**
	 * @return the $log
	 */
	public function getLog() {

		try {
			if(!isset($this->log) || $this->log == null || empty($this->log))
			{
				$this->log = Zend_Registry::get('log');
			}
			elseif(isset($this->log) && !$this->log instanceof Dxcore_Log)
			{
				throw new Zend_Service_Exception('Festival_Service_OrderMailer::$log must be an instance of Dxcore_Log');
			}
		} catch (Exception $e) {
			$this->log = Zend_Registry::get('log');
		}
		return $this->log;
	}

	/**
	 * @param field_type $log
	 */
	public function setLog($log) {
		$this->log = $log;
	}

	/**
     * @return the $order
     */
    public function getOrder ()
    {
        return $this->order;
    }

	/**
     * @return the $mService
     */
    public function getMService ()
    {
        if(!$this->mService instanceof Zend_Mail)
        {
            $this->mService = new Zend_Mail();
        }
        return $this->mService;
    }

	/**
     * @return the $pdfService
     */
    public function getPdfService ()
    {
        if(!$this->pdfService instanceof Zend_Pdf)
        {
            $this->pdfService = new Zend_Pdf();
        }
        return $this->pdfService;
    }


	/**
     * @return the $date
     */
    public function getDate ()
    {
        return $this->date;
    }

	/**
     * @return the $emailOnly
     */
    public function getEmailOnly ()
    {
        return $this->emailOnly;
    }

	/**
     * @param field_type $order
     */
    public function setOrder ($order)
    {
        $this->order = $order;
    }

	/**
     * @param field_type $mService
     */
    public function setMService ($mService)
    {
        $this->mService = $mService;
    }

	/**
     * @param field_type $pdfService
     */
    public function setPdfService ($pdfService)
    {
        $this->pdfService = $pdfService;
    }

	/**
     * @param field_type $savepath
     */
    public function setSavepath ($savepath)
    {
        $this->savepath = $savepath;
    }

	/**
     * @param field_type $date
     */
    public function setDate ($date)
    {
        $this->date = $date;
    }

	/**
     * @param boolean $emailOnly
     */
    public function setEmailOnly ($emailOnly)
    {
        $this->emailOnly = $emailOnly;
    }
	/**
	 * @return the $savePath
	 */
	public function getSavePath() {
		return $this->savePath;
	}


	/**
	 * @return the $pageCount
	 */
	public function getPageCount() {
		return $this->pageCount;
	}

	/**
	 * @return the $archiveDir
	 */
	public function getArchiveDir() {
		return $this->archiveDir;
	}

	/**
	 * @return the $orderId
	 */
	public function getOrderId() {
		return $this->orderId;
	}


	/**
	 * @param field_type $pages
	 */
	public function setPages($pages) {
		$this->pages = $pages;
	}

	/**
	 * @param number $pageCount
	 */
	public function setPageCount($pageCount) {
		$this->pageCount = $pageCount;
	}

	/**
	 * @param string $archiveDir
	 */
	public function setArchiveDir($archiveDir) {
		$this->archiveDir = $archiveDir;
	}

	/**
	 * @param field_type $orderId
	 */
	public function setOrderId($orderId) {
		$this->orderId = $orderId;
	}



}