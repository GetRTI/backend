<?php
/* @var $this FileToTagsController */
/* @var $model FileToTags */

$this->breadcrumbs=array(
	'File To Tags'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List FileToTags', 'url'=>array('index')),
	array('label'=>'Create FileToTags', 'url'=>array('create')),
	array('label'=>'View FileToTags', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage FileToTags', 'url'=>array('admin')),
);
?>

<h1>Update FileToTags <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>