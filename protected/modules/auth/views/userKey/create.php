<?php
/* @var $this UserKeyController */
/* @var $model UserKey */

$this->breadcrumbs = array(
    'Manage API Keys' => array('admin'),
    'Create',
);

$this->menu = array(
    array('label' => 'Manage API Keys', 'url' => array('admin')),
);
?>

  <h1>Create API Key</h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>