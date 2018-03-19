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
?>
<script>

    function onClick(e,treeId, treeNode) {
        $("#category-parent_id").val(treeNode.id)

//        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
//        zTree.expandNode(treeNode);
    }


</script>
