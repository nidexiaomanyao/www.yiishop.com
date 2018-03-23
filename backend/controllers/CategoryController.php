<?php

namespace backend\controllers;

use backend\models\Category;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\helpers\Json;

class CategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $cates=Category::find()->orderBy('tree,lft')->all();
        return $this->render('index',compact('cates'));
    }

    /**
     * @return string
     */
    public function actionAdd(){
        $cate=new Category();
        //查出数据
        $cates=Category::find()->asArray()->all();
        $cates[]=['id'=>0,'name'=>'一级分类','parent_id'=>0];
        //转为JSON字符串
        $catesjson=Json::encode($cates);

//        var_dump($catesJson);exit();

        $request=\Yii::$app->request;
        if ($request->isPost){
            $cate->load($request->post());
            if ($cate->validate()){
                //添加一级分了
                if ($cate->parent_id==0){
                    $cate->makeRoot();
                    //厂家成功
                    \Yii::$app->session->setFlash("success","创建一级分类:".$cate->name.":成功");
                   //刷新不跳转
                    return $this->refresh();
                }else{
                    //子类
                    $cateparent= Category::findOne($cate->parent_id);

                    $cate->prependTo($cateparent);
                    \Yii::$app->session->setFlash("success","创建{$cateparent->name}分类的子分类:".$cate->name.":成功");
                    //刷新不跳转
                    return $this->refresh();

                }
            }
        }
        return $this->render('add',compact('cate','catesjson'));

    }
    //编辑
    public function actionEdit($id){
        $cate=Category::findOne($id);
        //查出数据
        $cates=Category::find()->asArray()->all();
        $cates[]=['id'=>0,'name'=>'一级分类','parent_id'=>0];
        //转为JSON字符串
        $catesjson=Json::encode($cates);

//        var_dump($catesJson);exit();

        $request=\Yii::$app->request;
        if ($request->isPost){
            $cate->load($request->post());
            if ($cate->validate()){
                try{
                    if ($cate->parent_id==0){
                        $cate->save();
                        //厂家成功
                        \Yii::$app->session->setFlash("success","修改一级分类:".$cate->name.":成功");
                        //刷新不跳转
                        return $this->refresh();
                    }else{
                        //子类
                        $cateparent= Category::findOne($cate->parent_id);

                        $cate->prependTo($cateparent);
                        \Yii::$app->session->setFlash("success","修改{$cateparent->name}分类的子分类:".$cate->name.":成功");
                        //刷新不跳转
                        return $this->redirect(['index']);

                    }
                }

                catch (Exception $exception){
                \Yii::$app->session->setFlash("danger","不能移动到子节点");
                }
                //添加一级分了

            }
        }
        return $this->render('add',compact('cate','catesjson'));

    }
        //删除
    public function actionDel($id)
    {
        $cate=Category::findOne(['parent_id'=>$id]);
        if($cate!=null){
            \Yii::$app->session->setFlash('success',"文件内含文件，不能删除！请先删除子文件");
            return $this->redirect(['index']);
        }else{
            $cate=Category::findOne($id);
            if($cate->depth==0){
                GoodsDel::findOne($id)->delete();
            }else{
                Category::findOne($id)->delete();
            }
            \Yii::$app->session->setFlash('success',"删除成功");
            return $this->redirect(['index']);
        }
    }

    public function actionTest()
    {
//        父ID
//        $cate=new Category();
//        $cate->name="冰箱";
//        $cate->makeRoot();
        //子数据
        $cateparent= Category::findOne(1);
        $cate=new Category();
        $cate->name="电视";
        $cate->parent_id=1;
        $cate->prependTo($cateparent);
         }

}
