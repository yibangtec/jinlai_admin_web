<link rel=stylesheet media=all href="/css/edit.css">
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

<script defer src="/js/edit.js"></script>

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
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-edit form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/edit?id='.$item[$this->id_name], $attributes);
	?>
		<p class=help-block>必填项以“※”符号标示</p>

		<fieldset>
			<legend>基本信息</legend>

			<input name=id type=hidden value="<?php echo $item[$this->id_name] ?>">
			
			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称 ※</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo $item['name'] ?>" placeholder="名称" required>
				</div>
			</div>

            <div class=form-group>
                <label for=url_name class="col-sm-2 control-label">自定义URL</label>
                <div class=col-sm-10>
                    <input class=form-control name=url_name type=text value="<?php echo $item['url_name'] ?>" placeholder="自定义URL">
                </div>
            </div>

            <div class=form-group>
                <label for=description class="col-sm-2 control-label">描述</label>
                <div class=col-sm-10>
                    <textarea class=form-control name=description rows=5 placeholder="最多255个字符"><?php echo $item['description'] ?></textarea>
                </div>
            </div>

            <div class=form-group>
                <label for=extra class="col-sm-2 control-label">补充描述</label>
                <div class=col-sm-10>
                    <textarea class=form-control name=extra rows=5 placeholder="最多5000个字符"><?php echo $item['extra'] ?></textarea>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <div class=form-group>
                <label for=content_css class="col-sm-2 control-label">自定义样式</label>
                <div class=col-sm-10>
                    <textarea class=form-control name=content_css rows=5 placeholder="CSS；无需<style>标签，最多5000个字符"><?php echo $item['content_css'] ?></textarea>

                    <div class=well>
                        <h3>常用样式说明</h3>
                        <ul>
                            <li>body {页面容器}</li>
                            <li>#content {主内容区}</li>

                            <li>#vote-searcher {候选项搜索器}</li>
                            <li>#options-naver {候选项筛选器}</li>

                            <li>#vote-options {候选项列表}</li>
                            <li>.vote-option {候选项}</li>
                        </ul>
                    </div>
                </div>
            </div>

			<div class=form-group>
				<label for=url_image class="col-sm-2 control-label">形象图</label>
				<div class=col-sm-10>
                    <?php
                    require_once(APPPATH. 'views/templates/file-uploader.php');
                    $name_to_upload = 'url_image';
                    generate_html($name_to_upload, $this->class_name, FALSE, 1, $item[$name_to_upload]);
                    ?>
                    <p class=help-block>正方形图片视觉效果最佳</p>
				</div>
			</div>

            <!--
			<div class=form-group>
				<label for=url_audio class="col-sm-2 control-label">背景音乐</label>
				<div class=col-sm-10>
					<input class=form-control name=url_audio type=hidden value="<?php echo $item['url_audio'] ?>" placeholder="背景音乐">
				</div>
			</div>
			-->
            <input class=form-control name=url_audio type=hidden value="<?php echo $item['url_audio'] ?>" placeholder="背景音乐">

			<div class=form-group>
				<label for=url_video class="col-sm-2 control-label">形象视频</label>
				<div class=col-sm-10>
					<input class=form-control name=url_video type=text value="<?php echo $item['url_video'] ?>" placeholder="形象视频">
				</div>
			</div>

            <div class=form-group>
                <label for=url_video_thumb class="col-sm-2 control-label">形象视频缩略图</label>
                <div class=col-sm-10>
                    <?php
                    require_once(VIEWS_PATH. 'templates/file-uploader.php');
                    $name_to_upload = 'url_video_thumb';
                    generate_html($name_to_upload, $this->class_name, FALSE, 1, $item[$name_to_upload]);
                    ?>

                    <p class=help-block>需要与视频尺寸一致</p>
                </div>
            </div>

            <div class=form-group>
                <label for=url_default_option_image class="col-sm-2 control-label">选项默认占位图</label>
                <div class=col-sm-10>
                    <?php
                    require_once(APPPATH. 'views/templates/file-uploader.php');
                    $name_to_upload = 'url_default_option_image';
                    generate_html($name_to_upload, $this->class_name, FALSE, 1, $item[$name_to_upload]);
                    ?>

                    <p class=help-block>正方形图片视觉效果最佳</p>
                </div>
            </div>
        </fieldset>

        <fieldset>
			<div class=form-group>
				<label for=signup_allowed class="col-sm-2 control-label">用户可报名</label>
				<div class=col-sm-10>
                    <select name=signup_allowed required>
                        <option value="否" <?php if ($item['signup_allowed'] === '否') echo 'selected' ?>>否</option>
                        <option value="是" <?php if ($item['signup_allowed'] === '是') echo 'selected' ?>>是</option>
                    </select>
				</div>
			</div>

            <div class=form-group>
                <label for=option_censor class="col-sm-2 control-label">候选项需审核</label>
                <div class=col-sm-10>
                    <select name=option_censor required>
                        <option value="否" <?php if ($item['option_censor'] === '否') echo 'selected' ?>>否</option>
                        <option value="是" <?php if ($item['option_censor'] === '是') echo 'selected' ?>>是</option>
                    </select>
                </div>
            </div>
			
			<div class=form-group>
				<label for=max_user_total class="col-sm-2 control-label">每选民最高总选票数</label>
				<div class=col-sm-10>
					<input class=form-control name=max_user_total type=number min=0 max=999 step=1 value="<?php echo $item['max_user_total'] ?>" placeholder="最高999，不限请填0">
                    <p class=help-block>填0则不限</p>
				</div>
			</div>
			<div class=form-group>
				<label for=max_user_daily class="col-sm-2 control-label">每选民最高日选票数</label>
				<div class=col-sm-10>
					<input class=form-control name=max_user_daily type=number min=1 max=99 step=1 value="<?php echo $item['max_user_daily'] ?>" placeholder="最高99">
				</div>
			</div>
			<div class=form-group>
				<label for=max_user_daily_each class="col-sm-2 control-label">每选民同选项最高日选票数</label>
				<div class=col-sm-10>
					<input class=form-control name=max_user_daily_each type=number min=1 max=99 step=1 value="<?php echo $item['max_user_daily_each'] ?>" placeholder="最高99">
				</div>
			</div>
			
			<div class=form-group>
				<label for=time_start class="col-sm-2 control-label">开始时间</label>
				<div class=col-sm-10>
                    <input class=form-control name=time_start type=datetime value="<?php echo (empty($item['time_start']) || $item['time_start'] < time())? NULL: date('Y-m-d H:i', $item['time_start']); ?>" placeholder="例如：<?php echo date('Y-m-d H:i', strtotime('+1days')) ?>">

                    <?php require_once(APPPATH. 'views/templates/time_start_hint.php') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=time_end class="col-sm-2 control-label">结束时间</label>
				<div class=col-sm-10>
                    <input class=form-control name=time_end type=datetime value="<?php echo empty($item['time_end'])? NULL: date('Y-m-d H:i', $item['time_end']); ?>" placeholder="例如：<?php echo date('Y-m-d H:i', strtotime('+31days')) ?>">

                    <?php require_once(APPPATH. 'views/templates/time_end_hint.php') ?>
				</div>
			</div>

            <div class=form-group>
                <label for=option_orders class="col-sm-2 control-label">选项显示顺序</label>
                <div class=col-sm-10>
                    <?php if (count($options) > 1): ?>
                    <textarea class=form-control name=option_orders rows=10><?php
                            foreach ($options as $option)
                                echo $option['option_id'].',' ?></textarea>

                    <p class=help-block>候选项数量较多时，系统需要较长时间处理，页面将过一段时间才显示处理结果，稍等片刻即可。</p>
                    <?php else: ?>
                    <p class="form-control-static">有效投票选项多于1个时，可在此批量各选项在投票页面中显示的顺序。</p>
                    <?php endif ?>
                </div>
            </div>
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>
	</form>

</div>