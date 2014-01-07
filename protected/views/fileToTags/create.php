<?php
/* @var $this FileToTagsController */
/* @var $model FileToTags */

$this->breadcrumbs=array(
	'File To Tags'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List FileToTags', 'url'=>array('index')),
	array('label'=>'Manage FileToTags', 'url'=>array('admin')),
);
?>

<h1>Create FileToTags</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>