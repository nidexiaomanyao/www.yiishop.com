<?php

namespace backend\controllers;

use backend\models\AuthItem;
use yii\helpers\ArrayHelper;

class RoleController extends \yii\web\Controller
{
    //角色列表
    public function actionIndex()
    {
        $auth=\Yii::$app->authManager;
        //找到所有权限
        $roles=$auth->getRoles();
        return $this->render('index',compact('roles'));
    }
    //添加角色
    public function actionAdd(){
    //模型对象
    $model=new AuthItem();
        $auth=\Yii::$app->authManager;
    //拿出所有权限
        $pers=$auth->getPermissions();
        $persArr=ArrayHelper::map($pers,'name','description');
//        var_dump($persArr);exit;
    if ($model->load(\Yii::$app->request->post()) && $model->validate()){

        $role=$auth->createRole($model->name);
        //设置描述
        $role->description=$model->description;

        if ($auth->add($role)) {
            //判断有么有添加权限
            if ($model->permissions){
                foreach ($model->permissions as $perName){
                    $per=$auth->getPermission($perName);
                    //给角色添加权限
                    $auth->addChild($role,$per);

                }
            }
            //循环取出权限并加给角色


            \Yii::$app->session->setFlash('success','角色添加成功');
            return $this->refresh();
        }

    }else{
//            var_dump($model->errors);exit();
    }
    //显示视图
    return $this->render('add',compact('model','persArr'));

}
    //编辑
    public function actionEdit($name){
        //模型对象
        $model=AuthItem::findOne($name);
        $auth=\Yii::$app->authManager;
        //拿出所有权限
        $pers=$auth->getPermissions();
        $persArr=ArrayHelper::map($pers,'name','description');
        //回显  得到当前角色的所有权限
        $rolepers=$auth->getPermissionsByRole($name);
//        var_dump(array_keys($rolepers));exit;
        $model->permissions=array_keys($rolepers);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){

            $role=$auth->getRole($model->name);
            //设置描述
            $role->description=$model->description;
            //编辑
            if ($auth->update($model->name,$role)) {
                //先删除所有权限
                $auth->removeChildren($role);

                //判断有么有添加权限
                if ($model->permissions){
                    foreach ($model->permissions as $perName){
                        $per=$auth->getPermission($perName);
                        //给角色添加权限
                        $auth->addChild($role,$per);

                    }
                }
                //循环取出权限并加给角色


                \Yii::$app->session->setFlash('success','角色添加成功');
                return $this->redirect(['index']);
            }

        }else{
//            var_dump($model->errors);exit();
        }

        //显示视图
        return $this->render('edit',compact('model','persArr'));

    }
    /*
     * 权限删除
     */
    public function actionDel($name){
        $auth=\Yii::$app->authManager;
            //找到权限
        $per=$auth->getRole($name);
        //删除
        if ($auth->remove($per)) {
            \Yii::$app->session->setFlash('success','删除成功');
       return $this->redirect(['index']);
        }
    }

    public function actionAdminRole($roleName,$id){
        $auth=\Yii::$app->authManager;
        $role=$auth->getRole($roleName);
//        $auth->assign($role,$id);
        var_dump($auth->assign($role,$id));

    }
    //判断权限
    public function actionCheck(){
        var_dump(\Yii::$app->user->can('goods/add'));
    }
}
