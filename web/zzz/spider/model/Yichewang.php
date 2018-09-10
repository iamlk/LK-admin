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

class Yichewang extends Car{

    private $bbs = null;

    public function init(){
        parent::init();
        $this->bbs = new Bbs();
    }

    public function matchAll($id=0){
        $list = $this->findAll('bbs="'.self::YICHEWANG.'" AND is_fetch=1');
        if($id) $list = $this->findAll('id='.$id);
        else{
            foreach($list as $li){
                echo '<a target="_blank" href="/zzz/index.php?id='.$li['id'].'">'.$li['model'].'</a><br/><br/>';
            }
            return;
        }
        foreach($list as $li){
            @$this->bbs->data['car_id'] = $li['id'];
            $this->fetchBBS($li['core']);
        }
    }

    public function fetchBBSItem($src,$page=1){
        if($page>1) $url = str_replace('.html','-'.$page.'.html',$src);
        else    $url = $src;
        $html = Http::curl($url);
        if(empty($html)){
            if($page>1) return;
            $html = Http::curl($url);
            if(empty($html)){
                $html = Http::curl($url);
                if(empty($html)) return;
            }
        }
        if(!strpos($html,'linknow') && $page>1) return;
        @$this->bbs->data['url'] = $url;
        $dom = new DOMDocument();
        $libxml_previous_state = libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        libxml_use_internal_errors($libxml_previous_state);
        $xpath = new DOMXPath($dom);
        $list = $xpath->query('//div[@class="post_width"]');
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
        $i=0;
        while(true){
            $url = "http://baa.bitauto.com/$core/index-all-all-$page-1.html";
            //echo $url."\r\n";
            $html = Http::curl($url);
            if(empty($html)){
                sleep(1);
                $html = Http::curl($url);
                if(empty($html)){
                    sleep(1);
                    $html = Http::curl($url);
                    if(empty($html)){
                        sleep(1);
                        $i++;
                        if($i==10)
                            exit($url);
                        else
                            continue;
                    }
                }
            }
            if(!strpos($html,'linknow') && $page>1) return;
            $dom = new DOMDocument();
            $libxml_previous_state = libxml_use_internal_errors(true);
            $dom->loadHTML($html);
            libxml_clear_errors();
            libxml_use_internal_errors($libxml_previous_state);
            $xpath = new DOMXPath($dom);
            $list = $xpath->query('//div[@class="postscontent"]/div[@class="postslist_xh"]/ul/li[@class="bt"]');
            foreach($list as $li){
                $url = trim($li->firstChild->attributes->getNamedItem('href')->value);
                if(empty($url)) continue;
                if($this->bbs->findOne('url="'.$url.'"')) continue;
                $url2 = str_replace('.html','-1.html',$url);
                if($this->bbs->findOne('url="'.$url2.'"')) continue;
                @$this->bbs->data['title'] = trim($li->firstChild->nodeValue);
                $date = $li->nextSibling->nextSibling->nextSibling->nextSibling->childNodes[3]->nodeValue;
                $date = str_replace(' ','',trim($date));
                if(empty($date)){
                    $date = $li->nextSibling->nextSibling->nextSibling->nextSibling->childNodes[2]->nodeValue;
                    $date = str_replace(' ','',trim($date));
                }
                $time = strtotime($date);
                if($time < $this->time){
                    echo "finish\r\n";
                    return;
                }
                @$this->bbs->data['created'] = date('Y-m-d',$time);
                $this->fetchBBSItem($url);
            }
            $page++;
            sleep(2);
        }
        echo 'End';exit;
    }

    //匹配哪些车型需要抓取
    public function setFetch(){
        $file = fopen('./model.txt','r');
        while(!feof($file)){
            $line = trim(fgets($file));
            if(empty($line)) continue;
            $data = $this->findOne('bbs="'.self::YICHEWANG.'" AND model="'.$line.'"');
            if($data){
                $data['is_fetch'] = 1;
                $this->update($data);
            }else{
                $data = $this->findOne('bbs="'.self::YICHEWANG.'" AND CONCAT(brand,model) = "'.$line.'"');
                if($data){
                    $data['is_fetch'] = 1;
                    $this->update($data);
                }else{
                    echo $line."\n";
                }
            }
        }
    }

    private function fetchModel($url){
        $head = 'http://baa.bitauto.com/';
        $html = Http::curl($url);
        if(!trim($html)){
            echo $url;
            return;
        }
        $dom = new DOMDocument();
        $libxml_previous_state = libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        libxml_use_internal_errors($libxml_previous_state);
        $xpath = new DOMXPath($dom);
        $list = $xpath->query('//ul[@class="sub-car-box"]/li');
        foreach($list as $li){
            $core = $li->childNodes[0]->attributes->getNamedItem('href')->value;
            $core = substr($core,1);
            $this->data['core'] = $core;
            $this->data['url'] = $head.$core;
            $this->data['model'] = trim($li->childNodes[0]->firstChild->nodeValue);
            $this->insert();
        }
    }

    //获取网站所有车型和品牌
    public function fetchCar(){
        $this->data['bbs'] = self::YICHEWANG;
        $html = Http::curl('http://baa.bitauto.com/foruminterrelated/brandforumlist.html');
        $dom = new DOMDocument();
        $dom2 = new DOMDocument();
        $libxml_previous_state = libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        libxml_use_internal_errors($libxml_previous_state);
        $xpath = new DOMXPath($dom);
        $list = $xpath->query('//ul[@class="list-con"]/li');
        foreach($list as $li){
            $this->data['letter'] = trim($li->childNodes[1]->nodeValue);
            $html2 = $dom->saveHTML($li->childNodes[3]);
            $html2 = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'.$html2;
            $dom2->loadHTML($html2);
            $xpath2 = new DOMXPath($dom2);
            $list2 = $xpath2->query('//a');//brand
            foreach($list2 as $li2){
                $url2 = $li2->attributes->getNamedItem('href')->value;
                $this->data['brand'] = trim($li2->childNodes[3]->nodeValue);
                $this->fetchModel($url2);
            }
        }
    }
}