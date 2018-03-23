<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\LoginForm;

class AdminController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model=Admin::find()->all();
        return $this->render('index',['model'=>$model]);
    }

    public function actionLogin(){
        //NEW表单模型
        $model=new LoginForm();
            $request=\Yii::$app->request;
            if ($request->isPost){
                $model->load($request->post());
                if ($model->validate()){
                    $admin=Admin::findOne(['username'=>$model->username,'status'=>1]);
                if ($admin) {
                    //验证密码
                    if (\Yii::$app->security->validatePassword($model->password,$admin->password_hash)){
                        \Yii::$app->user->login($admin,$model->rememberMe?3600*24*7:0);

                        //时间IP
                        $admin->login_at=time();
                        $admin->login_ip=ip2long(\Yii::$app->request->userIP);
                        //更新数据
                        if ($admin->save()) {
                            \Yii::$app->session->setFlash('success','登陆成功');
                            return $this->redirect(['index']);
                        }


                    }else{
                        $model->addError('password','密码错误');

                    }
                }else{
                    //用户不纯在
                    $model->addError('username','用户不存在或以禁用');
                }
                }else{
                    //打印错


                }

            }
        //显示视图
        return $this->render('login',compact('model'));


    }

    public function actionAdd(){
        $admin=new Admin();

//        $admin->setScenario('add');
        //绑定数据判断POST
        if ($admin->load(\Yii::$app->request->post()) && $admin->validate()){
            //加密
            $admin->password_hash=\Yii::$app->security->generatePasswordHash($admin->password_hash);
            //令牌
            $admin->auth_key=\Yii::$app->security->generateRandomString();

            if ($admin->save()){
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['index']);

            }

        }
        return $this->render('add',compact('admin'));

    }
    public function actionLogout(){
        if (\Yii::$app->user->logout()){

            return $this->redirect(['login']);
        }


    }

    public function actionEdit($id){
        $admin=Admin::findOne($id);
        $password=$admin->password_hash;
        //绑定场景
        $admin->setScenario('edit');
        //绑定数据判断POST
        if ($admin->load(\Yii::$app->request->post()) && $admin->validate()){
            //判断有没有密码
//            if ($admin->password_hash){
//                //加密
//                $admin->password_hash=\Yii::$app->security->generatePasswordHash
//                ($admin->password_hash);
//
//
//            }else{
//                $admin->password_hash=$password;
//            }
            $admin->password_hash=$admin->password_hash?\Yii::$app->security->generatePasswordHash($admin->password_hash):$password;

             //令牌
//            $admin->auth_key=\Yii::$app->security->generateRandomString();

            if ($admin->save()){
                \Yii::$app->session->setFlash('success','编辑成功');
                return $this->redirect(['index']);

            }

        }
        $admin->password_hash=null;
        return $this->render('add',compact('admin'));

    }


}
