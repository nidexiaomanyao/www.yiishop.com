<?php

namespace backend\controllers;

use backend\models\AuthItem;

class PermissionController extends \yii\web\Controller
{
    //权限裂变
    public function actionIndex()
    {
        $auth=\Yii::$app->authManager;
        //找到所有权限
        $pers=$auth->getPermissions();
        return $this->render('index',compact('pers'));
    }
    //添加
    public function actionAdd(){
    //模型对象
    $model=new AuthItem();
    if ($model->load(\Yii::$app->request->post()) && $model->validate()){
        $auth=\Yii::$app->authManager;
        $per=$auth->createPermission($model->name);
        //设置描述
        $per->description=$model->description;

        if ($auth->add($per)) {
            \Yii::$app->session->setFlash('success','权限添加成功');
            return $this->refresh();
        }

    }else{
//            var_dump($model->errors);exit();
    }
    //显示视图
    return $this->render('add',compact('model'));

}
    //编辑
    public function actionEdit($name){
        //模型对象
        $model=AuthItem::findOne($name);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            $auth=\Yii::$app->authManager;
            //得到权限
            $per=$auth->getPermission($name);

            $per->description=$model->description;

            if ($auth->update($model->name,$per)) {
                \Yii::$app->session->setFlash('success','权限修改成功');
                return $this->refresh();
            }

        }else{
//            var_dump($model->errors);exit();
        }
        //显示视图
        return $this->render('edit',compact('model'));

    }
    /*
     * 权限删除
     */
    public function actionDel($name){
        $auth=\Yii::$app->authManager;
            //找到权限
        $per=$auth->getPermission($name);
        //删除
        if ($auth->remove($per)) {
            \Yii::$app->session->setFlash('success','删除成功');
       return $this->redirect(['index']);
        }
    }
}
