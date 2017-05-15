<?php

class ApiController extends Controller
{
    Const APPLICATION_ID = 'TCGAPI';
    private $format = 'json';

    public function filters()
    {
        return array();
    }

    /**
     * Get a list of all cards/decks registered by the user.
     */
    public function actionList()
    {
        // Get the respective model instance
        $user = $this->_checkAuth();
        switch ($_GET['model']) {
            case 'card':
                $models = Card::model()->findAll('user_id=?', array($user->id));
                break;
            case 'deck':
                $models = Deck::model()->findAll('user_id=?', array($user->id));
                break;
            default:
                // Model not implemented error
                $this->_sendResponse(501, sprintf(
                    'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                    $_GET['model']));
                Yii::app()->end();
        }
        // Did we get some results?
        if (empty($models)) {
            // No
            $this->_sendResponse(200,
                sprintf('No items were found for model <b>%s</b> for this user.', $_GET['model']));
        } else {
            // Prepare response
            $rows = array();
            foreach ($models as $model)
                $rows[] = CJSON::decode($model->toJSON());
            // Send the response
            $this->_sendResponse(200, CJSON::encode($rows), 'application/json');
        }
    }

    /**
     * Get a single card/deck registered to the user.
     */
    public function actionView()
    {
        $user = $this->_checkAuth();
        // Check if id was submitted via GET
        if (!isset($_GET['id']))
            $this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing');

        switch ($_GET['model']) {
            // Find respective model
            case 'card':
                $model = Card::model()->findByPk($_GET['id']);
                break;
            case 'deck':
                $model = Deck::model()->findByPk($_GET['id']);
                break;
            default:
                $this->_sendResponse(501, sprintf(
                    'Mode <b>view</b> is not implemented for model <b>%s</b>',
                    $_GET['model']));
                Yii::app()->end();
        }
        // Did we find the requested model? If not, raise an error
        if (is_null($model) or $model->user_id != $user->id)
            $this->_sendResponse(404, 'No Item found with id ' . $_GET['id']);
        else
            $this->_sendResponse(200, $model->toJSON(), 'application/json');
    }

    /**
     * Create a new card or deck.
     */
    public function actionCreate()
    {
        $json = file_get_contents('php://input');
        $post_vars = CJSON::decode($json);
        $user = $this->_checkAuth();
        switch ($_GET['model']) {
            // Get an instance of the respective model
            case 'card':
                $model = new Card;
                break;
            case 'deck':
                $model = new Deck;
                $cardList = array();
                break;
            default:
                $this->_sendResponse(501,
                    sprintf('Mode <b>create</b> is not implemented for model <b>%s</b>',
                        $_GET['model']));
                Yii::app()->end();
        }
        // Try to assign POST values to attributes
        foreach ($post_vars as $var => $value) {
            // Does the model have this attribute? If not raise an error
            if ($model->hasAttribute($var)) {
                $model->$var = $value;
            } elseif ($var === 'cards') {
                foreach ($value as $card) {
                    $deckCard = new DeckCard;
                    foreach ($card as $i => $val) {
                        if ($deckCard->hasAttribute($i)) {
                            $deckCard->$i = $val;
                        } else {
                            $this->_sendResponse(500,
                                sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $i,
                                    $_GET['model']));
                        }
                    }
                    $cardList[] = $deckCard;
                }
            } else {
                $this->_sendResponse(500,
                    sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var, $_GET['model']));
            }
        }
        $model->user_id = $user->id;

        // Try to save the model
        $transaction = Yii::app()->db->beginTransaction();
        if ($model->save()) {
            foreach ($cardList as $card) {
                // If at least one card association cannot be saved, terminate.
                if (!$card->save()) {
                    // Errors occurred
                    $transaction->rollback();
                    $msg = "<h1>Error</h1>";
                    $msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
                    $msg .= "<ul>";
                    foreach ($card->errors as $attribute => $attr_errors) {
                        $msg .= "<li>Attribute: $attribute</li>";
                        $msg .= "<ul>";
                        foreach ($attr_errors as $attr_error) {
                            $msg .= "<li>$attr_error</li>";
                        }
                        $msg .= "</ul>";
                    }
                    $msg .= "</ul>";
                    $this->_sendResponse(500, $msg);
                }
            }
            $transaction->commit();
            $this->_sendResponse(200, $model->toJSON(), 'application/json');
        } else {
            // Errors occurred
            $transaction->rollback();
            $msg = "<h1>Error</h1>";
            $msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
            $msg .= "<ul>";
            foreach ($model->errors as $attribute => $attr_errors) {
                $msg .= "<li>Attribute: $attribute</li>";
                $msg .= "<ul>";
                foreach ($attr_errors as $attr_error) {
                    $msg .= "<li>$attr_error</li>";
                }
                $msg .= "</ul>";
            }
            $msg .= "</ul>";
            $this->_sendResponse(500, $msg);
        }
    }

    /**
     * Update an existing card or deck.
     */
    public function actionUpdate()
    {
        $user = $this->_checkAuth();
        // Parse the PUT parameters. This didn't work: parse_str(file_get_contents('php://input'), $put_vars);
        $json = file_get_contents('php://input'); //$GLOBALS['HTTP_RAW_POST_DATA'] is not preferred: http://www.php.net/manual/en/ini.core.php#ini.always-populate-raw-post-data
        $put_vars = CJSON::decode($json, true);  //true means use associative array

        switch ($_GET['model']) {
            // Find respective model
            case 'card':
                $model = Card::model()->findByPk($_GET['id']);
                break;
            case 'deck':
                $model = Deck::model()->findByPk($_GET['id']);
                $cardList = array();
                foreach (DeckCard::model()->findAll('deck_id=?', array($_GET['id'])) as $card)
                    $cardList[$card->card_id] = $card;
                break;
            default:
                $this->_sendResponse(501,
                    sprintf('Error: Mode <b>update</b> is not implemented for model <b>%s</b>',
                        $_GET['model']));
                Yii::app()->end();
        }
        // Did we find the requested model? If not, raise an error
        if ($model === null or $model->user_id != $user->id) {
            $this->_sendResponse(400,
                sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b> for this user.",
                    $_GET['model'], $_GET['id']));
        }

