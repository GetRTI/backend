<?php

class ApiController extends Controller
{
    public function actionIndex()
    {
        $this->render('index');
    }
    
    public function actionList()
    {
        switch ($_GET['model']){
            case 'files':
                $files = Files::model()->findAll();
                if(empty($files)){
                    $response = array(
                        'error'=>'No Files Found',
                    );
                    $this->_sendResponse(200, CJSON::encode($response));
                }
                else {
                    $response = array();
                    foreach ($files as $file){
                        $response[] = $file->attributes;
                    }
                    $this->_sendResponse(200, CJSON::encode($response));
                }
            break;
        }
    }
    
    public function actionView()
    {
        $this->_sendResponse(200, 'View action called');
    }
    
    public function actionCreate()
    {
        switch ($_GET['model']){
            case 'files':
                // validate the request
                if(!(isset($_POST['name']) && isset($_FILES['file']))){
                    $response = array(
                        'error'=>'Incomplete Request',
                    );
                    $this->_sendResponse(200, CJSON::encode($response));
                    Yii::app()->end();
                }
                else{
                    $this->_addFile();
                    }
            break;
        }
    }
    
    public function actionUpdate()
    {
        $this->_sendResponse(200, 'Update action called');
    }
    
    public function actionDelete()
    {
        $this->_sendResponse(200, 'Delete action called');
    }
    
    public function filters()
    {
        // return the filter configuration for this controller, e.g.:
        return array();
    }
    
    private function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
    {
        // set the status
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        header($status_header);
        // and the content type
        header('Content-type: ' . $content_type);
 
        // pages with body are easy
        if($body != '')
        {
            // send the body
            echo $body;
        }
        // we need to create the body if none is passed
        else
        {
            // create some body messages
            $message = '';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch($status)
            {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on 
            // (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templated in a real-world solution
            $body = '
                <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
                <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                    <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
                </head>
                <body>
                    <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
                    <p>' . $message . '</p>
                    <hr />
                    <address>' . $signature . '</address>
                </body>
                </html>';

            echo $body;
        }
        Yii::app()->end();
    }
    
    private function _getStatusCodeMessage($status)
    {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'File Type Not Allowed',
            406 => 'Incomplete Request',
            407 => 'Error',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }
    
    private function _addFile(){
        // move the file to a tmp directory
        $tmpDirfile = '../tmp/'. basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $tmpDirfile);
        
        // check the mime type
        $mime = CFileHelper::getMimeType($tmpDirfile);
        if(($mime=='image/png' || $mime=='image/jpeg' || $mime=='application/pdf')==FALSE){        
            $response = array(
                'error'=>'File mime type not allowed',
            );
            $this->_sendResponse(200, json_encode($response));
            unlink($tmpDirfile);
            Yii::app()->end();
        }
        
        // move to uploads dir and change the file name
        $utilsObj = new Utils();
        $uploadDirFile = 'files/'.$utilsObj->get_random_string('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 16).".".CFileHelper::getExtension($tmpDirfile);
        rename($tmpDirfile, $uploadDirFile);
        
        // add the file to db
        $files = new Files;
        $files->name = $_POST['name'];
        $files->department = NULL;
        if(isset($_POST['description'])){
            $files->description = $_POST['description'];
        }
        else {
            $files->description = NULL;
        }
        $files->content = NULL;
        $files->address = $uploadDirFile;
        $files->date = new CDbExpression('NOW()');
        $files->slug = $slug = Utils::slugify($_POST['name']).'-'.$utilsObj->get_random_string('0123456789', 5);
        $files->published = 0;
        $files->uploaded_by = NULL;
        $files->save();
        
        // add tags
        $fileID = Files::model()->findByAttributes(array('slug'=>$slug));
        if(isset($_POST['tags'])){
            $arrTags = str_getcsv($_POST['tags']);
            foreach($arrTags as $tag){
                $tagID = Tags::model()->findByAttributes(array('tag'=>  $tag));
                    // if its a new tag
                    if($tagID==NULL){
                        $newTag = new Tags;
                        $newTag->tag = $tag;
                        $newTag->save();
                    }
                    $tagID = Tags::model()->findByAttributes(array('tag'=>  $tag));                                 
                    $filesToTag = new FileToTags;
                    $filesToTag->file = $fileID->id;
                    $filesToTag->tag = $tagID->id;
                    $filesToTag->Save();
            }
        }
        $response = array(
            'fileID'=>$fileID->id,
            'slug'=>$fileID->slug,
        );
        
        $this->_sendResponse(200, json_encode($response));
    }
}