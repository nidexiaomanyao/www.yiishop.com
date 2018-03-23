<?php
/* @var $this yii\web\View */
?>
    <h1>分类</h1>
    <a href="add" class="btn btn-info">添加</a>
    <table class="table">
        <tr>
            <th>名字</th>
            <th>上级分类</th>
            <th>简介</th>
        </tr>

        <?php foreach ($cates as $cate):?>

            <tr class="cate" data-tree="<?=$cate->tree?>" data-lft="<?=$cate->lft?>" data-rgt="<?=$cate->rgt?>">
                <td><span class="cate-tr glyphicon glyphicon-arrow-down"></span><?=$cate->nameText?></td>
                <td><?=$cate->parent_id?></td>
                <td><?=$cate->intro?></td>
              <td><a href="<?=\yii\helpers\Url::to(['category/edit','id'=>$cate->id]) ?>" class="btn btn-info">编辑</a>
                    <a href="<?=\yii\helpers\Url::to(['category/del','id'=>$cate->id])?>" class="btn btn-danger">删除</a>
            </tr>

        <?php endforeach;?>
    </table>
<?php
$js=<<<JS
$(".cate-tr").click(function() {
    var trParent=$(this).parent().parent();
    var treeParent=trParent.attr('data-tree');
    var lftParent=trParent.attr('data-lft');
    var rgtParent=trParent.attr('data-rgt');
    
    $(".cate").each(function(k,v) {
      var tree=$(v).attr('data-tree');
      var lft=$(v).attr('data-lft');
      var rgt=$(v).attr('data-rgt');
    
    
    if (tree==treeParent &&   Number(lft)>lftParent && Number(rgt)<rgtParent){
        $(v).toggle();
        
    }
    });
   $(this).toggleClass('glyphicon-arrow-down');
  $(this).toggleClass('glyphicon-arrow-up');
  console.log(this); 
});
 // console.debug(111);
   
  

JS;
$this->registerJs($js);

?>
