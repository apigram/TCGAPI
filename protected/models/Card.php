<?php

/**
 * This is the model class for table "tcg_card".
 *
 * The followings are the available columns in table 'tcg_card':
 * @property integer $id
 * @property string $image_data
 * @property string $image_type
 * @property string $name
 * @property string $notes
 * @property integer $quantity
 * @property string $date_modified
 * @property integer $user_id
 *
 * The followings are the available model relations:
 * @property TcgUsers $user
 */
class Card extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'tcg_card';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('image_data, image_type, name, quantity, date_modified, user_id', 'required'),
            array('quantity, user_id', 'numerical', 'integerOnly' => true),
            array('image_type', 'length', 'max' => 20),
            array('name', 'length', 'max' => 100),
            array('notes', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'id, image_data, image_type, name, notes, quantity, date_modified, user_id',
                'safe',
                'on' => 'search',
            ),
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
            'image_data' => 'Image Data',
            'image_type' => 'Image Type',
            'name' => 'Name',
            'notes' => 'Notes',
            'quantity' => 'Quantity',
            'date_modified' => 'Date Modified',
            'user_id' => 'User',
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
        $criteria->compare('image_data', $this->image_data, true);
        $criteria->compare('image_type', $this->image_type, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('notes', $this->notes, true);
        $criteria->compare('quantity', $this->quantity);
        $criteria->compare('date_modified', $this->date_modified, true);
        $criteria->compare('user_id', $this->user_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function beforeValidate()
    {
        $this->date_modified = date('Y-m-d H:i:s');

        return parent::beforeSave();
    }

    public function toJSON()
    {
        $output = array();

        foreach ($this->attributes as $var => $value)
        {
            if ($var != 'id' and $var != 'user_id')
                $output[$var] = $value;
        }

        return CJSON::encode($output);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Card the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
