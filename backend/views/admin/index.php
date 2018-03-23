<?php
/* @var $this yii\web\View */
?>
<h1>管理员列表</h1>

<a href="add" class="btn btn-info">添加</a>
<table class="table">

    <tr>
        <th>用户名</th>
        <th>令牌</th>
        <th>状态</th>
        <th>登陆时间</th>
        <th>登陆IP</th>

        <th>操作</th>
    </tr>

    <?php foreach ($model as $models):?>
        <tr>
            <td><?=$models->username?></td>
            <td><?=$models->auth_key?></td>
            <td><?=$models->status?></td>
            <td><?=$models->login_at?></td>
            <td><?=$models->login_ip?></td>

            <td><a href="<?=\yii\helpers\Url::to(['admin/edit','id'=>$models->id])?>" class="glyphicon glyphicon-edit" title="编辑"></a>
                <a href="<?=\yii\helpers\Url::to(['admin/del','id'=>$models->id])?>" class="glyphicon glyphicon-trash" title="删除"></a></td>
        </tr>


    <?php endforeach;?>

</table>