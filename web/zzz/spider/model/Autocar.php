<?php
/**
 * Created by PhpStorm.
 * User: Leonidax
 * Date: 2018/9/2
 * Time: 10:14
 */

namespace spider\model;
use spider\tool\Http;
use \DOMDocument;
use \DOMXPath;
use spider\model\Car;

class Autocar extends Car{

    private $bbs = null;
    private $letters = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','V','W','X','Y','Z'];

    public function init(){
        parent::init();
        $this->bbs = new Bbs();
    }

    public function matchAll(){
        $list = $this->findAll('bbs="'.self::AUTOCAR.'" AND is_fetch=1');
        foreach($list as $li){
            @$this->bbs->data['car_id'] = $li['id'];
            $this->fetchBBS($li['core']);
        }
    }

    public function fetchBBSItem($src,$page=1){
        $url = str_replace('-1.html','-'.$page.'.html',$src);
        $url = "https://club.autohome.com.cn/bbs/thread/150663af40430380/75641540-1.html";
        $html = Http::curl($url,[],'get','https://club.autohome.com.cn/bbs/forum-c-3852-1.html?orderby=dateline&qaType=-1#pvareaid=101061');
        if(empty($html)){
            if($page>1) return;
            $html = Http::curl($url);
            if(empty($html)){
                $html = Http::curl($url);
                if(empty($html)) return;
            }
        }
        $html = gzdecode($html);
        $html = mb_convert_encoding($html,'utf-8','gb2312');
        $html = str_replace('charset=gb2312','charset=utf-8',$html);
        if(!strpos($html,'x-pages2')) return;

        @$this->bbs->data['url'] = $url;
        $dom = new DOMDocument();
        $libxml_previous_state = libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        libxml_use_internal_errors($libxml_previous_state);
        $xpath = new DOMXPath($dom);
        $list = $xpath->query('//div[@class="w740"]');
        if(count($list)==0) return;
        foreach($list as $li){
            unset($this->bbs->data['id']);
            @$this->bbs->data['content'] = trim($li->nodeValue);
            $this->bbs->insert();
        }
        $page++;
        $this->fetchBBSItem($src,$page);
    }

    private function fetchBBS($core){
        $page = 1;
        while(true){
            $url = "https://club.autohome.com.cn/bbs/forum-c-$core-$page.html?orderby=dateline&qaType=-1";
            $html = Http::curl($url);
            if(empty($html)){
                sleep(1);
                $html = Http::curl($url);
                if(empty($html)){
                    sleep(1);
                    $html = Http::curl($url);
                    if(empty($html)){
                        continue;
                    }
                }
            }
            $html = mb_convert_encoding($html,'utf-8','gb2312');
            $html = str_replace('charset=gb2312','charset=utf-8',$html);
            $match = [];
            preg_match("/<span class='cur'>(.*?)<\/span>/", $html,$match);
            $p = $match[1];
            $dom = new DOMDocument();
            $libxml_previous_state = libxml_use_internal_errors(true);
            $dom->loadHTML($html);
            libxml_clear_errors();
            libxml_use_internal_errors($libxml_previous_state);
            $xpath = new DOMXPath($dom);
            $list = $xpath->query('//div[@id="subcontent"]/dl[@class="list_dl"]/dt');
            foreach($list as $li){
                $url = trim($li->childNodes[3]->attributes->getNamedItem('href')->value);
                if(empty($url)) continue;
                if($this->bbs->findOne('url="'.$url.'"')) continue;
                @$this->bbs->data['title'] = trim($li->childNodes[3]->nodeValue);
                $date = $li->nextSibling->nextSibling->childNodes[2]->nodeValue;
                $date = trim($date);
                $time = strtotime($date);
                if($time < $this->time){
                    if($page==1) continue;
                    echo "finish\r\n";
                    return;
                }
                @$this->bbs->data['created'] = date('Y-m-d',$time);
                $this->fetchBBSItem($url);exit;
            }
            if($p == $page) return;
            $page++;
        }
    }

    //匹配哪些车型需要抓取
    public function setFetch(){
        $file = fopen('./model.txt','r');
        while(!feof($file)){
            $line = trim(fgets($file));
            if(empty($line)) continue;
            $data = $this->findOne('bbs="'.self::AUTOCAR.'" AND model="'.$line.'"');
            if($data){
                $data['is_fetch'] = 1;
                $this->update($data);
            }else{
                $data = $this->findOne('bbs="'.self::AUTOCAR.'" AND CONCAT(brand,model) = "'.$line.'"');
                if($data){
                    $data['is_fetch'] = 1;
                    $this->update($data);
                }else{
                    echo $line."\n";
                }
            }
        }
    }

    //获取网站所有车型和品牌
    public function fetchCar(){
        $this->data['bbs'] = self::AUTOCAR;
        $post = ['type'=>'c','exttype'=>'0','initial'=> 'C'];
        foreach($this->letters as $letter){
            $this->data['letter'] = $letter;
            $url = 'https://club.autohome.com.cn/clubindex/indexv2?1536135988592';
            $post['initial'] = $letter;
            $html = Http::curl($url,$post,'post');
            $json = mb_convert_encoding($html,'utf-8', 'gb2312');
            $list = json_decode($json, true);
            $list = json_decode($list, true);
            foreach($list as $li){
                $this->data['brand'] = $li['brand_name'];
                $this->data['model'] = str_replace('论坛','',$li['Name']);
                $this->data['core'] = $li['TypeId'];
                $this->data['url'] = 'https://club.autohome.com.cn/bbs/forum-c-'.$li['TypeId'].'-1.html';
                unset($this->data['id']);
                $this->insert();
            }
        }
    }
}