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

    public function matchAll($id=0){
        $list = $this->findAll('bbs="'.self::AUTOCAR.'" AND is_fetch=1');
        if($id)
            $list = $this->findAll('id='.$id);
        else{
            foreach($list as $li){
            echo '<a href="/zzz/a.php?id='.$li['id'].'">'.$li['model'].'</a><br/><br/>';
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
        sleep(1);
        $url = 'https://club.autohome.com.cn'.$src;
        $url = str_replace('-1.html','-'.$page.'.html',$url);
        $html = Http::curl($url,[],'get','https://club.autohome.com.cn/bbs/forum-c-3852-1.html?orderby=dateline&qaType=-1#pvareaid=101061');
        if(empty($html)){
            if($page>1) return;
            $html = Http::curl($url);
            if(empty($html)){
                $html = Http::curl($url);
                if(empty($html)) return $this->fetchBBSItem($src);
            }
        }
        $id = $this->bbs->data['car_id'];
        $p = @$_GET['page']?$_GET['page']:1;
        if(strpos($html,'V2</title>'))
            exit("<head><title>$id-$p</title><meta http-equiv=\"refresh\" content=\"3;url=/zzz/a.php?id=$id&page=$p\"> </head><body>1</body>");
        if(!strpos($html,'x-pages2')) $html = @gzdecode($html);
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
        }
        $list = $xpath->query('//ul[@class="maxw"]/li/a');
        if(count($list)==0) return;
        foreach($list as $li){
            $this->bbs->data['author'] = trim($li->nodeValue);
            //$this->bbs->insert();
            print_r($this->bbs->data);echo 'ok';
            exit;
            return;
        }
        $page++;
        $this->fetchBBSItem($src,$page);
    }

    private function fetchBBS($core){
        $id = $this->bbs->data['car_id'];
        $page = @$_GET['page']?$_GET['page']:1;
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
                        exit("<head><title>$id-$page</title><meta http-equiv=\"refresh\" content=\"3;url=/zzz/a.php?id=$id&page=$page\"> </head><body>1</body>");
                    }
                }
            }
            echo 'aaa';exit;
            $html = mb_convert_encoding($html,'utf-8','gb2312');
            $html = str_replace('charset=gb2312','charset=utf-8',$html);
            if(strpos($html,'V2</title>'))
                exit("<head><title>$id-$page</title><meta http-equiv=\"refresh\" content=\"3;url=/zzz/a.php?id=$id&page=$page\"> </head><body>1</body>");
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
            if(count($list)<1) exit($url);
            foreach($list as $li){
                $url = trim($li->childNodes[3]->attributes->getNamedItem('href')->value);
                if(empty($url)) continue;
                $url2 = 'https://club.autohome.com.cn'.$url;
                if($this->bbs->findOne('url="'.$url2.'"')) continue;
                @$this->bbs->data['title'] = trim($li->childNodes[3]->nodeValue);
                $date = @$li->nextSibling->nextSibling->childNodes[2]->nodeValue;
                if(empty($date)) continue;
                $date = trim($date);
                $time = strtotime($date);
                if($time < $this->time){
                    if($page==1) continue;
                    echo "finish\r\n";
                    return;
                }
                @$this->bbs->data['created'] = date('Y-m-d',$time);
                $this->fetchBBSItem($url);
                sleep(1);
            }
            if($p != $page) return;
            $page++;
            echo "<head><title>$id-$page</title><meta http-equiv=\"refresh\" content=\"3;url=/zzz/test.php?id=$id&page=$page\"> </head><body>1</body>";
            return;
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