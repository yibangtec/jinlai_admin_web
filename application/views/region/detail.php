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
	<?php if ( empty($item) ): ?>
	<p><?php echo $error ?></p>

	<?php
		else:
			// 需要特定角色和权限进行该操作
			$current_role = $this->session->role; // 当前用户角色
			$current_level = $this->session->level; // 当前用户级别
			$role_allowed = array('管理员', '经理');
			$level_allowed = 30;
			if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
			?>
		    <ul id=item-actions class=list-unstyled>
				<li><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>">编辑</a></li>
		    </ul>
			<?php endif ?>

	<dl id=list-info class=dl-horizontal>
		<dt><?php echo $this->class_name_cn ?>ID</dt>
		<dd><?php echo $item[$this->id_name] ?></dd>
		<dt>国别</dt>
		<dd><?php echo $item['nation'] ?></dd>
		<dt>省级行政区</dt>
		<dd><?php echo $item['province'] ?></dd>
        <dt>省级行政区简称</dt>
        <dd><?php echo $item['province_abbr'] ?></dd>
        <dt>省级行政区通称</dt>
        <dd><?php echo $item['province_brief'] ?></dd>
		<dt>省级行政区索引</dt>
		<dd><?php echo $item['province_index'] ?></dd>
		<dt>市级行政区</dt>
		<dd><?php echo $item['city'] ?></dd>
		<dt>区县级行政区</dt>
		<dd><?php echo $item['county'] ?></dd>
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
	<?php endif ?>
</div>