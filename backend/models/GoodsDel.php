<?php
namespace backend\models;
use yii\db\ActiveRecord;
class GoodsDel extends ActiveRecord
{
    public static function tableName()
    {
        return 'goods_category';
    }
}