<?php
/* @var $this UserKeyController */
/* @var $model UserKey */

$this->breadcrumbs = array(
    'Manage API Keys' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'Create API Key', 'url' => array('create')),
    array('label' => 'View API Key', 'url' => array('view', 'id' => $model->id)),
    array('label' => 'Manage API Keys', 'url' => array('admin')),
);
?>

  <h1>Update API Key</h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>