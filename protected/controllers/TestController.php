<?php

class TestController extends Controller
{
	public function actionIndex()
	{
                $user = new Users;
                $user->username = "abc";
                $user->email = "saurav@linuxb.in";
                $user->passwordSave = "abcd";
                $user->role = 0;
                $user->save();
                $this->render('index');
	}

	// -----------------------------------------------------------
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}