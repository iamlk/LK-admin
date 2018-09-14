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

class Chezhiwang extends Car{

    private $bbs = null;
    private $kb = null;

    public function init(){
        parent::init();
        $this->bbs = new Bbs();
        $this->kb = new Koubei();
    }

    public function koubei($id=0){
        $list = $this->findAll('bbs="'.self::CHEZHIWANG.'" AND is_fetch=1');
        if($id)
            $list = $this->findAll('id='.$id);
        else {
            foreach ($list as $li) {
                echo '<a href="/zzz/c.php?id=' . $li['id'] . '">' . $li['model'] . '</a><br/><br/>';
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
        $list = $this->findAll('bbs="'.self::CHEZHIWANG.'" AND is_fetch=1');
        if($id)
            $list = $this->findAll('id='.$id);
        else {
            foreach ($list as $li) {
                echo '<a href="/zzz/c.php?id=' . $li['id'] . '">' . $li['model'] . '</a><br/><br/>';
            }
            exit;
        }
        foreach($list as $li){
            @$this->bbs->data['car_id'] = $li['id'];
            $this->fetchBBS($li['core']);
        }
        echo 'End';
    }

    public function fetchBBSItem($src,$page=1){
        if($page==1) $url = $src;
        else $url = $src.'&page='.$page;
        $html = Http::curl($url,[],'get','http://bbs.12365auto.com/seriesTopicList.aspx?page=2&orderType=1&sId=3');
        if(empty($html)){
            if($page>1) return;
            $html = Http::curl($url);
            if(empty($html)){
                $html = Http::curl($url);
                if(empty($html)) return $this->fetchBBSItem($src);
            }
        }
        $match = [];
        preg_match("/<a (.*?) class='fir'>(.*?)<\/a>/", $html,$match);
        $p = trim($match[2]);
        if(empty($p)) return;

        @$this->bbs->data['url'] = $url;
        $dom = new DOMDocument();
        $libxml_previous_state = libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        libxml_use_internal_errors($libxml_previous_state);
        $xpath = new DOMXPath($dom);
        $list = $xpath->query('//div[@class="nr_r_c"]/div[@class="neirong"]');
        if(count($list)==0) return;
        foreach($list as $li){
            unset($this->bbs->data['id']);
            @$this->bbs->data['content'] = trim($li->nodeValue);
            break;
        }
        $list = $xpath->query('//div[@class="nr_l_s"]/dl/dt/p');
        if(count($list)==0) return;
        foreach($list as $li){
            @$this->bbs->data['author'] = trim($li->nodeValue);
            $this->bbs->insert();
            return;
        }
        if($p != $page) return;
        $page++;
        $this->fetchBBSItem($src,$page);
    }

    private function fetchBBS($core){
        $page = @$_GET['page']?$_GET['page']:1;
        $id = $this->bbs->data['car_id'];
        while(true){
            $url = "http://bbs.12365auto.com/seriesTopicList.aspx?page=$page&orderType=1&sId=$core";
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
            $html = str_replace('charset=GB2312','charset=utf-8',$html);
            $dom = new DOMDocument();
            $libxml_previous_state = libxml_use_internal_errors(true);
            $dom->loadHTML($html);
            libxml_clear_errors();
            libxml_use_internal_errors($libxml_previous_state);
            $xpath = new DOMXPath($dom);
            $list = $xpath->query('//table[@id="tbrow"]/tr');
            if(count($list)<1) exit('<a href="http://localhost/zzz/c.php?id='.$id.'&page='.$page.'">'.$url.'</a>');
            foreach($list as $li){
                $url = trim($li->childNodes[0]->childNodes[3]->attributes->getNamedItem('href')->value);
                if(empty($url)) continue;
                if($this->bbs->findOne('url="'.$url.'"')) continue;
                @$this->bbs->data['title'] = trim($li->childNodes[0]->childNodes[3]->nodeValue);
                $date = @$li->childNodes[2]->childNodes[3]->nodeValue;
                if(empty($date)) continue;
                $date = trim($date);
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
    }

    //匹配哪些车型需要抓取
    public function setFetch(){
        $file = fopen('./model.txt','r');
        while(!feof($file)){
            $line = trim(fgets($file));
            if(empty($line)) continue;
            $data = $this->findOne('bbs="'.self::CHEZHIWANG.'" AND model="'.$line.'"');
            if($data){
                $data['is_fetch'] = 1;
                $this->update($data);
            }else{
                $data = $this->findOne('bbs="'.self::CHEZHIWANG.'" AND CONCAT(brand,model) = "'.$line.'"');
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
        $this->data['bbs'] = self::CHEZHIWANG;
        $url = 'http://www.12365auto.com/js/brandsHaveSeries.js?version=20180906';
        $content = Http::curl($url);
        $content = str_replace('var brandsHaveSeries = ','',$content);
        $content = trim(str_replace(';', '',$content));
        $content = mb_convert_encoding($content,'utf-8','gb2312');
        $list = json_decode($content,true);
        foreach($list as $li){
            $this->data['letter'] = $li['initials'];
            $this->data['brand'] = $li['name'];
            $configs = $li['config'];
            foreach($configs as $config){
                $list2 = $config['config'];
                foreach($list2 as $li2){
                    $this->data['model'] = $li2['seriesName'];
                    $this->data['core'] = $li2['seriesId'];
                    $this->data['url'] = 'http://bbs.12365auto.com/seriesTopicList.aspx?sId='.$li2['seriesId'];
                    unset($this->data['id']);
                    //$this->insert();
                }
            }
        }
    }
}