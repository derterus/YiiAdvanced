<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FileForm extends Model
{
    public $file;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
}
