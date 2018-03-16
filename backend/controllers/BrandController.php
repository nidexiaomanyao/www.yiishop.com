<?php
namespace backend\controllers;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    /**
     * 品牌列表
     * @return string
     */
    public function actionIndex()
    {
        // 1.总条数
        $count = Brand::find()->count();
        //2 每页显示条数
        $pageSize = 4;
        //创建分页对象
        $page = new Pagination(
            [
                'pageSize' => $pageSize,
                'totalCount' => $count
            ]
        );
        $brand = Brand::find()->limit($page->limit)->offset($page->offset)->where(['status' => 1])->all();
        //显示视图
        return $this->render('index', ['brand' => $brand, 'page' => $page]);
    }

    public function actionAdd()
    {
        //创建对象
        $model = new Brand();
      //判断是不是POST提交
        if(\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            //上传图片
            $model->imgfile=UploadedFile::getInstance($model,'imgfile');
            $imgpath="";
            if ($model->imgfile!==null){
                $imgpath="images/".time().".".$model->imgfile->extension;
                //移动路径
                $model->imgfile->saveAs($imgpath,false);
            }
            //验证
            if ($model->validate()){
                //把路径赋值给logo
                $model->logo=$imgpath;
                //保存数据
                if ($model->save()){
                    //提示
                    \Yii::$app->session->setFlash('success','添加成功');
                    //跳转
                    return $this->redirect(['index']);
                }
            }else{
                var_dump($model->errors);exit();
            }
        }
        $model->status=1;
        return $this->render('add',['model'=>$model]);

    }

    public function actionEdit($id)
    {
        //创建对象
//        $model = new Brand();
        $model=Brand::findOne($id);
        //判断是不是POST提交
        if(\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            //上传图片
            $model->imgfile=UploadedFile::getInstance($model,'imgfile');
            $imgpath="";
            if ($model->imgfile!==null){
                $imgpath="images/".time().".".$model->imgfile->extension;
                //移动路径
                $model->imgfile->saveAs($imgpath,false);
            }
            //验证
            if ($model->validate()){
                //把路径赋值给logo
                $model->logo=$imgpath;
                //保存数据
                if ($model->save()){
                    //提示
                    \Yii::$app->session->setFlash('success','添加成功');
                    //跳转
                    return $this->redirect(['index']);
                }
            }else{
                var_dump($model->errors);exit();
            }
        }
        $model->status=1;
        return $this->render('add',['model'=>$model]);

    }

    public function actionDel($id)
    {
        $model = Brand::findOne($id);
        $model->delete();
        return $this->redirect(['brand/index']);
    }

    public function actionHuishou($id)
    {
        $data=Brand::findOne($id);
        $data->status=0;
        $data->save();
//        var_dump($data);exit();
        //跳转
        return $this->redirect(['brand/index']);
    }

    public function actionDisplay(){
        $display=Brand::find()->where(['status'=>0])->all();
        return $this->render('recycle',['display'=>$display]);
    }
    public function actionReduction($id)
    {
        $data=Brand::findOne($id);
        $data->status=1;
        $data->save();
        //跳转
        return $this->redirect(['brand/display']);
    }
}
