<?php
class System_View_Helper_ImageSlider extends Zend_View_Helper_Abstract
{
	const ALBUM_NAME = 'Slider';
	const ALBUM_ID   = -3;
	public $appSettings;
	public $html = "";
	public $slider;
	public $skin;
	public $images;
	
	public function imageSlider($sliderSkin = 'default', $isHome = false, $showSlider = true) 
	{
		$this->sliderSkin = $sliderSkin;
		$this->sliderModel = new Media_Model_SliderSettings();
		$this->slider = $this->sliderModel->fetchSettings();
		
		if( ! $this->slider->isActive ) {
			return $this->html;
		}
		
		if($showSlider) {
			$isHome = true;
		}
		
		if( ! $isHome ) {
			return $this->html;
		}
		
		$this->skinModel = new Admin_Model_Skins();
		$this->skin = $this->skinModel->fetchCurrent();
		
		// we have to have this
		$this->view->jQuery()->enable();
		$this->view->jQuery()->uiEnable();
		
		$skinSysPath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'skins' . DIRECTORY_SEPARATOR . $this->skin->skinName . DIRECTORY_SEPARATOR . 'slider.css';
		if(file_exists($skinSysPath)) {
			$stylePath = '/skins/' . $this->skin->skinName . '/slider.css';
		} else {
			$stylePath = '/skins/default/slider.css';
		}
		$sliderSkinPath = '/js-src/nivo-slider/themes/'. $sliderSkin .'/'.$sliderSkin.'.css';
		
		$this->view->headLink()->prependStylesheet('/js-src/nivo-slider/nivo-slider.css', 'screen');
		$this->view->headLink()->appendStylesheet($sliderSkinPath, 'screen');
		$this->view->headLink()->appendStylesheet($stylePath, 'screen');
		$this->view->jQuery()->addJavascriptFile('/js-src/nivo-slider/jquery.nivo.slider.js');
		
		
		$this->view->jQuery()->addJavascript('jQuery(window).load(function() {
											    jQuery("#slider").nivoSlider({
											        effect: "'.$this->slider->effect.'", 
											        slices: '.$this->slider->slices.', 
											        boxCols: '.$this->slider->boxCols.',
											        boxRows: '.$this->slider->boxRows.', 
											        animSpeed: '.$this->slider->animSpeed.', 
											        pauseTime: '.$this->slider->pauseTime.', 
											        startSlide: '.$this->slider->startSlide.', 
											        directionNav: '.$this->slider->directionNav.', 
											        controlNav: '.$this->slider->controlNav.', 
											        controlNavThumbs: '.$this->slider->controlNavThumbs.', 
											        pauseOnHover: '.$this->slider->pauseOnHover.', 
											        manualAdvance: '.$this->slider->manualAdvance.', 
											        prevText: "'.$this->slider->prevText.'", 
											        nextText: "'.$this->slider->nextText.'", 
											        randomStart: '.$this->slider->randomStart.', 
											    });
											});');

		return $this->getSliderTemplate();
		
	}
	public function getSliderTemplate() {
		
		$this->images = new Media_Model_Files();
		$images = $this->images->fetchAllByAlbumName($albumName = 'Slider');
		
		$html = "";
		
		$html .= '
		        <div class="slider-wrapper theme-'.$this->sliderSkin.'">
		            <div class="ribbon"></div>
    				    <div id="slider" class="nivoSlider">';
		
		//!empty($image->description) ? 'title="'.$image->description.'"' : '';
		
		if(count($images) >=1) {
			foreach($images as $image) {
				$title = !empty($image->description) ? 'title="'.$image->description.'"' : '';
				$html .= '<img src="/modules/slider/images/' . $image->fileName . '" alt="" '.$title.' />';
			}
		}
		
		
		$html .= '</div>
				</div>';
		
		$this->html = '<div class="slider-wrapper theme-'.$this->sliderSkin.'">
    						<div id="slider" class="nivoSlider">
				
        						<img src="/modules/slider/images/nemo.jpg" alt="" />
        							<a href="http://dev7studios.com"><img src="/modules/slider/images/up.jpg" alt="" title="#htmlcaption" /></a>
				
        						<img src="/modules/slider/images/toystory.jpg" alt="" title="This is an example of a caption" />
				
        						<img src="/modules/slider/images/walle.jpg" alt="" />
				
    						</div>
				
						</div>
				
					   <div id="htmlcaption" class="nivo-html-caption">
    						<strong>This</strong> is an example of a <em>HTML</em> caption with <a href="#">a link</a>.
					   </div>';
		
		return $html;
	}
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}