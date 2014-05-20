<?php

class MusicController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        $this->crumb->append('Home','left',true);
        $this->crumb->append(strtolower($this->controller_name));

        $this->title = $this->controller_name;

        $this->model = new Music();

    }


    public function getIndex()
    {

        $this->heads = array(
            array('Song Title',array('search'=>true,'sort'=>true)),
            array('Artist',array('search'=>true,'sort'=>true)),
            array('Albums',array('search'=>true,'sort'=>true)),
            array('Genre',array('search'=>true,'sort'=>true)),
            array('Owner',array('search'=>true,'sort'=>true)),
            array('Tags',array('search'=>true,'sort'=>true)),
            array('Last Update',array('search'=>true,'sort'=>true,'date'=>true)),
        );


        return parent::getIndex();

    }

    public function postIndex()
    {

        $this->fields = array(
            array('songTitle',array('kind'=>'text','query'=>'like','pos'=>'both','callback'=>'pics','show'=>true)),
            array('artist',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('album',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true,'attr'=>array('class'=>'expander'))),
            array('genre',array('kind'=>'text','query'=>'like','callback'=>'splitGenre','pos'=>'both','show'=>true)),
            array('ownerName',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('tags',array('kind'=>'text','query'=>'like','pos'=>'both','callback'=>'splitTag','show'=>true)),
            array('lastUpdate',array('kind'=>'date','query'=>'like','pos'=>'both','show'=>true)),
        );

        return parent::postIndex();
    }

    public function postAdd($data = null)
    {

        $this->validator = array(
            'songTitle' => 'required',
            'slug'=> 'required'
        );

        return parent::postAdd($data);
    }

    public function postEdit($id,$data = null)
    {
        $this->validator = array(
            'songTitle' => 'required',
            'slug'=> 'required'
        );

        return parent::postEdit($id,$data);
    }

    public function beforeSave($data){
        $data['ownerId'] = Auth::user()->_id;
        $data['ownerName'] = Auth::user()->fullname;
        return $data;
    }

    public function beforeUpdate($id,$data){
        $data['ownerId'] = Auth::user()->_id;
        $data['ownerName'] = Auth::user()->fullname;
        return $data;
    }

    public function makeActions($data)
    {
        $delete = '<span class="del" id="'.$data['_id'].'" ><i class="icon-trash"></i>Delete</span>';
        $edit = '<a href="'.URL::to('music/edit/'.$data['_id']).'"><i class="icon-edit"></i>Update</a>';

        $actions = $edit.'<br />'.$delete;
        return $actions;
    }

    public function namePic($data)
    {
        $name = HTML::link('products/view/'.$data['_id'],$data['productName']);
        if(isset($data['thumbnail_url']) && count($data['thumbnail_url'])){
            $display = HTML::image($data['thumbnail_url'][0].'?'.time(), $data['filename'][0], array('id' => $data['_id']));
            return $display.'<br />'.$name;
        }else{
            return $name;
        }
    }

    public function pics($data)
    {
        $name = HTML::link('products/view/'.$data['_id'],$data['productName']);
        if(isset($data['thumbnail_url']) && count($data['thumbnail_url'])){
            $display = HTML::image($data['thumbnail_url'][0].'?'.time(), $data['filename'][0], array('style'=>'min-width:100px;','id' => $data['_id']));
            return $data['songTitle'].'<br />'.$display;
        }else{
            return $name;
        }
    }

    public function splitTag($data){
        $tags = explode(',',$data['tags']);
        if(is_array($tags) && count($tags) > 0 && $data['tags'] != ''){
            $ts = array();
            foreach($tags as $t){
                $ts[] = '<span class="tag">'.$t.'</span>';
            }

            return implode('', $ts);
        }else{
            return $data['tags'];
        }
    }

    public function splitGenre($data){
        $tags = explode(',',$data['genre']);
        if(is_array($tags) && count($tags) > 0 && $data['genre'] != ''){
            $ts = array();
            foreach($tags as $t){
                $ts[] = '<span class="tag">'.$t.'</span>';
            }

            return implode('', $ts);
        }else{
            return $data['genre'];
        }
    }

    public function getViewpics($id)
    {

    }


}
