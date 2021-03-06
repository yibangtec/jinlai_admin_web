<link rel=stylesheet media=all href="/css/index.css">
<style>


	/* 宽度在750像素以上的设备 */
	@media only screen and (min-width:751px)
	{

	}
	
	/* 宽度在960像素以上的设备 */
	@media only screen and (min-width:961px)
	{

	}

	/* 宽度在1280像素以上的设备 */
	@media only screen and (min-width:1281px)
	{

	}
</style>

<script defer src="/js/index.js"></script>

<base href="<?php echo $this->media_root ?>">

<div id=breadcrumb>
	<ol class="breadcrumb container">
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li class=active><?php echo $this->class_name_cn ?></li>
	</ol>
</div>

<div id=content class=container>
	<?php
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户级别
	$role_allowed = array('管理员', '经理');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class="btn-group btn-group-justified" role=group>
		<a class="btn <?php echo (empty($this->input->get()))? 'btn-primary': 'btn-default' ?>" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>">所有</a>
        <a class="btn <?php echo ($this->input->get('available') == 1)? 'btn-primary': 'btn-default' ?>" title="有在售商品的商品分类" href="<?php echo base_url($this->class_name.'?available=1') ?>">在售中</a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>">回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>">创建</a>
	</div>
	
    <div id=primary_actions class=action_bottom>
        <?php if (count($items) > 1): ?>
        <span id=enter_bulk>
            <i class="far fa-edit"></i>批量
        </span>
        <?php endif ?>

        <ul class=horizontal>
            <li>
                <a class=bg_primary title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>">创建</a>
            </li>
        </ul>
    </div>
	<?php endif ?>

	<?php if ( empty($items) ): ?>
	<blockquote>
		<p>这里空空如也，快点添加<?php echo $this->class_name_cn ?>吧</p>
	</blockquote>

	<?php else: ?>
	<form method=get target=_blank>
        <?php if (count($items) > 1): ?>
        <div id=bulk_action class=action_bottom>
            <span id="bulk_selector" data-bulk-selector=off>
                <i class="far fa-circle"></i>全选
            </span>
            <span id=exit_bulk>取消</span>
            <ul class=horizontal>
                <li>
                    <button class=bg_primary formaction="<?php echo base_url($this->class_name.'/delete') ?>" type=submit>删除</button>
                </li>
            </ul>
        </div>
        <?php endif ?>

        <ul id=item-list class=row>
            <?php foreach ($items as $item): ?>
            <li>

                <a href="<?php echo base_url($this->class_name.'/detail?id='.$item[$this->id_name]) ?>">
                    <p><?php echo $this->class_name_cn ?>ID <?php echo $item[$this->id_name] ?></p>
                    <p>
                        <?php echo $item['name'] ?>
                        <br>
                        <?php echo trim($item['nature'].'/Lv.'.$item['level'].'/'.$item['parent_id'], '/') ?>
                    </p>
                </a>

                <div class=item-actions>
		            <span>
		                <input name=ids[] class=form-control type=checkbox value="<?php echo $item[$this->id_name] ?>">
		            </span>

                    <ul class=horizontal>
                        <?php if ($item['level'] < 3): ?>
                        <li><a href="<?php echo base_url('item_category/index?parent_id='.$item[$this->id_name]) ?>" target=_blank>子分类 <i class="far fa-angle-right"></i></a></li>
                        <?php endif ?>

                        <?php
                        // 需要特定角色和权限进行该操作
                        if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
                            ?>
                        <li><a href="<?php echo base_url($this->class_name.'/delete?ids='.$item[$this->id_name]) ?>" target=_blank>删除</a></li>
                        <li><a href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank>编辑</a></li>
                        <?php endif ?>
                    </ul>
                </div>

            </li>
            <?php endforeach ?>
        </ul>

        <!--

        <h2>顶级分类</h2>
        <ul data-level=1></ul>

        <h2>次级分类</h2>
        <ul data-level=2></ul>

        <h2>末级分类</h2>
        <ul data-level=3></ul>

        -->

	</form>
	<?php endif ?>
</div>

<script>
    /*
    $(function(){
        var items = <?php echo json_encode($items) ?>;

        // 顶级分类列表
        var list_level_1 = $('ul[data-level=1]');

        // 次级分类列表
        var list_level_2 = $('ul[data-level=2]');

        // 末级分类列表
        var list_level_3 = $('ul[data-level=3]');

        $.each(items, function(index, item){
            var item_dom = '<li>' +
                    '<a href="'+ base_url+'detail?id='+item.category_id +'">' +
                    item.name +
                    '</a>' +
                    '<div class=item-actions>' +
                    '   <span>' +
                    '       <input name=ids[] class=form-control type=checkbox value="' + item.category_id + '">' +
                    '   </span>' +
                    '</div>' +
                '</li>';

            $('ul[data-level='+ item.level +']').append(item_dom);
        });
    });
    */
</script>