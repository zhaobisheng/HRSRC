<?php
namespace Admin\Controller;
use Admin\Controller;
/**
 * @author Zhou Yuyang <1009465756@qq.com> 12:28 2016/1/23
 * @copyright 2105-2018 SRCMS 
 * @homepage http://www.src.pw
 * @version 1.5
 */
 
/**
 * 单页管理
 */
class OrderController extends BaseController
{
    /**
     * 单页列表
     * @return [type] [description]
     */
    public function index($key="")
    {
        if($key == ""){
            $model = M('order');  
        }else{
            $where['title'] = array('like',"%$key%");
            $where['name'] = array('like',"%$key%");
            $where['_logic'] = 'or';
            $model = M('order')->where($where); 
        } 
        
        $count  = $model->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Extend\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        $pages = $model->limit($Page->firstRow.','.$Page->listRows)->where($where)->order('id DESC')->select();
        $this->assign('model', $pages);
        $this->assign('page',$show);
        $this->display();     
    }

    public function delete()
    {
		$id = I('get.id',0,'intval');
        $model = M('order');
        $result = $model->where("id=".$id)->delete();
        if($result){
            $this->success("删除成功", U('order/index'));
        }else{
            $this->error("删除失败");
        }
    }
	public function update()
    {
		$id = I('get.id',0,'intval');
        //默认显示添加表单
        if (!IS_POST) {
            $model = M('order')->where('id='.$id)->find();
            $this->assign('model',$model);
            $this->display();
        }
        if (IS_POST) {
			$id = I('id');
            $model = D("order");
			$checkId = I('gid');
			$data['finish']=I('state');
			if($id!=$checkId){ $this->error("信息不正确");}
					
                if ($model->where('id='.$checkId)->save($data)) {
                    $this->success("更新成功", U('order/index'));
                } else {
                    $this->error("更新失败");
                }        
            }
        }
    
}
