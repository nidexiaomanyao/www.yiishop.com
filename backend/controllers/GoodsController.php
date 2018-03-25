<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Brand;
use backend\models\Category;
use backend\models\Goods;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class GoodsController extends \yii\web\Controller
{

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
    public function actionIndex(){


                // 创建一个 DB 查询来获得所有 status 为 1 的文章
        $query = Goods::find();
        $minPrice=\Yii::$app->request->get('minPrice');
        $maxPrice=\Yii::$app->request->get('maxPrice');
        $keyword=\Yii::$app->request->get('keyword');
        $status=\Yii::$app->request->get('status');
        //家条件
        if ($minPrice){
            $query->andWhere("shop_price>={$minPrice}");
        }

        if ($maxPrice){
            $query->andWhere(['<=','shop_price',$maxPrice]);
        }
        //货号
        if ($keyword!==""){
            $query->andWhere("name like '%{$keyword}%' or sn like '%{$keyword}%'");

               }
            //状态
        if ($status==="0" || $status==="1"){
            $query->andWhere(['status'=>$status]);
        }



        // 得到文章的总数（但是还没有从数据库取数据）
        $count = $query->count();

        // 使用总数来创建一个分页对象
        $pagination = new Pagination(
            [
                'totalCount' => $count,
                'pageSize'=>3,


            ]);

        // 使用分页对象来填充 limit 子句并取得文章数据
        $goods = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index',compact('pagination','goods'));
    }
    //添加
    public function actionAdd()
    {
        $good = new Goods();
        //商品详情
        $intro = new GoodsIntro();
        //分类
        $cates = Category::find()->orderBy('tree,lft')->all();
        $cates = ArrayHelper::map($cates, 'id', 'nameText');
        //品牌
        $brands = Brand::find()->all();
        $brands = ArrayHelper::map($brands, 'id', 'name');
//post
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $good->load($request->post());
            //绑定商品详情
            $intro->load($request->post());
            //手太严重
            if ($good->validate() && $intro->validate()) {

                //判断SN
                if (!$good->sn ) {
                    //没有.自动生成
                    $dayTime=strtotime(date('Ymd'));
                    $count= Goods::find()->where(['>','create_at',$dayTime])->count();
                    $count+=1;
                    $countStr="0000".$count;

                    $countStr=substr($countStr,-5);


                    $good->sn=date('Ymd').$countStr;
                }
                //保存数据
                if ($good->save()) {
                    $intro->goods_id=$good->id;
                    $intro->save();
                    //多图保存
                    foreach ($good->images as $image){

                    $gallery=new GoodsGallery();
                    $gallery->goods_id=$good->id;
                    $gallery->path=$image;
                    //保存图片
                    $gallery->save();
                    }
                    //提示
                    \Yii::$app->session->setFlash('success','添加成功');
               return $this->redirect(['index']);
                }

            } else {
                //TODO
                var_dump($good->errors);exit();
            }
        }



        return $this->render('add',compact('good','cates','brands','intro'));
    }
    //编辑
    public function actionEdit($id)
    {
        $good = Goods::findOne($id);
        //商品详情
        $intro =GoodsIntro::findOne(['goods_id'=>$id]);
        //分类
        $cates = Category::find()->orderBy('tree,lft')->all();
        $cates = ArrayHelper::map($cates, 'id', 'nameText');
        //品牌
        $brands = Brand::find()->all();
        $brands = ArrayHelper::map($brands, 'id', 'name');
//post
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $good->load($request->post());
            //绑定商品详情
            $intro->load($request->post());
            //手太严重
            if ($good->validate() && $intro->validate()) {

                //判断SN
                if (!$good->sn ) {
                    //没有.自动生成
                    $dayTime=strtotime(date('Ymd'));
                    $count= Goods::find()->where(['>','create_at',$dayTime])->count();
                    $count+=1;
                    $countStr="0000".$count;

                    $countStr=substr($countStr,-5);


                    $good->sn=date('Ymd').$countStr;
                }
                //保存数据
                if ($good->save()) {
//                    $intro->goods_id=$good->id;
                    $intro->save();
                    //回显删除图片
                    GoodsGallery::deleteAll(['goods_id'=>$id]);
                    //多图保存
                    foreach ($good->images as $image){

                        $gallery=new GoodsGallery();
                        $gallery->goods_id=$good->id;
                        $gallery->path=$image;
                        //保存图片
                        $gallery->save();
                    }
                    //提示
                    \Yii::$app->session->setFlash('success','编辑成功');
                    return $this->redirect(['index']);
                }

            } else {
                //TODO
                var_dump($good->errors);exit();
            }
        }
        //多图回显
        $images=GoodsGallery::find()->where(['goods_id'=>$id])->all();
        $images=array_column($images,'path');

        $good->images=$images;
        return $this->render('add',compact('good','cates','brands','intro'));
    }
    public function actionDel($id)
    {
        $article=Goods::findOne($id);
        $article->delete();
        $this->redirect(['goods/index']);
    }


}
