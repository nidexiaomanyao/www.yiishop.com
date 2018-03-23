<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property string $name 商品名称
 * @property string $sn 商品货号
 * @property string $logo LOGO
 * @property int $category_id 商品分类
 * @property int $brand_id 品牌分类
 * @property string $market_price 市场价格
 * @property string $shop_price 商品价格
 * @property string $stock 库存
 * @property string $status 1=正常  0=回收站
 * @property string $sort 排序
 * @property string $create_at
 */
class Goods extends \yii\db\ActiveRecord
{
    //多图
    public $images;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['create_at', 'update_at'],
                    self::EVENT_BEFORE_UPDATE => ['update_at'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                // 'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['market_price', 'shop_price'], 'number'],
        [['name','logo','category_id','brand_id','market_price', 'shop_price', 'stock', 'status', 'sort','images'],'required'],
            [['sn'],'unique'],
            ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '商品货号',
            'logo' => 'LOGO',
            'category_id' => '商品分类',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'status' => '1=正常  0=回收站',
            'sort' => '排序',
            'create_at' => 'Create At',
        ];
    }
}
