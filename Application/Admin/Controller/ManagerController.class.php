<?php
namespace Admin\Controller;
use Admin\Controller;

/**
 * @author Zhou Yuyang <1009465756@qq.com> 12:28 2016/1/26
 * @copyright 2105-2018 SRCMS 
 * @homepage http://www.src.pw
 * @version 1.6
 */

/**
 * 后台用户管理
 */
class ManagerController extends BaseController
{
    /**
     * 用户列表
     * @return [type] [description]
     */
    public function index($key="")
    {
        if($key == ""){
            $model = M('manager');  
        }else{
            $where['username'] = array('like',"%$key%");
            $where['email'] = array('like',"%$key%");
            $where['_logic'] = 'or';
            $model = M('member')->where($where); 
        } 
        
        $count  = $model->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Extend\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        $member = $model->limit($Page->firstRow.','.$Page->listRows)->where($where)->order('id DESC')->select();
        $this->assign('member', $member);
        $this->assign('page',$show);
        $this->display();     
    }

    /**
     * 添加用户
     */
    public function add()
    {
        //默认显示添加表单
        if (!IS_POST) {
            $this->display();
        }
        if (IS_POST) {
            //如果用户提交数据
            $model = D("Manager");
			$salt = $this->GetSalt();
			$data['username'] = I('username');
			$data['email']= I('email');
			$data['password'] = I('password');
			$data['salt'] = $salt;
		    $repassword= I('repassword');
			$data['password'] = md5(base64_encode($data['salt']).md5(md5($data['salt']).md5($data['password'])."HRSRC")."OPENSISE");
			$data['create_at']=time();
                if ($model->field('username,email,salt,password,create_at')->data($data)->add()) {
                    $this->success("后台用户添加成功", U('manager/index'));
                } else {
                    $this->error("后台用户添加失败");
                }
            
        }
    }
    /**
     * 更新后台用户信息
     * @param  [type] $id [管理员ID]
     * @return [type]     [description]
     */
    public function update()
    {
        //默认显示添加表单
        if (!IS_POST) {
            $model = M('manager')->find(I('id',0,'intval'));
            $this->assign('model',$model);
            $this->display();
        }
        if (IS_POST) {
            $model = D("manager");
            if (!$model->field('username,email,password')->create()) {
                $this->error($model->getError());
            }else{
                //验证密码是否为空
				$user = $model->find(I('id'));
				   
                $data = I();
                unset($data['password']);
                if(I('password') != ""){
                    $data['password'] = md5(base64_encode($user['salt']).md5(md5($user['salt']).md5(I('password'))."HRSRC")."OPENSISE");
                }
                //更新
                if ($model->save($data)) {
                    $this->success("用户信息更新成功", U('manager/index'));
                } else {
                    $this->error("未做任何修改,用户信息更新失败");
                }        
            }
        }
    }
    /**
     * 删除后台用户
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function delete()
    {
		$id = I('get.id',0,'intval'); 
    	if(C('SUPER_ADMIN_ID') == $id) $this->error("超级管理员不可禁用!");
        $model = M('manager');
        //查询status字段值
        $result = $model->find($id);
        //更新字段
        $data['id']=$id;
        if($result['status'] == 1){
        	$data['status']=0;
        }
        if($result['status'] == 0){
        	$data['status']=1;
        }
        if($model->save($data)){
            $this->success("状态更新成功", U('manager/index'));
        }else{
            $this->error("状态更新失败");
        }
    }
	
	public function GetSalt()
	{
		$salt = "";
		 $chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		 for($num=0;$num<8;$num++)
		  {
			$RandNum = rand(0,strlen($chars)-1);  
			$salt .= $chars[$RandNum]; 
		  }
		  return $salt;
	}
}
