<?php

class ApiController extends Controller
{
    public function actionIndex()
    {
        $this->render('index');
    }
    
    public function actionList()
    {
        $this->_sendResponse(200, 'List action called');
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
                    $this->_sendResponse(406, 'Incomplete Request');
                }
                else{
                    //upload the file to tmp directory
                    $uploaddir = '../tmp/';
                    $uploadfile = $uploaddir . basename($_FILES['file']['name']);
                    move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile);
                    // check the file mime type
                    $mime = CFileHelper::getMimeType($uploadfile);
                    if($mime=='image/png' || $mime=='image/jpeg' || $mime=='application/pdf'){
                        // change file name and move to files directory
                        $utilsObj = new Utils();
                        $newfilename = $utilsObj->get_random_string('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 16);
                        $file = $newfilename.'.'.CFileHelper::getExtension($uploadfile);
                        rename($uploadfile, 'files/'.$file);
                        chmod('files/'.$file, 0755);
                        // add the file info to database
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
                        $files->address = $file;
                        $files->date = new CDbExpression('NOW()');
                        $files->slug = $slug = Utils::slugify($_POST['name']).'-'.$utilsObj->get_random_string('0123456789', 5);
                        $files->published = FALSE;
                        $files->uploaded_by = NULL;
                        $fileSaved = $files->save();
                        $fileID = Files::model()->findByAttributes(array('slug'=>$slug));
                        if(isset($_POST['tags'])){
                            $arrTags = str_getcsv($_POST['tags']);
                            foreach($arrTags as $tag){
                                $tagDb = Tags::model()->findByAttributes(array('tag'=>  $tag));
                                // if its a new tag
                                if($tagDb==NULL){
                                    $newTag = new Tags;
                                    $newTag->tag = $tag;
                                    $newTag->save();
                                }
                                $tagDb = Tags::model()->findByAttributes(array('tag'=>  $tag));                                 
                                $filesToTag = new FileToTags;
                                $filesToTag->file = $fileID->id;
                                $filesToTag->tag = $tagDb->id;
                                $filesToTag->Save();
                            }
                        }
                        if($fileSaved){
                            $this->_sendResponse(200, 'OK');
                        }
                        else{
                            $this->_sendResponse(407, 'Error in save to database');
                        }
                    }
                    else{
                        $this->_sendResponse(405, 'File type not allowed '.$mime);
                        unlink($uploadfile);
                    }
                    
                }
            break;
            case 'users':
                $user = new Users;
                $user->username = $_POST['username'];
                $user->passwordSave = $_POST['password'];
                $user->repeatPassword = $_POST['password'];
                $user->email = $_POST['email'];
                $user->role = 0;
                $user->save();
                $this->_sendResponse(200, 'Done');
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
    
}