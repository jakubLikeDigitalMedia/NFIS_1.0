<?php

class Image_Model extends Model_Abstract{

    const TABLE = 'image';
    const PRM_KEY = 'img_id';

    const TITLE = 'title';
    const DESC = 'description';
    const NAME = 'name';
    const UPLOADED_BY = 'uploaded_by';
    const UPLOAD_DATE = 'upload_date';
    const ORG_NAME = 'original_name';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
    
    public function uploadImage($_files){
            $userId = $this->userId;
            $uploaderSettings = new UploaderOptions();
            $uploaderSettings->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploaderSettings->setMaxFilesize(1024*1024);
            $uploaderSettings->userId = $userId;
            
            $imageUploader = new ImageUploader($uploaderSettings);
            $imageUploader->loadFiles('Filedata');
            $ret = $imageUploader->validateImages(); 

            $files = $imageUploader->getFiles();
            
            foreach ($files as $name => $detail) {
                $imageUploader->saveOriginalImage($detail);
        //      $imageUploader->cropImage($detail, new Dimension(10,15,100,120));
        //      $imageUploader->createThumbnail($detail, new Dimension(0,0,100,120));
                $imageUploader->createSquareThumbnail($detail, 100);
                
                return $this->insertImage($detail, $imageUploader, $userId);
            }
    }
    
    private function insertImage($detail, $imageUploader, $userId){
            $dbc = new QueryHandler();
            $fileOriginalName = $detail["name"];
            $fileName = $imageUploader->generateImageName($userId, $detail,'');
            $uploadedBy = $userId;
            return $dbc->insert(array($this::NAME => $fileName, $this::UPLOADED_BY => $uploadedBy, $this::ORG_NAME => $fileOriginalName),$this::TABLE);
    }
    
    public function cropImage($x, $y, $x2, $y2, $w, $h, $imageName){
        $userId = $this->userId;

        $uploaderSettings = new UploaderOptions();
        $uploaderSettings->setAllowedExtensions(array('jpg','jpeg','gif','png'));
        $uploaderSettings->userId = $userId;
        //$uploaderSettings->setMaxFilesize(1024*1024);

        $imageUploader = new ImageUploader($uploaderSettings);
        $image_path = explode('public', realpath("."));
        $image_path = $image_path[0] . '/public/images/employee/original/';
        $details['tmp_name'] = $image_path . $imageName;
        $details['name'] = $imageName;
        $imageUploader->cropImage($details, new Dimension($x,$y,$w,$h));
    }
    
    public function updateImageDesc($imgTitle, $imgDesc, $imgId){
        $dbc = new QueryHandler();
        $dbc->update(array($this::TITLE => $imgTitle, $this::DESC => $imgDesc), array(0 => "img_id=".$imgId), $this::TABLE);
    }
} 