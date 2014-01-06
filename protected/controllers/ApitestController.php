<?php

class ApitestController extends Controller
{
	public function actionIndex()
	{       
            $model = new ApiTestForm;
            if(isset($_POST['ApiTestForm'])){
                //$url = 'http://getrti/index.php/api/files';
                $options = array(
                 
                    'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => $_POST['ApiTestForm'],
                    ),
                );
                $context  = stream_context_create($options);
                $result = CJSON::decode(file_get_contents($url, false, $context));
                var_dump($_POST['ApiTestForm']);
            }
            $this->render('index', array('model'=>$model));
            
	}
}