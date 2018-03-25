<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mulu".
 *
 * @property int $id
 * @property string $name 名称
 * @property string $ico 图标
 * @property string $url 地址
 * @property int $parent_id 父ID
 */
class Mulu extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name', 'ico', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'ico' => '图标',
            'url' => '地址',
            'parent_id' => '父ID',
        ];
    }
    public static function menu(){

        $menu=[
            [
            'label' => '商品管理',
            'icon' => 'share',
            'url' => '#',
            'items' => [
                ['label' => '商品列表', 'icon' => 'dashboard', 'url' => ['/goods/index'],],
                ['label' => '添加商品', 'icon' => 'file-code-o', 'url' => ['/goods/add'],],

            ],
        ],
            ];

        $menu=[];
        $menus=self::find()->where(['parent_id'=>0])->all();
        foreach ($menus as $menu){
            $newMenu=[];
            $newMenu['label']=$menu->name;
            $newMenu['icon']=$menu->ico;
            $newMenu['url']=$menu->url;
            //找到二级目录
            $menusSon=self::find()->where(['parent_id'=>$menu->id])->all();
            foreach ($menusSon as $menuSon){
                $newMenuSon=[];
                $newMenuSon['label']=$menuSon->name;
                $newMenuSon['icon']=$menuSon->ico;
                $newMenuSon['url']=$menuSon->url;

                $newMenu['items'][]=$newMenuSon;
            }

            $menuAll[]=$newMenu;
        }

        return $menuAll;
    }
}
