<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - About';
$this->breadcrumbs=array(
	'About',
);
?>
<h1>About</h1>

<p>
  The Pokemon Trading Card Game Collection API is a RESTful microservice that allows third-party applications to store card collections in the cloud, where they can be viewed and assembled into decks.
  Registered users can invoke actions on the microservice using generated API keys for per-client authentication.
</p>
