<?php
namespace User\Controller;
use Think\Controller;

/**
 * @author Zhou Yuyang <1009465756@qq.com> 12:28 2016/1/23
 * @copyright 2105-2018 SRCMS 
 * @homepage http://www.src.pw
 * @version 1.5
 */

class GiftController extends BaseController{

    public function index(){
        $gift = M('links')->select();
		$tmodel= M('setting');
		$title = $tmodel->where('id=1')->select();
		$this->assign('title', $title);
        $this->assign('gift',$gift);
        $this->display();
    }
	
	public function add()
    {
        //默认显示添加表单
		$id = session('userId');
		$gid = I('get.gid',0,'intval');
        if (!IS_POST) {
			$tmodel= M('setting');
		    $title = $tmodel->where('id=1')->select();
		    $this->assign('title', $title);
            $info = M('info')->where('user_id='.$id)->select();
			$gift = M('links')->where('id='.$gid)->select();
            $this->assign('info',$info);
			$this->assign('gift',$gift);
            $this->display();
        }
        if (IS_POST) {
            //如果用户提交数据
            $model = D("order");
			$model->user_id = 1;
			$model->username = 1;
			$data['username'] = session('user');
			$data['realname']= I('realname');
			$data['zipcode'] = I('zipcode');
       		$data['location'] = I('location');
			$data['tel'] = I('tel');
			$data['alipay'] = I('alipay');
			$data['gift'] = I('gift');
			$data['gid'] = I('gid');
			$uid = session('userId');
			$User = M('member');
			$shop = M('links');
			$price = $shop->where('id='.$data['gid'])->getField('jifen');
			$jifen = $User->where('id='.$uid.' AND username="'.$data['username'].'"')->getField('jifen');
			if($jifen>=$price){
			 if ($model->field('username,realname,zipcode,location,tel,alipay,gift,gid')->data($data)->add()) {
				 $newJifen = $jifen-$price;
				 $User-> where('id='.$uid)->setField('jifen',$newJifen);
               $this->success("下单成功", U('index/index'));
			   }
              } else {
                    $this->error("下单失败兑换该礼品需要".$price."积分");
                }
			
           /* if (!$model->field('username,email,password,repassword,gid')->create()) {
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($model->getError());
                exit();
            } else {
                if ($model->add()) {
                    $this->success("下单成功", U('index/index'));
                } else {
                    $this->error("下单失败");
                }
            }*/
		}
	}
}