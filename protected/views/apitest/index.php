<?php
$this->breadcrumbs=array(
	'Apitest',
);?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

      
        <div class="form">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'upload-form',
                'enableClientValidation'=>true,
                'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                ),
            )); ?>


            <p class="note">Fields with <span class="required">*</span> are required.</p>

            <div class="row">
                <?php echo $form->labelEx($model,'name'); ?>
                <?php echo $form->textField($model,'name'); ?>
                <?php echo $form->error($model,'name'); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($model, 'file'); ?>
                <?php echo $form->fileField($model, 'file'); ?>
                <?php echo $form->error($model, 'file'); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($model,'department'); ?>
                <?php echo $form->textField($model,'department'); ?>
                <?php echo $form->error($model,'department'); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($model,'description'); ?>
                <?php echo $form->textField($model,'description'); ?>
                <?php echo $form->error($model,'description'); ?>
            </div>

            <div class="row buttons">
                <?php echo CHtml::submitButton('Upload'); ?>
            </div>

        <?php $this->endWidget(); ?>
        </div><!-- form -->