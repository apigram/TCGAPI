<?php
/* @var $this UserKeyController */
/* @var $data UserKey */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('key_title')); ?>:</b>
	<?php echo CHtml::encode($data->key_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('api_key')); ?>:</b>
	<?php echo CHtml::encode($data->api_key); ?>
	<br />


</div>