<link rel=stylesheet media=all href="/css/detail.css">
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

<script defer src="/js/detail.js"></script>

<base href="<?php echo $this->media_root ?>">

<div id=breadcrumb>
	<ol class="breadcrumb container">
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
	</ol>
</div>

<div id=content class=container>
    <?php
    // 需要特定角色和权限进行该操作
    $current_role = $this->session->role; // 当前用户角色
    $current_level = $this->session->level; // 当前用户级别
    $role_allowed = array('管理员', '经理');
    $level_allowed = 30;
    ?>
    <ul id=item-actions class=list-unstyled>
        <?php
        // 需要特定角色和权限进行该操作
        if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
            ?>
            <li><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>">编辑</a></li>
        <?php endif ?>
    </ul>

	<dl id=list-info class=dl-horizontal>
		<dt>商品分类ID</dt>
		<dd><?php echo $item['category_id'] ?></dd>

		<dt>分类图片</dt>
		<?php if ( !empty($item['url_image']) ): ?>
		<dd class=row>
			<figure class="col-xs-12 col-sm-6 col-md-4">
				<img class=img-rounded src="<?php echo $item['url_image'] ?>">
			</figure>
		</dd>
		<?php else: ?>
		<dd>未上传</dd>
		<?php endif ?>
		
		<dt>名称</dt>
		<dd><?php echo $item['name'] ?></dd>
	</dl>

	<dl id=list-record class=dl-horizontal>
		<dt>创建时间</dt>
		<dd>
			<?php echo $item['time_create'] ?>
			<a href="<?php echo base_url('stuff/detail?id='.$item['creator_id']) ?>" target=new>查看创建者</a>
		</dd>

		<?php if ( ! empty($item['time_delete']) ): ?>
		<dt>删除时间</dt>
		<dd><?php echo $item['time_delete'] ?></dd>
		<?php endif ?>

		<?php if ( ! empty($item['operator_id']) ): ?>
		<dt>最后操作时间</dt>
		<dd>
			<?php echo $item['time_edit'] ?>
			<a href="<?php echo base_url('stuff/detail?id='.$item['operator_id']) ?>" target=new>查看最后操作者</a>
		</dd>
		<?php endif ?>
	</dl>
</div>