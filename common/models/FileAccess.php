<?php
namespace common\models;

use Yii;

/**
 * This is the model class for table "file_access".
 *
 * @property int $id
 * @property int $file_id
 * @property int $user_id
 */
class FileAccess extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file_access';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_id', 'user_id'], 'required'],
            [['file_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_id' => 'File ID',
            'user_id' => 'User ID',
        ];
    }
}
