<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/24 0024
 * Time: 下午 7:27
 */

namespace backend\filters;


use yii\base\ActionFilter;

class RbacFilter extends ActionFilter
{

    public function beforeAction($action)
    {
        if(!\Yii::$app->user->can($action->uniqueId)){
            $html=<<<HTML
        <script >
//        alert("你没有权限");
        window.history.go(-1);
</script>
HTML;
//
            \Yii::$app->session->setFlash("danger",'你没有权限');
            echo $html;

            return false;
        }
        return parent::beforeAction($action);
    }

}