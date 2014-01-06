<?php

/**
 * This is the model class for table "files".
 *
 * The followings are the available columns in table 'files':
 * @property string $id
 * @property string $name
 * @property integer $department
 * @property string $description
 * @property string $content
 * @property string $address
 * @property string $date
 * @property string $slug
 * @property string $uploaded_by
 *
 * The followings are the available model relations:
 * @property FileToTags[] $fileToTags
 * @property Departments $department0
 * @property Users $uploadedBy
 */
class Files extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'files';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, address, date, slug', 'required'),
			array('department', 'numerical', 'integerOnly'=>true),
			array('address, slug', 'length', 'max'=>400),
			array('uploaded_by', 'length', 'max'=>20),
			array('description, content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, department, description, content, address, date, slug, uploaded_by', 'safe', 'on'=>'search'),
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
			'fileToTags' => array(self::HAS_MANY, 'FileToTags', 'file'),
			'department0' => array(self::BELONGS_TO, 'Departments', 'department'),
			'uploadedBy' => array(self::BELONGS_TO, 'Users', 'uploaded_by'),
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
			'department' => 'Department',
			'description' => 'Description',
			'content' => 'Content',
			'address' => 'Address',
			'date' => 'Date',
			'slug' => 'Slug',
			'uploaded_by' => 'Uploaded By',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('department',$this->department);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('uploaded_by',$this->uploaded_by,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Files the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}