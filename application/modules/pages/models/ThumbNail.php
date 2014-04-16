<?php
/**
 *
 * @author Joey Smith
 *        
 */
class ThumbNail
{
    public $thumbDir;
    public $ratio = 1;
    public $image;
    public $galleryPath;
    public $albums;
    public $files;
    public $imageId;
    public $type;
    public $width;
    public $height;
    public $maxWidth;
    public $maxHeight;
    public $thumbWidth;
    public $thumbHeight;
    public $thumbNailImage;
    public $sourceImage;
    // TODO - Insert your code here
    /**
     */
    public function __construct ()
    {
        define('MAX_WIDTH', $maxWidth);
        define('MAX_HEIGHT', $maxHeight);
        // TODO - Insert your code here
    }
	/**
     * @return the $thumbDir
     */
    public function getThumbDir ()
    {
        return $this->thumbDir;
    }

	/**
     * @param string $thumbDir
     */
    public function setThumbDir ($thumbDir)
    {
        $this->thumbDir = $thumbDir;
    }

	/**
     * @return the $ratio
     */
    public function getRatio ()
    {
        return $this->ratio;
    }

	/**
     * @param number $ratio
     */
    public function setRatio ($ratio)
    {
        $this->ratio = $ratio;
    }

	/**
     * @return the $image
     */
    public function getImage ()
    {
        return $this->image;
    }

	/**
     * @param field_type $image
     */
    public function setImage ($image)
    {
        $this->image = $image;
    }

	/**
     * @return the $galleryPath
     */
    public function getGalleryPath ()
    {
        return $this->galleryPath;
    }

	/**
     * @param field_type $galleryPath
     */
    public function setGalleryPath ($galleryPath)
    {
        $this->galleryPath = $galleryPath;
    }

	/**
     * @return the $albums
     */
    public function getAlbums ()
    {
        return $this->albums;
    }

	/**
     * @param field_type $albums
     */
    public function setAlbums ($albums = null)
    {
        if(null !== $albums) {
        	$this->albums = $albums;
        } else {
            $this->albums = new Pages_Model_Albums();
        }
    }

	/**
     * @return the $files
     */
    public function getFiles ()
    {
        return $this->files;
    }

	/**
     * @param field_type $files
     */
    public function setFiles ($files = null)
    {
        if(null !== $files) {
            $this->files = $files;
        } else {
            $this->files = new Pages_Model_Files();
        }
    }

	/**
     * @return the $imageId
     */
    public function getImageId ()
    {
        return $this->imageId;
    }

	/**
     * @param field_type $imageId
     */
    public function setImageId ($imageId)
    {
        $this->imageId = $imageId;
    }

	/**
     * @return the $type
     */
    public function getType ()
    {
        return $this->type;
    }

	/**
     * @param field_type $type
     */
    public function setType ($type)
    {
        $this->type = $type;
    }

	/**
     * @return the $width
     */
    public function getWidth ()
    {
        return $this->width;
    }

	/**
     * @param field_type $width
     */
    public function setWidth ($width)
    {
        $this->width = $width;
    }

	/**
     * @return the $height
     */
    public function getHeight ()
    {
        return $this->height;
    }

	/**
     * @param field_type $height
     */
    public function setHeight ($height)
    {
        $this->height = $height;
    }

	/**
     * @return the $maxWidth
     */
    public function getMaxWidth ()
    {
        return $this->maxWidth;
    }

	/**
     * @param field_type $maxWidth
     */
    public function setMaxWidth ($maxWidth)
    {
        $this->maxWidth = $maxWidth;
    }

	/**
     * @return the $maxHeight
     */
    public function getMaxHeight ()
    {
        return $this->maxHeight;
    }

	/**
     * @param field_type $maxHeight
     */
    public function setMaxHeight ($maxHeight)
    {
        $this->maxHeight = $maxHeight;
    }

	/**
     * @return the $thumbWidth
     */
    public function getThumbWidth ()
    {
        return $this->thumbWidth;
    }

	/**
     * @param field_type $thumbWidth
     */
    public function setThumbWidth ($thumbWidth)
    {
        $this->thumbWidth = $thumbWidth;
    }

	/**
     * @return the $thumbHeight
     */
    public function getThumbHeight ()
    {
        return $this->thumbHeight;
    }

	/**
     * @param field_type $thumbHeight
     */
    public function setThumbHeight ($thumbHeight)
    {
        $this->thumbHeight = $thumbHeight;
    }

	/**
     * @return the $thumbNailImage
     */
    public function getThumbNailImage ()
    {
        return $this->thumbNailImage;
    }

	/**
     * @param field_type $thumbNailImage
     */
    public function setThumbNailImage ($thumbNailImage)
    {
        $this->thumbNailImage = $thumbNailImage;
    }

	/**
     * @return the $sourceImage
     */
    public function getSourceImage ()
    {
        return $this->sourceImage;
    }

	/**
     * @param field_type $sourceImage
     */
    public function setSourceImage ($sourceImage)
    {
        $this->sourceImage = $sourceImage;
    }

}
?>