<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

/* Home 首页 */
$route['home'] = 'home/index'; // 首页

/* Account 账号 */
$route['login'] = 'account/login'; // 密码登录
$route['login_sms'] = 'account/login_sms'; // 短信登录/注册
$route['register'] = 'account/register'; // 邮箱注册
$route['logout'] = 'account/logout'; // 退出当前账号
$route['password_set'] = 'account/password_set'; // 设置密码（仅限登录后）
$route['password_reset'] = 'account/password_reset'; // 重置密码（仅限登录前）
$route['password_change'] = 'account/password_change'; // 修改密码（仅限登录后）
$route['email_reset'] = 'account/email_reset'; // 换绑Email（仅限登录后）
$route['mobile_reset'] = 'account/mobile_reset'; // 换绑手机号（仅限登录后）
$route['account/edit'] = 'account/edit'; // 编辑账户资料
$route['mine'] = 'account/mine'; // 个人中心（仅限登录后）

/* 以下按控制器类名称字母降序排列 */

/* Article 文章 */
$route['article/detail'] = 'article/detail'; // 详情
$route['article/edit'] = 'article/edit'; // 编辑
$route['article/create'] = 'article/create'; // 创建
$route['article/delete'] = 'article/delete'; // 删除
$route['article/restore'] = 'article/restore'; // 恢复
$route['article/trash'] = 'article/trash'; // 回收站
$route['article/(:any)'] = 'article/detail/$1';
$route['article'] = 'article/index'; // 列表

/* Item 产品 */
$route['item/detail'] = 'item/detail'; // 详情
$route['item/edit'] = 'item/edit'; // 编辑
$route['item/create'] = 'item/create'; // 创建
$route['item/delete'] = 'item/delete'; // 删除
$route['item/restore'] = 'item/restore'; // 恢复
$route['item/trash'] = 'item/trash'; // 回收站
$route['item/(:any)'] = 'item/detail/$1';
$route['item'] = 'item/index'; // 列表

/* Order 订单 */
$route['order/detail'] = 'order/detail'; // 详情
$route['order/(:num)'] = 'order/detail/$1';
$route['order'] = 'order/index'; // 列表

/* User 用户（无社交功能的前台一般可删除该组） */
$route['user/detail'] = 'user/detail'; // 用户详情
$route['user'] = 'user/index'; // 用户列表

$route['default_controller'] = 'home/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE; // 将路径中的“-”解析为“_”，兼顾SEO需要与类命名规范

/* End of file routes.php */
/* Location: ./application/config/routes.php */