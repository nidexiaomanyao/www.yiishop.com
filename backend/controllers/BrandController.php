<?php
namespace backend\controllers;
use backend\models\Brand;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\UploadedFile;
use crazyfd\qiniu\Qiniu;
class BrandController extends \yii\web\Controller
{
    /**
     * 品牌列表
     * @return string
     */
    public function actionIndex()
    {
        // 1.总条数
        $count=Brand::find()->count();
        //2 每页显示条数
        $pageSize=4;
        //创建分页对象
        $page=new Pagination(
            [
                'pageSize'=>$pageSize,
                'totalCount'=>$count
            ]
        );
        $brand=Brand::find()->limit($page->limit)->offset($page->offset)->where(['status'=>1])->all();
        //显示视图
        return $this->render('index',['brand'=>$brand,'page'=>$page]);
    }
    public function actionAdd()
    {
        //创建对象
        $model=new Brand();
        //创建HTTP请求对象
        $request=\Yii::$app->request;
        //判断是否是POST提交
        if ($model->load($request->post())){
            //创建文件上传对象
//            $model->imgfile=UploadedFile::getInstance($model,'imgfile');
            //拼装路径
            //$path="images/brand/".uniqid().".".$model->imgfile->extension;
            //保存图片
            //$model->imgfile->saveAs($path,false);
            //验证数据
            if ($model->validate()){
                //和数据库里logo字段绑定
                //$model->logo=$path;
                //提示
                \Yii::$app->session->setFlash("success","添加成功");
                //保存数据
                if ($model->save(false)){
                    //跳转
                    return $this->redirect(['index']);
                }
            }
        }
        //显示视图
        $model->status=1;
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id)
    {
        //创建对象
        //$model=new Brand();
        $model=Brand::findOne($id);
        //创建HTTP请求对象
        $request=\Yii::$app->request;
        //判断是否是POST提交
        if ($model->load($request->post())){
            //创建文件上传对象
            ///$model->imgfile=UploadedFile::getInstance($model,'imgfile');
            //拼装路径
            //$path="images/brand/".uniqid().".".$model->imgfile->extension;
            //保存图片
            //$model->imgfile->saveAs($path,false);
            //验证数据

            if ($model->validate()){
                //和数据库里logo字段绑定
                //$model->logo=$path;
                //提示
                \Yii::$app->session->setFlash("success","修改成功");
                //保存数据
                if ($model->save(false)){
                    //跳转
                    return $this->redirect(['index']);
                }
            }
        }
        //显示视图
        $model->status=1;
        return $this->render('add',['model'=>$model]);
    }
    public function actionDel($id)
    {
        $model=Brand::findOne($id);
        $model->delete();
        return $this->redirect(['brand/index']);
    }
    //七牛云上传
    public function actionUpload()
    {
        //配置
        $ak = 'JarICzXxSm-GNYMcSEHUHD6Au-WeT6fs43Xezd_h';
        $sk = '_p8D9jn5Hu6gi5UDxTPh5PbOntNk-RljMih4pbpT';
        $domain = 'http://oyvifomow.bkt.clouddn.com/';
        $bucket = 'haoge';
        $zone = 'south_china';
        //七牛云
//        var_dump($domain);
        $qiniu = new Qiniu($ak, $sk,$domain, $bucket,$zone);
        $key = time();
        $key .=$key. strtolower(strrchr($_FILES['file']['name'], '.'));
        $qiniu->uploadFile($_FILES['file']['tmp_name'],$key);
        $url = $qiniu->getLink($key);
//        var_dump($url);exit();

        $ok=[
            'code'=>0,
            'url'=>$url,
            "attachment"=>$url

        ];
        return json_encode($ok);

    }
    public function actionDel7()
    {
        $ak = 'JarICzXxSm-GNYMcSEHUHD6Au-WeT6fs43Xezd_h';
        $sk = '_p8D9jn5Hu6gi5UDxTPh5PbOntNk-RljMih4pbpT';
        $domain = 'http://oyvifomow.bkt.clouddn.com/';
        $bucket = 'haoge';
        $zone = 'south_china';
        //七牛云
        $qiniu = new Qiniu($ak, $sk,$domain, $bucket,$zone);
        $key = time();
        $key .=$key. strtolower(strrchr($_FILES['file']['name'], '.'));

        $qiniu->uploadFile($_FILES['file']['tmp_name'],$key);
        $url = $qiniu->getLink($key);
        $qiniu->delete("1.jpg","haoge");
    }
    /**
     * 回收站
     * @param $id
     * @return \yii\web\Response
     */
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
//        var_dump($data);exit();
        //跳转
        return $this->redirect(['brand/display']);
    }
}