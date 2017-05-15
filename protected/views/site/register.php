<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-register-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // See class documentation of CActiveForm for details on this,
        // you need to use the performAjaxValidation()-method described there.
        'enableAjaxValidation' => false,
    )); ?>

  <p>Standard users are able to maintain their collections on this microservice. Developers have the added ability to generate API keys to allow their third-party applications to interface with this microservice.</p>
  <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

  <div class="row">
      <?php echo $form->labelEx($model, 'first_name'); ?>
      <?php echo $form->textField($model, 'first_name'); ?>
      <?php echo $form->error($model, 'first_name'); ?>
  </div>

  <div class="row">
      <?php echo $form->labelEx($model, 'surname'); ?>
      <?php echo $form->textField($model, 'surname'); ?>
      <?php echo $form->error($model, 'surname'); ?>
  </div>

  <div class="row">
      <?php echo $form->labelEx($model, 'username'); ?>
      <?php echo $form->textField($model, 'username'); ?>
      <?php echo $form->error($model, 'username'); ?>
  </div>

  <div class="row">
      <?php echo $form->labelEx($model, 'password'); ?>
      <?php echo $form->passwordField($model, 'password'); ?>
      <?php echo $form->error($model, 'password'); ?>
  </div>

  <div class="row">
      <?php echo $form->labelEx($model, 'email'); ?>
      <?php echo $form->textField($model, 'email'); ?>
      <?php echo $form->error($model, 'email'); ?>
  </div>

  <div class="row">
      <?php echo $form->labelEx($model, 'user_type'); ?>
      <?php echo $form->dropDownList($model, 'user_type',array('standard'=>'Standard', 'developer'=>'Developer')); ?>
      <?php echo $form->error($model, 'user_type'); ?>
  </div>


  <div class="row buttons">
      <?php echo CHtml::submitButton('Submit'); ?>
  </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->