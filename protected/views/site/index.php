<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<?php if (Yii::app()->user->hasState('role') and Yii::app()->user->getState('role') !== 'standard'):?>
  <h1>Welcome, <i><?php echo Yii::app()->user->name?></i></h1>

  <p>The Pokemon Trading Card Game Collection API supports the following RESTful actions (NOTE: All actions below require an API key as a URL parameter, identified by 'api'):</p>

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
<?php elseif (Yii::app()->user->hasState('role') and Yii::app()->user->getState('role') === 'standard'): ?>
    <h1>Welcome</h1>
    <p>Please login via a compatible third-party application.</p>
<?php else: ?>
  <h1>Welcome</h1>
  <p>Please login using the Login link at the top of the screen.</p>
<?php endif;?>
