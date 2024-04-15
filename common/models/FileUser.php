<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * FileUser model
 *
 * @property integer $id
 * @property integer $file_id
 * @property integer $user_id
 * @property integer $access_level
 */
class FileUser extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%file_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_id', 'user_id', 'access_level'], 'required'],
            [['file_id', 'user_id','access_level'], 'integer'],
        ];
    }
}
