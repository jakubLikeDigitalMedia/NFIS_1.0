<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
  
set_include_path(get_include_path() . PATH_SEPARATOR . "/tmp");

//die(var_dump(realpath(".")));
$image_path = explode('public', realpath("."));
$image_path = $image_path[0];
define('IMG_ROOT_FOLDER',   $image_path .'/public/images/');
define('EMPLOYEES_IMG_FOLDER', IMG_ROOT_FOLDER.'employee/');
define('EMPLOYEES_IMG_FOLDER_ORIG', EMPLOYEES_IMG_FOLDER.'original/');
define('EMPLOYEES_IMG_FOLDER_CROP', EMPLOYEES_IMG_FOLDER.'cropped/');
define('EMPLOYEES_IMG_FOLDER_THUMB', EMPLOYEES_IMG_FOLDER.'thumb/');
define('EMPLOYEES_IMG_FOLDER_THUMB_GALLERY', EMPLOYEES_IMG_FOLDER_THUMB.'gallery/');



function ensureDirIfExists($dir)
{
  if(!file_exists($dir))
  {
      die($dir);
   mkdir($dir);
  }
}

class Dimension
{
  public $x;
  public $y;
  public $width;
  public $height;
  
  public function __construct($x, $y, $width, $height)
  {
    $this->x = $x;
    $this->y = $y;
    $this->width = $width;
    $this->height = $height;
  }
}

class UploaderOptions
{
  private $allowedExtensions;
  private $maxFilesize;
  
  
  public function setAllowedExtensions($allowedExtensions)
  {
    $this->allowedExtensions = $allowedExtensions;
  }
  
  public function setMaxFilesize($maxSize)
  {
    $this->maxFilesize = $maxSize;
  }
  
  public function validateImage($img)
  {
    $imageType = exif_imagetype($img["tmp_name"]);
    $imageSize = $img["size"];
    
    if(!in_array($imageType, $this->allowedExtensions))
    {
      return array('success' => FALSE, 'error' => "Invalid image type");
    } 
    else if($imageSize > $this->maxFilesize) {
      return array('success' => FALSE, 'error' => "Image size is too large");
    }
    else{
      return array('success' => TRUE, 'error' => "");
    }
           
  }
}

class ImageUploader
{
  private $settings;
  private $dbManager;
  
  private $files;
  
  public function generateImageName($userId, $img, $dim = '')
  {
    list($name) = explode(".", $img["name"]);
    //return $userId."_".$name."_".$dim->width."_".$dim->height.".jpg";
    $name = str_replace($userId."_", '', $name);
    return $userId."_".$name.".jpg";
  }
  
  public function __construct($settings)
  {
    $this->settings = $settings;
    
    ensureDirIfExists(IMG_ROOT_FOLDER);
    ensureDirIfExists(EMPLOYEES_IMG_FOLDER);
    ensureDirIfExists(EMPLOYEES_IMG_FOLDER_ORIG);
    ensureDirIfExists(EMPLOYEES_IMG_FOLDER_CROP);
    ensureDirIfExists(EMPLOYEES_IMG_FOLDER_THUMB);
    ensureDirIfExists(EMPLOYEES_IMG_FOLDER_THUMB_GALLERY);
  }
  
