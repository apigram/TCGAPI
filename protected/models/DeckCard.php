<?php

/**
 * This is the model class for table "tcg_deck_cards".
 *
 * The followings are the available columns in table 'tcg_deck_cards':
 * @property integer $id
 * @property integer $deck_id
 * @property integer $card_id
 * @property integer $quantity
 * @property date $date_modified
 *
 * The followings are the available model relations:
 * @property Deck $deck
 * @property Card $card
 */
class DeckCard extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'tcg_deck_cards';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('deck_id, card_id, quantity, date_modified', 'required'),
            array('deck_id, card_id, quantity', 'numerical', 'integerOnly' => true),
            array('quantity', 'numerical', 'min' => 1, 'max' => 4), // no more than 4 identically named cards can be added to a deck. Additionally, quantity must be at least 1.
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, deck_id, card_id, quantity', 'safe', 'on' => 'search'),
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
            'deck' => array(self::BELONGS_TO, 'Deck', 'deck_id'),
            'card' => array(self::BELONGS_TO, 'Card', 'card_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'deck_id' => 'Deck',
            'card_id' => 'Card',
            'quantity' => 'Quantity',
        );
    }

    public function beforeValidate()
    {
        $this->date_modified = date('Y-m-d H:i:s');

        return parent::beforeValidate();
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
        $criteria->compare('deck_id', $this->deck_id);
        $criteria->compare('card_id', $this->card_id);
        $criteria->compare('quantity', $this->quantity);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DeckCard the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
