<?php
/* @var $this FileToTagsController */
/* @var $model FileToTags */

$this->breadcrumbs=array(
	'File To Tags'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List FileToTags', 'url'=>array('index')),
	array('label'=>'Create FileToTags', 'url'=>array('create')),
	array('label'=>'Update FileToTags', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete FileToTags', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage FileToTags', 'url'=>array('admin')),
);
?>

<h1>View FileToTags #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'file',
		'tag',
	),
)); ?>
