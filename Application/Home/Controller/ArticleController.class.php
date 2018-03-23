<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

/**
 * 文档模型控制器
 * 文档模型列表和详情
 */
class ArticleController extends HomeController {

    /* 文档模型频道页 */
	public function index(){
		/* 分类信息 */
		$category = $this->category();

		//频道页只显示模板，默认不读取任何内容
		//内容可以通过模板标签自行定制

		/* 模板赋值并渲染模板 */
		$this->assign('category', $category);
		$this->display($category['template_index']);
	}

	/* 文档模型多列表页 */
	public function lists_all($p = 1){
		/* 分类信息 */
		$category = $this->category_lists();
		/* 获取当前分类列表 */
		$Document = D('Document');
        $list = $Document->page($p, 10)->lists($category['id']);
		if(false === $list){
			$this->error('获取列表数据失败！');
		}
		/* 模板赋值并渲染模板 */
		$this->assign('category', $category);
		$this->assign('list', $list);
		$this->assign('focus', '钦家动态');
		$this->display('lists');
	}

    /* 文档模型列表 */
    public function lists($p = 1){
        /* 分类信息 */
        $category = $this->category();

        /* 获取当前分类列表 */
        $Document = D('Document');
        $list = $Document->page($p, 10)->lists($category['id']);
        if(false === $list){
            $this->error('获取列表数据失败！');
        }

        /* 模板赋值并渲染模板 */
        $this->assign('focus', '钦家动态');
        $this->assign('category', $category);
        $this->assign('list', $list);
        $this->display($category['template_lists']);
    }

	/* 文档模型详情页 */
	public function detail($id = 0, $p = 1){
		/* 标识正确性检测 */
		if(!($id && is_numeric($id))){
			$this->error('文档ID错误！');
		}

		/* 页码检测 */
		$p = intval($p);
		$p = empty($p) ? 1 : $p;

		/* 获取详细信息 */
		$Document = D('Document');
		$info = $Document->detail($id);
		if(!$info){
			$this->error($Document->getError());
		}
        $previnfo=$Document->prev($info);
        $nextinfo=$Document->next($info);
        /* 上一篇标题 */
        $this->assign('prev_title',$nextinfo['title']);
        $this->assign('prev_id',$nextinfo['id']);
        /* 下一篇标题 */
        $this->assign('next_title',$previnfo['title']);
        $this->assign('next_id',$previnfo['id']);

		/* 分类信息 */
		$category = $this->category($info['category_id']);

		/* 获取模板 */
		if(!empty($info['template'])){//已定制模板
			$tmpl = $info['template'];
		} elseif (!empty($category['template_detail'])){ //分类已定制模板
			$tmpl = $category['template_detail'];
		} else { //使用默认模板
			$tmpl = 'Article/'. get_document_model($info['model_id'],'name') .'/detail';
		}

		/* 更新浏览数 */
		$map = array('id' => $id);
		$Document->where($map)->setInc('view');

		/* 模板赋值并渲染模板 */
		$this->assign('category', $category);
		$this->assign('info', $info);
		$this->assign('page', $p); //页码
		$this->display($tmpl);
	}

	/* 多文档分类检测 */
	private function category_lists(){
		/* 标识正确性检测 */
		$id1=0;
        $id2=0;
		$id1 = $id1 ? $id1 : 'mtbd';
		$id2 = $id2 ? $id2 : 'qjxw';

		if(empty($id1)&&empty($id2)){
			$this->error('没有指定文档分类！');
		}

		/* 获取分类信息 */
		$category=[];
		$category['c1'] = D('Category')->info($id1);
		$category['c2'] = D('Category')->info($id2);
		foreach ($category as $val){
            if($val && 1 == $val['status']){
                if($val['display']==0)$this->error('该分类禁止显示！');
            } else {
                $this->error('分类不存在或被禁用！');
            }
        }
        return $category;
	}

    /* 文档分类检测 */
    private function category($id = 0){
        /* 标识正确性检测 */
        $id = $id ? $id : I('get.category', 0);
        if(empty($id)){
            $this->error('没有指定文档分类！');
        }

        /* 获取分类信息 */
        $category = D('Category')->info($id);
        if($category && 1 == $category['status']){
            switch ($category['display']) {
                case 0:
                    $this->error('该分类禁止显示！');
                    break;
                //TODO: 更多分类显示状态判断
                default:
                    return $category;
            }
        } else {
            $this->error('分类不存在或被禁用！');
        }
    }

}
