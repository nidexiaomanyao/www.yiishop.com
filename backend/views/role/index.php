<?php

?>
    <h1>角色列表</h1>
    <a href="add" class="btn btn-info">添加</a>
    <table class="table">
        <tr>
            <th>名称</th>
            <th>简介</th>
            <th>权限</th>
            <th>操作</th>
        </tr>

        <?php foreach ($roles as $role):?>

            <tr>
                <td><?=strpos($role->name,'/')!==false?"---":""?><?=$role->name?></td>
                <td><?=$role->description?></td>
                <td><?php
                    //得到当前角色的权限
                    $auth=Yii::$app->authManager;
                   $pers=$auth->getPermissionsByRole($role->name);
                   $html="";
                        foreach ($pers as $per){
                            $html.= $per->description.",";
                        }
                        $html=trim($html,',');
                        echo $html;
                    ?>

                </td>
                <td>
                    <?=\yii\bootstrap\Html::a("编辑",['edit','name'=>$role->name],['class'=>'btn btn-success'])?>
                    <?=\yii\bootstrap\Html::a("删除",['del','name'=>$role->name],['class'=>'btn btn-success'])?>

                </td>
            </tr>

        <?php endforeach;?>
    </table>

<?php
?>