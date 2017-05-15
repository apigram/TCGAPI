<?php
/* @var $this UserKeyController */
/* @var $model UserKey */

$this->breadcrumbs = array(
    'User Keys' => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => 'Create API Key', 'url' => array('create')),
    array('label' => 'Update API Key', 'url' => array('update', 'id' => $model->id)),
    array(
        'label' => 'Delete API Key',
        'url' => '#',
        'linkOptions' => array(
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => 'Are you sure you want to delete this item?',
        ),
    ),
    array('label' => 'Manage API Keys', 'url' => array('admin')),
);
?>

<h1>View API Key</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'key_title',
        'api_key',
    ),
)); ?>
