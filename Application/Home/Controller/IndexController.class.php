<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Home\Model\CategoryModel;
use Home\Model\DocumentModel;
use OT\DataDictionary;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController {

    //系统首页
    public function index(){
//        $this->assign('category',$category);//栏目
//        $this->assign('lists',$lists);//列表
//        $this->assign('page',D('Document')->page);//分页

        $Document = D('Document');
        $Category = M('Category');
        //媒体报道
        $mtbd_id = $Category->field('id')->where("name = 'mtbd'")->find();
        $mtbd_list =$Document->lists($mtbd_id['id']);
        //钦家新闻
        $qjxw_id = $Category->field('id')->where("name = 'qjxw'")->find();
        $qjxw_list =$Document->lists($qjxw_id['id']);
        //公益活动
        $gybd_id = $Category->field('id')->where("name = 'gybd'")->find();
        $gybd_list =$Document->lists($gybd_id['id']);
        $public=(int)(count($gybd_list)/6);
        if ($public>3)$public=3;
        $public_list=[];
        for ($i=0; $i<$public; $i++) {
            $public_list[$i]=$i;
        }
        $this->assign('focus',"首页");
        $this->assign('mtbd_list',$mtbd_list);
        $this->assign('qjxw_list',$qjxw_list);
        $this->assign('gybd_list',$gybd_list);
        $this->assign('public_list',$public_list);
        $this->display();
    }

    public function rescue(){

        $this->assign('focus',"一呼百应");//列表
        $this->display();
    }

    public function instructions(){
        $this->assign('focus',"使用钦家");
        $this->display();
    }

    public function introduction(){
        $this->assign('focus',"关于钦家");
        $this->assign('column',"品牌故事");
        $this->display();
    }

    public function technology(){
        $this->assign('focus',"关于钦家");
        $this->assign('column',"领先技术");
        $this->display();
    }

    public function patent(){
        $this->assign('focus',"关于钦家");
        $this->assign('column',"独家专利");
        $this->display();
    }

    public function join_us(){
        $this->assign('focus',"关于钦家");
        $this->assign('column',"加入我们");
        $this->display();
    }

    public function center(){
        $this->assign('focus',"关于钦家");
        $this->assign('column',"服务与帮助");
        $this->display();
    }

    public function product_elder(){
        $this->assign('focus',"老人定位贴");
        $this->display();
    }

    public function product_child(){
        $this->assign('focus',"儿童定位贴");
        $this->display();
    }

    public function product_pet(){
        $this->assign('focus',"宠物定位贴");
        $this->display();
    }
    public function product_goods(){
        $this->assign('focus',"物品定位贴");
        $this->display();
    }

    public function law(){
        $this->assign('focus',"关于钦家");
        $this->assign('column',"法律声明");
        $this->display();
    }

}