        // Try to assign PUT parameters to attributes
        foreach ($put_vars as $var => $value) {
            // Does model have this attribute? If not, raise an error
            if ($model->hasAttribute($var)) {
                $model->$var = $value;
            }
            elseif ($var === 'cards') {
                foreach ($value as $card) {
                    // If the card is not in the array, create a new one.
                    if (!isset($cardList[$card->card_id])) {
                        $deckCard = new DeckCard;
                        foreach ($card as $i => $val) {
                            if ($deckCard->hasAttribute($i)) {
                                $deckCard->$i = $val;
                            } else {
                                $this->_sendResponse(500,
                                    sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $i,
                                        $_GET['model']));
                            }
                        }
                    }
                    else
                    {
                        foreach ($card as $i => $val) {
                            if ($cardList[$card->card_id]->hasAttribute($i)) {
                                $cardList[$card->card_id]->$i = $val;
                            } else {
                                $this->_sendResponse(500,
                                    sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $i,
                                        $_GET['model']));
                            }
                        }
                    }

                    $cardList[$deckCard->card_id] = $deckCard;
                }
            }
            else {
                $this->_sendResponse(500,
                    sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>',
                        $var, $_GET['model']));
            }
        }

        $model->user_id = $user->id;

        // Try to save the model
        $transaction = Yii::app()->db->beginTransaction();
        if ($model->save()) {
            foreach ($cardList as $card) {
                // If at least one card association cannot be saved, terminate.
                if (!$card->save()) {
                    // Errors occurred
                    $transaction->rollback();
                    $msg = "<h1>Error</h1>";
                    $msg .= sprintf("Couldn't update model <b>%s</b>", $_GET['model']);
                    $msg .= "<ul>";
                    foreach ($card->errors as $attribute => $attr_errors) {
                        $msg .= "<li>Attribute: $attribute</li>";
                        $msg .= "<ul>";
                        foreach ($attr_errors as $attr_error)
                            $msg .= "<li>$attr_error</li>";
                        $msg .= "</ul>";
                    }
                    $msg .= "</ul>";
                    $this->_sendResponse(500, $msg);
                }
            }
            $transaction->commit();
            $this->_sendResponse(200, $model->toJSON(), 'application/json');
        }
        else {
            // prepare the error $msg
            // Errors occurred
            $transaction->rollback();
            $msg = "<h1>Error</h1>";
            $msg .= sprintf("Couldn't update model <b>%s</b>", $_GET['model']);
            $msg .= "<ul>";
            foreach ($model->errors as $attribute => $attr_errors) {
                $msg .= "<li>Attribute: $attribute</li>";
                $msg .= "<ul>";
                foreach ($attr_errors as $attr_error)
                    $msg .= "<li>$attr_error</li>";
                $msg .= "</ul>";
            }
            $msg .= "</ul>";
            $this->_sendResponse(500, $msg);
        }
    }

    /**
     * Delete an existing card or deck.
     */
    public function actionDelete()
    {
        $user = $this->_checkAuth();
        switch ($_GET['model']) {
            // Load the respective model
            case 'card':
                $model = Card::model()->findByPk($_GET['id']);
                break;
            case 'deck':
                $model = Deck::model()->findByPk($_GET['id']);
                break;
            default:
                $this->_sendResponse(501,
                    sprintf('Error: Mode <b>delete</b> is not implemented for model <b>%s</b>',
                        $_GET['model']));
                Yii::app()->end();
        }
        // Was a model found? If not, raise an error
        if ($model === null or $model->user_id != $user->id)
            $this->_sendResponse(400,
                sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b> for this user.",
                    $_GET['model'], $_GET['id']));

        // Delete the model
        $num = $model->delete();
        if ($num > 0)
            $this->_sendResponse(200, $num, 'application/json');    //this is the only way to work with backbone
        else
            $this->_sendResponse(500,
                sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $_GET['id']));
    }

    private function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
    {
        // set the status
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        header($status_header);
        // and the content type
        header('Content-type: ' . $content_type);

        // pages with body are easy
        if ($body != '') {
            // send the body
            echo $body;
        } // we need to create the body if none is passed
        else {
            // create some body messages
            $message = '';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch ($status) {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on
            // (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templated in a real-world solution
            $body = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
</head>
<body>
    <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
    <p>' . $message . '</p>
    <hr />
    <address>' . $signature . '</address>
</body>
</html>';

            echo $body;
        }
        Yii::app()->end();
    }

    private function _getStatusCodeMessage($status)
    {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }

    private function _checkAuth()
    {
        // Check if the API key matches any on the database.
        if (!isset($_GET['api'])) {
            // Error: Unauthorized
            $this->_sendResponse(401);
        }
        $api_user = UserKey::model()->find('api_key=?', array($_GET['api']));

        // Ensure that a valid user is registered to the given API key.
        if ($api_user->user === null) {
            // Error: Unauthorized
            $this->_sendResponse(401, 'Error: API key is not registered to a valid user.');
        }

        return $api_user->user;
    }
}