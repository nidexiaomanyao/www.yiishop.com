<?php

?>
    <h1>分类</h1>
    <a href="add" class="btn btn-info">添加</a>
    <table class="table">
        <tr>
            <th>名字</th>
            <th>上级分类</th>
            <th>简介</th>
        </tr>

        <?php foreach ($categorys as $category):?>

            <tr>
                <td><?=$category->name?></td>
                <td><?=$category->parent_id?></td>
                <td><?=$category->intro?></td>
              <td><a href="<?=\yii\helpers\Url::to(['category/edit','id'=>$category->id]) ?>" class="btn btn-info">编辑</a>
                    <a href="<?=\yii\helpers\Url::to(['category/del','id'=>$category->id])?>" class="btn btn-danger">删除</a>
            </tr>

        <?php endforeach;?>
    </table>

