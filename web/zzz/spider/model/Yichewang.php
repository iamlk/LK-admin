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

    public function koubei($id=0){
        $list = $this->findAll('bbs="'.self::YICHEWANG.'" AND is_fetch=1');
        if($id)
            $list = $this->findAll('id='.$id);
        else {
            foreach ($list as $li) {echo 'ok';
                $url = 'http://car.bitauto.com/'.$li['core'].'/koubei/';
                $html = Http::curl($url);
                $dom = new DOMDocument();
                $libxml_previous_state = libxml_use_internal_errors(true);
                $dom->loadHTML($html);
                libxml_clear_errors();
                libxml_use_internal_errors($libxml_previous_state);
                $xpath = new DOMXPath($dom);
                $list = $xpath->query('//a[@id="aTopicListUrl"]');
                echo count($list);
                foreach($list as $li){
                    print_r($li);
                }exit;
                echo '<a href="/zzz/y.php?id=' . $li['id'] . '">' . $li['model'] . '</a><br/><br/>';
            }
            exit;
        }
        foreach($list as $li){
            @$this->kb->data['car_id'] = $li['id'];
            $this->fetchKoubei($li['core']);
        }
        echo 'End';
    }


    private function fetchKoubei($core){
        $page = @$_GET['page']?$_GET['page']:1;
        $id = $this->kb->data['car_id'];
        while(true){
            $url = "http://www.12365auto.com/review/$core"."_0_0_$page.shtml";
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
            $dom = new DOMDocument();
            $libxml_previous_state = libxml_use_internal_errors(true);
            $dom->loadHTML($html);
            libxml_clear_errors();
            libxml_use_internal_errors($libxml_previous_state);
            $xpath = new DOMXPath($dom);
            $list = $xpath->query('//div[@class="kbfb"]/dl/dt');
            $l2 = $xpath->query('//div[@class="kbfb"]/dl/dd/div[@class="kbnr"]');
            $star = $xpath->query('//div[@class="kbfb"]/dl/dd/div[@class="cx_bt"]/p/b');
            if(count($list)==0) exit('<a href='.$url.'">finish</a>');
            if(count($l2)==0) exit('<a href='.$url.'">finish</a>');
            foreach($list as $i => $li){
                @$this->kb->data['uid'] = intval($li->attributes->getNamedItem('uid')->value);
                $this->kb->data['author'] = trim($li->childNodes[1]->nodeValue);
                $this->kb->data['note'] = trim($l2[$i]->childNodes[1]->nodeValue);
                $this->kb->data['merit'] = trim($l2[$i]->childNodes[3]->childNodes[0]->nodeValue);
                $this->kb->data['defect'] = trim($l2[$i]->childNodes[3]->childNodes[2]->nodeValue);
                $this->kb->data['summary'] = trim($l2[$i]->childNodes[3]->childNodes[4]->nodeValue);
                $date = $l2[$i]->childNodes[3]->childNodes[6]->nodeValue;
                $date = str_replace('----','',$date);
                $date = trim($date);
                $this->kb->data['created'] = $date;
                $this->kb->data['star'] = trim($star[$i]->nodeValue);
                unset($this->kb->data['id']);
                $this->kb->insert();
            }
            $page++;
        }
    }

    public function matchAll($id=0){
        $list = $this->findAll('bbs="'.self::YICHEWANG.'" AND is_fetch=1 AND letter is not null');
        if($id) $list = $this->findAll('id='.$id);
        else{
            foreach($list as $li){
                echo '<a target="_blank" href="/zzz/y.php?id='.$li['id'].'">'.$li['model'].'</a><br/><br/>';
            }
            return;
        }
        foreach($list as $li){
            @$this->bbs->data['car_id'] = $li['id'];
            $this->fetchBBS($li['core']);
        }
        echo 'End';
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
                if(empty($html)) return $this->fetchBBSItem($src);
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
            break;
        }
        $list = $xpath->query('//div[@class="user_name"]/a');
        if(count($list)==0) return;
        foreach ($list as $li){
            @$this->bbs->data['author'] = trim($li->nodeValue);
            $this->bbs->insert();
            return;
        }
        $page++;
        $this->fetchBBSItem($src,$page);
    }

    private function fetchBBS($core){
        $page = @$_GET['page']?$_GET['page']:1;
        $id = $this->bbs->data['car_id'];
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
            $i=0;
            if(!strpos($html,'linknow') && $page>1) return;
            $dom = new DOMDocument();
            $libxml_previous_state = libxml_use_internal_errors(true);
            $dom->loadHTML($html);
            libxml_clear_errors();
            libxml_use_internal_errors($libxml_previous_state);
            $xpath = new DOMXPath($dom);
            $list = $xpath->query('//div[@class="postscontent"]/div[@class="postslist_xh"]/ul/li[@class="bt"]');
            if(count($list)<1) exit('<a href="http://localhost/zzz/y.php?id='.$id.'&page='.$page.'">'.$url.'</a>');
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