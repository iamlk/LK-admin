<?php
/**
 * Created by PhpStorm.
 * User: Leonidax
 * Date: 2018/9/3
 * Time: 下午9:08
 */

namespace spider\tool;

use spider\model\Bbs;
use spider\model\Category;
use spider\model\Pcmatch;

class Match{
    public function match(){
        $cate = new Category();
        $list = $cate->findAll('level=2');
        $key = [];
        foreach($list as $li){
            if(empty($li['keywords'])) continue;
            $tmp = explode("\n",$li['keywords']);
            $key[$li['id']] = $tmp;
        }
        $bbs = new Bbs();
        $p = 0;
        $list = $bbs->findAll('1=1','id,content');
        foreach($list as $li){
            foreach($key as $cate_id => $keys){
                foreach($keys as $k){
                    $k = trim($k);
                    if(($p=mb_strpos($li['content'],$k)) !== false){
                        $m = new Pcmatch();
                        $data = [];
                        $data['cate_id']=$cate_id;
                        if($p<4)
                            $s = mb_substr($li['content'],0,$p);
                        else
                            $s = mb_substr($li['content'],($p-4),4);
                        $data['head_keyword'] = $s;
                        $data['keyword'] = $k;
                        $data['bbs_id'] = $li['id'];
                        $len = mb_strlen($k);
                        $s = mb_substr($li['content'],($p+$len),10);
                        $data['keyword_end']=$s;
                        $m->insert($data);
                    }
                }
            }
        }
    }
}