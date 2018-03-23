<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($cate,'name');
echo $form->field($cate,'parent_id')->hiddenInput(['value'=>0]);
echo \liyuze\ztree\ZTree::widget([
    'setting' => '{
			data: {
				simpleData: {
					enable: true,
					pIdKey: "parent_id",
				}
			},
		 callback: {
            onClick: onClick
			}	
        }',
    'nodes' =>$catesjson
]);

echo $form->field($cate,'intro')->textarea();
echo \yii\bootstrap\Html::submitButton("提交",['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();

$js=<<<js
var treeObj = $.fn.zTree.getZTreeObj("w1");

//得到当前阶段
var node=treeObj.getNodeByParam("id","$cate->parent_id",null);
//选中当前节点
treeObj.selectNode(node);
$("#category-parent_id").val($cate->parent_id);

//展开方法
treeObj.expandAll(true);
/*选中当前节点*/
var node = treeObj.getNodeByParam("id","{$cate->parent_id}",null);
treeObj.selectNode(node);
js;
//注册JS代码
$this->registerJs($js);

?>
<script>

    function onClick(e,treeId, treeNode) {
        $("#category-parent_id").val(treeNode.id)

//        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
//        zTree.expandNode(treeNode);
    }


</script>