  /**
   * Loads file upload information from $_FILES to the ImageUploader object using $key
   */
  public function loadFiles($key)
  {
    
    $files = $_FILES[$key];
    
    $tmpArr = array();
    foreach ($files as $index => $value) {
      //foreach ($value as $index => $attr) {
        $tmpArr[$key][$index] = $value;
      //}
    }
    
    $this->files = array(); //Clear it before repopulating
    foreach ($tmpArr as $value) {
      $this->files[$value["name"]] = $value;
    }
  }
  
  
  /**
   * Returns an array containing all the information on the files to be uploaded
   */
  public function getFiles()
  {
    return $this->files;
  }
  
  
  /**
   * Returns a list of success status and error message(if validation fails). Keyed by image name
   */
  public function validateImages()
  {
    $ret = array();
    foreach ($this->files as $img) 
    {
      $ret[$img['name']] =$this->settings->validateImage($img);
    }
    
    return $ret;
  }
  
  
  /**
   * Params:
   * image-array containing file upload fields (name, type, tmp_name, error, size)
   * dim- Rectangle dimensions of crop
   * Returns: destination of where image is saved
   */
  public function cropImage($image, $dim)
  {
    $srcIm = @imagecreatefromstring(file_get_contents($image["tmp_name"]));
    $destIm = @imagecreatetruecolor($dim->width, $dim->height);
    
    imagecopyresampled($destIm, $srcIm, 0, 0, $dim->x, $dim->y, $dim->width,
                       $dim->height, $dim->width, $dim->height);
    
    $dest = EMPLOYEES_IMG_FOLDER_CROP.$this->generateImageName($this->settings->userId, $image, $dim);
    
    imagejpeg($destIm, $dest);
    
    return $dest;
  }
  
  
  /**
   * Params:
   * image-array containing file upload fields (name, type, tmp_name, error, size)
   * dim- Dimensions of thumbnail
   * Returns: destination of where image is saved
   */
  public function createThumbnail($image, $dim)
  {
    $srcIm = @imagecreatefromstring(file_get_contents($image["tmp_name"]));
    $destIm = @imagecreatetruecolor($dim->width, $dim->height);
    
    list($origWidth, $origHeight) = getimagesize($image["tmp_name"]);
    
    imagecopyresampled($destIm, $srcIm, 0, 0, 0, 0, $dim->width,
                       $dim->height, $origWidth, $origHeight);
    $dest = EMPLOYEES_IMG_FOLDER_THUMB.$this->generateImageName('', $image, $dim);
    
    imagejpeg($destIm, $dest);
    
    return $dest;
  }
  
  
  /**
   * Params:
   * image-array containing file upload fields (name, type, tmp_name, error, size)
   * dim- Dimensions of thumbnail
   * Returns: destination of where image is saved
   */
  public function createSquareThumbnail($image, $length)
  {
    $srcIm = @imagecreatefromstring(file_get_contents($image["tmp_name"]));
    $destIm = @imagecreatetruecolor($length, $length);
    
    list($origWidth, $origHeight) = getimagesize($image["tmp_name"]);
    
    imagecopyresampled($destIm, $srcIm, 0, 0, 0, 0, $length,
                       $length, $origWidth, $origHeight);
    
    $dim = new Dimension(0,0, $length, $length);
    $dest = EMPLOYEES_IMG_FOLDER_THUMB_GALLERY.$this->generateImageName($this->settings->userId, $image, $dim);
    header('Content-Type: image/jpeg');
    imagejpeg($destIm, $dest);
    
    return $dest;
  }
  
  
  /**
   * Copies image from temp dir to image dir
   */
  public function saveOriginalImage($image)
  {
    list($width, $height) = getimagesize($image["tmp_name"]);
    $dim = new Dimension(0,0,$width, $height);
    $dest=EMPLOYEES_IMG_FOLDER_ORIG.$this->generateImageName($this->settings->userId, $image, $dim);
   // move_uploaded_file($image["tmp_name"],$dest);
    copy($image["tmp_name"], $dest);
    
    return $dest;
  }
  
}

/**Examples
$uploaderSettings = new UploaderOptions();
$uploaderSettings->setAllowedExtensions(array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG));
$uploaderSettings->setMaxFilesize(1024*1024);

$imageUploader = new ImageUploader($uploaderSettings);
$imageUploader->loadFiles("myFile");
$ret = $imageUploader->validateImages(); 
    
$files = $imageUploader->getFiles();

foreach ($files as $name => $detail) {
	$imageUploader->saveOriginalImage($detail);
  $imageUploader->cropImage($detail, new Dimension(10,15,100,120));
  $imageUploader->createThumbnail($detail, new Dimension(0,0,100,120));
  $imageUploader->createSquareThumbnail($detail, 100);
}
**/
?>