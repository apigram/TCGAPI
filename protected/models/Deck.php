<?php

/**
 * This is the model class for table "tcg_decks".
 *
 * The followings are the available columns in table 'tcg_decks':
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property date $date_modified
 *
 * The followings are the available model relations:
 * @property Card[] $cards
 * @property User $user
 */
class Deck extends CActiveRecord
{
    public $numCards = 0;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'tcg_decks';
    }

    public function cardAdded($quantity)
    {
        $this->numCards += $quantity;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, user_id, date_modified', 'required'),
            array('user_id', 'numerical', 'integerOnly' => true),
            array('numCards', 'numerical', 'min' => 60, 'max'=> 60),
            array('name', 'length', 'max' => 30),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, user_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'cards' => array(self::MANY_MANY, 'Card', 'tcg_deck_cards(deck_id,card_id)'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'user_id' => 'User',
            'numCards' => 'Number of cards'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('user_id', $this->user_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getSummary()
    {
        $output = array();

        // though there is currently only one user-visible field in this model, we will use a loop in case more fields are added in the future.
        foreach ($this->attributes as $var => $value)
        {
            if ($var != 'user_id' and $var != 'date_modified')
                $output[$var] = $value;
        }

        return CJSON::encode($output);
    }

    public function getDetails()
    {
        $output = array();
        $cards = array();

        // though there is currently only one user-visible field in this model, we will use a loop in case more fields are added in the future.
        foreach ($this->attributes as $var => $value)
        {
            if ($var != 'user_id')
                $output[$var] = $value;
        }

        foreach ($this->cards as $card)
            $cards[] = CJSON::decode($card->getSummary($this->id));

        $output['cards'] = $cards;

        return CJSON::encode($output);
    }

    public function beforeValidate()
    {
        $this->date_modified = date('Y-m-d H:i:s');

        return parent::beforeValidate();
    }

    public function afterSave()
    {
        // reset the counter.
        $this->numCards = 0;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Deck the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
