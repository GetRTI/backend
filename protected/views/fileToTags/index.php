<?php
/* @var $this FileToTagsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'File To Tags',
);

$this->menu=array(
	array('label'=>'Create FileToTags', 'url'=>array('create')),
	array('label'=>'Manage FileToTags', 'url'=>array('admin')),
);
?>

<h1>File To Tags</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
