<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to the <i>Pokemon Trading Card Game Collection API</i>!</h1>

<p>The API uses the following endpoints:</p>

<table>
	<thead>
		<tr>
			<th>Action</th>
			<th>URL</th>
			<th>Request Type</th>
			<th>Request body</th>
			<th>Sample Response body</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Create</td>
			<td>/api/card</td>
			<td>POST</td>
			<td>
				<pre>name={string}
image_data={binary}
image_type={MIME-type}
notes={string}
quantity={number}
				</pre>
			</td>
			<td>
				<pre>{
	"name":{name},
	"image_data":{image_data},
	"image_type":{image_type},
	"notes":{notes},
	"quantity":{quantity},
	"id":{id}
}
				</pre>
			</td>
		</tr>
		<tr>
			<td>Get summary of all cards</td>
			<td>/api/card</td>
			<td>GET</td>
			<td>
				Nil
			</td>
			<td>
				<pre>[{
	"id":{id},
	"name":{name},
	"image_data":{image_data},
	"image_type":{image_type},
	"quantity":{quantity},
}]
				</pre>
			</td>
		</tr>
		<tr>
			<td>Get single card detail</td>
			<td>/api/card/{id}</td>
			<td>GET</td>
			<td>
				Nil
			</td>
			<td>
				<pre>{
	"id":{id},
	"name":{name},
	"image_data":{image_data},
	"image_type":{image_type},
	"notes":{notes},
	"quantity":{quantity}
}
				</pre>
			</td>
		</tr>
		<tr>
			<td>Update single card</td>
			<td>/api/card/{id}</td>
			<td>PUT</td>
			<td>
				<pre>{
	"name":{name},
	"image_data":{image_data},
	"image_type":{image_type},
	"notes":{notes},
	"quantity":{quantity}
}
				</pre>
			</td>
			<td>
				<pre>{
	"name":{name},
	"image_data":{image_data},
	"image_type":{image_type},
	"notes":{notes},
	"quantity":{quantity},
	"id":{id}
}
				</pre>
			</td>
		</tr>
		<tr>
			<td>Delete single card</td>
			<td>/api/card/{id}</td>
			<td>DELETE</td>
			<td>
				Nil
			</td>
			<td>
				# of records deleted.
			</td>
		</tr>
	</tbody>
</table>
