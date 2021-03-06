<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Vote/VOT 投票类
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Vote extends MY_Controller
	{
		/**
		 * 可作为列表筛选条件的字段名；可在具体方法中根据需要删除不需要的字段并转换为字符串进行应用，下同
		 */
		protected $names_to_sort = array(
            'name', 'description', 'signup_allowed', 'option_censor', 'max_user_total', 'max_user_daily', 'max_user_daily_each', 'time_start', 'time_end',
            'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id', 'time_create_min', 'time_create_max',
		);

		/**
		 * 可被编辑的字段名
		 */
		protected $names_edit_allowed = array(
            'name', 'url_name', 'description', 'extra', 'url_image', 'url_audio', 'url_video', 'url_video_thumb', 'url_default_option_image', 'signup_allowed', 'option_censor', 'max_user_total', 'max_user_daily', 'max_user_daily_each', 'content_css', 'time_start', 'time_end',
		);

		/**
		 * 完整编辑单行时必要的字段名
		 */
		protected $names_edit_required = array(
			'id',
			'name',
		);

		public function __construct()
		{
			parent::__construct();

			// 未登录用户转到登录页
			($this->session->time_expire_login > time()) OR redirect( $target_url );

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '投票'; // 改这里……
			$this->table_name = 'vote'; // 和这里……
			$this->id_name = 'vote_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name; // 视图文件所在目录
			$this->media_root = MEDIA_URL. $this->class_name.'/'; // 媒体文件所在目录

            // 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'name' => '名称',
				'description' => '描述',
                'url_image' => '形象图',
			);
		} // end __construct

		/**
		 * 列表页
		 */
		public function index()
		{
			// 页面信息
			$data = array(
				'title' => $this->class_name_cn. '列表',
				'class' => $this->class_name.' index',
			);

			// 筛选条件
			$condition['time_delete'] = 'NULL';
			// （可选）遍历筛选条件
			foreach ($this->names_to_sort as $sorter):
				if ( !empty($this->input->get_post($sorter)) )
					$condition[$sorter] = $this->input->get_post($sorter);
			endforeach;

			// 排序条件
			$order_by = NULL;

			// 从API服务器获取相应列表信息
			$params = $condition;
			$url = api_url($this->class_name. '/index');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['items'] = $result['content'];
			else:
				$data['items'] = array();
				$data['error'] = $result['content']['error']['message'];
			endif;

			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;

			// 输出视图
			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/index', $data);
			$this->load->view('templates/footer', $data);
		} // end index

		/**
		 * 详情页
		 */
		public function detail()
		{
            // 检查是否已传入必要参数
            $id = $this->input->get_post('id')? $this->input->get_post('id'): NULL;
            if ( !empty($id) ):
                $params['id'] = $id;
            elseif ( !empty($url_name) ):
                $params['url_name'] = $url_name;
            else:
                redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页
            endif;

			// 从API服务器获取相应详情信息
			$url = api_url($this->class_name. '/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content'];

                // 获取投票候选项及候选项标签
			    $data['options'] = $this->list_vote_option($id);
                $data['tags'] = $this->list_vote_tag($id);

                // 页面信息
                $data['title'] = $this->class_name_cn. ' "'. $data['item']['name']. '"';
                $data['class'] = $this->class_name.' detail';

                $this->load->view('templates/header', $data);
                $this->load->view($this->view_root.'/detail', $data);
                $this->load->view('templates/footer', $data);

			else:
                redirect( base_url('error/code_404') ); // 若缺少参数，转到错误提示页

			endif;
		} // end detail

		/**
		 * 回收站
		 */
		public function trash()
		{
			// 操作可能需要检查操作权限
			$role_allowed = array('管理员', '经理'); // 角色要求
			$min_level = 30; // 级别要求
			$this->permission_check($role_allowed, $min_level);

			// 页面信息
			$data = array(
				'title' => $this->class_name_cn. '回收站',
				'class' => $this->class_name.' trash',
			);

			// 筛选条件
			$condition['time_delete'] = 'IS NOT NULL';
			// （可选）遍历筛选条件
			foreach ($this->names_to_sort as $sorter):
				if ( !empty($this->input->get_post($sorter)) )
					$condition[$sorter] = $this->input->get_post($sorter);
			endforeach;

			// 排序条件
			$order_by['time_delete'] = 'DESC';

			// 从API服务器获取相应列表信息
			$params = $condition;
			$url = api_url($this->class_name. '/index');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['items'] = $result['content'];
			else:
				$data['items'] = array();
				$data['error'] = $result['content']['error']['message'];
			endif;

			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;

			// 输出视图
			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/trash', $data);
			$this->load->view('templates/footer', $data);
		} // end trash

		/**
		 * 创建
		 */
		public function create()
		{
			// 操作可能需要检查操作权限
			// $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

			// 页面信息
			$data = array(
				'title' => '创建'.$this->class_name_cn,
				'class' => $this->class_name.' create',
				'error' => '', // 预设错误提示
			);

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
			$this->form_validation->set_rules('name', '名称', 'trim|required|max_length[30]');
            $this->form_validation->set_rules('url_name', 'URL名称', 'trim|min_length[5]|max_length[30]|alpha_dash');
			$this->form_validation->set_rules('description', '描述', 'trim|max_length[255]');
            $this->form_validation->set_rules('extra', '补充描述', 'trim|max_length[5000]');
			$this->form_validation->set_rules('url_image', '形象图', 'trim|max_length[255]');
            $this->form_validation->set_rules('url_audio', '背景音乐', 'trim|max_length[255]');
			$this->form_validation->set_rules('url_video', '形象视频', 'trim|max_length[255]');
            $this->form_validation->set_rules('url_video_thumb', '形象视频缩略图', 'trim|max_length[255]');
            $this->form_validation->set_rules('url_default_option_image', '选项默认占位图', 'trim|max_length[255]');

            $this->form_validation->set_rules('signup_allowed', '用户可报名', 'trim|required|in_list[否,是]');
            $this->form_validation->set_rules('option_censor', '候选项需审核', 'trim|required|in_list[否,是]');

            $this->form_validation->set_rules('max_user_total', '每选民最高总选票数', 'trim|is_natural|greater_than_equal_to[0]|less_than_equal_to[999]');
            $this->form_validation->set_rules('max_user_daily', '每选民最高日选票数', 'trim|greater_than[0]|less_than_equal_to[99]');
            $this->form_validation->set_rules('max_user_daily_each', '每选民同选项最高日选票数', 'trim|greater_than[0]|less_than_equal_to[99]');
            $this->form_validation->set_rules('content_css', '自定义样式', 'trim|max_length[5000]');
			
			$this->form_validation->set_rules('time_start', '开始时间', 'trim|exact_length[16]|callback_time_start');
			$this->form_validation->set_rules('time_end', '结束时间', 'trim|exact_length[16]|callback_time_end');
            $this->form_validation->set_message('time_start', '开始时间需详细到分，且晚于当前时间1分钟后');
            $this->form_validation->set_message('time_end', '结束时间需详细到分，且晚于当前时间1分钟后，亦不可早于开始时间（若有）');

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$data['error'] = validation_errors();

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/create', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 需要创建的数据；逐一赋值需特别处理的字段
				$data_to_create = array(
					'user_id' => $this->session->user_id,

                    'max_user_total' => empty($this->input->post('max_user_total'))? 0: $this->input->post('max_user_total'),
                    'max_user_daily' => empty($this->input->post('max_user_daily'))? 1: $this->input->post('max_user_daily'),
                    'max_user_daily_each' => empty($this->input->post('max_user_daily_each'))? 1: $this->input->post('max_user_daily_each'),
                    'time_start' => empty($this->input->post('time_start'))? NULL: $this->strto_minute($this->input->post('time_start')), // 时间仅保留到分钟，下同
                    'time_end' => empty($this->input->post('time_end'))? NULL: $this->strto_minute($this->input->post('time_end')),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
                    'name', 'url_name', 'description', 'url_image', 'url_audio', 'url_video', 'url_video_thumb', 'url_default_option_image', 'signup_allowed','content_css',
                );
				foreach ($data_need_no_prepare as $name)
					$data_to_create[$name] = $this->input->post($name);

				// 向API服务器发送待创建数据
				$params = $data_to_create;
				$url = api_url($this->class_name. '/create');
				$result = $this->curl->go($url, $params, 'array');
				if ($result['status'] === 200):
					$data['title'] = $this->class_name_cn. '创建成功';
					$data['class'] = 'success';
					$data['content'] = $result['content']['message'];
					$data['operation'] = 'create';
					$data['id'] = $result['content']['id']; // 创建后的信息ID

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/result', $data);
					$this->load->view('templates/footer', $data);

				else:
					// 若创建失败，则进行提示
					$data['error'] = $result['content']['error']['message'];

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/create', $data);
					$this->load->view('templates/footer', $data);

				endif;

			endif;
		} // end create

		/**
		 * 编辑单行
		 */
		public function edit()
		{
			// 检查是否已传入必要参数
			$id = $this->input->get_post('id')? $this->input->get_post('id'): NULL;
			if ( !empty($id) ):
				$params['id'] = $id;
			else:
				redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页
			endif;

			// 操作可能需要检查操作权限
			// $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

			// 页面信息
			$data = array(
				'title' => '修改'.$this->class_name_cn,
				'class' => $this->class_name.' edit',
				'error' => '', // 预设错误提示
			);

			// 从API服务器获取相应详情信息
			$url = api_url($this->class_name. '/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content'];
			else:
				redirect( base_url('error/code_404') ); // 若未成功获取信息，则转到错误页
			endif;

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
            $this->form_validation->set_rules('name', '名称', 'trim|required|max_length[30]');
            $this->form_validation->set_rules('url_name', 'URL名称', 'trim|min_length[5]|max_length[30]|alpha_dash');
            $this->form_validation->set_rules('description', '描述', 'trim|max_length[255]');
            $this->form_validation->set_rules('extra', '补充描述', 'trim|max_length[5000]');
            $this->form_validation->set_rules('url_image', '形象图', 'trim|max_length[255]');
            $this->form_validation->set_rules('url_audio', '背景音乐', 'trim|max_length[255]');
            $this->form_validation->set_rules('url_video', '形象视频', 'trim|max_length[255]');
            $this->form_validation->set_rules('url_video_thumb', '形象视频缩略图', 'trim|max_length[255]');
            $this->form_validation->set_rules('url_default_option_image', '选项默认占位图', 'trim|max_length[255]');

            $this->form_validation->set_rules('signup_allowed', '用户可报名', 'trim|required|in_list[否,是]');
            $this->form_validation->set_rules('option_censor', '候选项需审核', 'trim|required|in_list[否,是]');
			$this->form_validation->set_rules('max_user_total', '每选民最高总选票数', 'trim|is_natural|greater_than_equal_to[0]|less_than_equal_to[999]');
			$this->form_validation->set_rules('max_user_daily', '每选民最高日选票数', 'trim|greater_than[0]|less_than_equal_to[99]');
			$this->form_validation->set_rules('max_user_daily_each', '每选民同选项最高日选票数', 'trim|greater_than[0]|less_than_equal_to[99]');
            $this->form_validation->set_rules('content_css', '自定义样式', 'trim|max_length[5000]');
			
			$this->form_validation->set_rules('time_start', '开始时间', 'trim|exact_length[16]|callback_time_start');
			$this->form_validation->set_rules('time_end', '结束时间', 'trim|exact_length[16]|callback_time_end');
            $this->form_validation->set_message('time_start', '开始时间需详细到分，且晚于当前时间1分钟后');
            $this->form_validation->set_message('time_end', '结束时间需详细到分，且晚于当前时间1分钟后，亦不可早于开始时间（若有）');

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$data['error'] .= validation_errors();
			    $data['options'] = $this->list_vote_option($id);

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/edit', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 需要编辑的数据；逐一赋值需特别处理的字段
				$data_to_edit = array(
					'user_id' => $this->session->user_id,
					'id' => $id,

                    'max_user_total' => empty($this->input->post('max_user_total'))? 0: $this->input->post('max_user_total'),
                    'max_user_daily' => empty($this->input->post('max_user_daily'))? 1: $this->input->post('max_user_daily'),
                    'max_user_daily_each' => empty($this->input->post('max_user_daily_each'))? 1: $this->input->post('max_user_daily_each'),
                    'time_start' => empty($this->input->post('time_start'))? NULL: $this->strto_minute($this->input->post('time_start')), // 时间仅保留到分钟，下同
                    'time_end' => empty($this->input->post('time_end'))? NULL: $this->strto_minute($this->input->post('time_end')),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'name', 'url_name', 'description', 'url_image', 'url_audio', 'url_video', 'url_video_thumb', 'url_default_option_image', 'signup_allowed', 'option_orders', 'content_css',
                );
				foreach ($data_need_no_prepare as $name)
					$data_to_edit[$name] = $this->input->post($name);

				// 向API服务器发送待创建数据
				$params = $data_to_edit;
				$url = api_url($this->class_name. '/edit');
				$result = $this->curl->go($url, $params, 'array');
				if ($result['status'] === 200):
					$data['title'] = $this->class_name_cn. '修改成功';
					$data['class'] = 'success';
					$data['content'] = $result['content']['message'];
					$data['operation'] = 'edit';
					$data['id'] = $id; // 修改后的信息ID

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/result', $data);
					$this->load->view('templates/footer', $data);

				else:
					// 若修改失败，则进行提示
					$data['error'] = $result['content']['error']['message'];

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/edit', $data);
					$this->load->view('templates/footer', $data);

				endif;

			endif;
		} // end edit

        /**
         * 以下为工具类方法
         */

        // 检查起始时间
        public function time_start($value)
        {
            if ( empty($value) ):
                return true;

            elseif (strlen($value) !== 16):
                return false;

            else:
                // 将精确到分的输入值拼合上秒值
                $time_to_check = strtotime($value.':00');

                // 该时间不可早于当前时间一分钟以内
                if ($time_to_check <= time() + 60):
                    return false;
                else:
                    return true;
                endif;

            endif;
        } // end time_start

        // 检查结束时间
        public function time_end($value)
        {
            if ( empty($value) ):
                return true;

            elseif (strlen($value) !== 16):
                return false;

            else:
                // 将精确到分的输入值拼合上秒值
                $time_to_check = strtotime($value.':00');

                // 该时间不可早于当前时间一分钟以内
                if ($time_to_check <= time() + 60):
                    return false;

                // 若已设置开始时间，不可早于开始时间一分钟以内
                elseif ( !empty($this->input->post('time_start')) && $time_to_check < strtotime($this->input->post('time_start')) + 60):
                    return false;

                else:
                    return true;

                endif;

            endif;
        } // end time_end

    } // end class Vote

/* End of file Vote.php */
/* Location: ./application/controllers/Vote.php */
