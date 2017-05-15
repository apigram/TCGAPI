<?php
/* @var $this UserKeyController */
/* @var $model UserKey */

$this->breadcrumbs = array(
    'Home' => array('site/index'),
    'Manage API Keys',
);

$this->menu = array(
    array('label' => 'Create API Key', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#user-key-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage API Keys</h1>

<p>
  You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
  or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
    <?php $this->renderPartial('_search', array(
        'model' => $model,
    )); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'user-key-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'key_title',
        'api_key',
        array(
            'class' => 'CButtonColumn',
        ),
    ),
)); ?>
