<?php

/**
 * This is the model class for table "tcg_user_keys".
 *
 * The followings are the available columns in table 'tcg_user_keys':
 * @property integer $id
 * @property integer $user_id
 * @property string $key_title
 * @property string $api_key
 *
 * The followings are the available model relations:
 * @property TcgUsers $user
 */
class UserKey extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'tcg_user_keys';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, key_title, api_key', 'required'),
            array('user_id', 'numerical', 'integerOnly' => true),
            array('key_title', 'length', 'max' => 50),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('user_id, key_title', 'safe', 'on' => 'search'),
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
            'user_id' => 'User',
            'key_title' => 'Description',
            'api_key' => 'API Key',
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
        $user = User::model()->find('username=?', array(Yii::app()->user->name));

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $user->id);
        $criteria->compare('key_title', $this->key_title, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function beforeValidate()
    {
        // Associate the API key with the active user.
        if (!isset($this->user_id)) {
            $user = User::model()->find('username=?', array(Yii::app()->user->name));
            $this->user_id = $user->id;
        }

        // Generate the API key if it hasn't already been generated.
        if (!isset($this->api_key)) {
            $this->api_key = md5(uniqid(rand(), true));
        }

        return parent::beforeValidate();
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CActiveRecord the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
