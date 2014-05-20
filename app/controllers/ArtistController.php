<?php

class ArtistController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        $this->crumb->append('Home','left',true);
        $this->crumb->append(strtolower($this->controller_name));

        $this->title = $this->controller_name;

        $this->model = new Artist();

    }


    public function getIndex()
    {

        $this->heads = array(
            array('Artist',array('search'=>true,'sort'=>true)),
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
            array('artistName',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('genre',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('ownerName',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('tags',array('kind'=>'text','query'=>'like','pos'=>'both','callback'=>'splitTag','show'=>true)),
            array('updated_at',array('kind'=>'date','query'=>'like','pos'=>'both','show'=>true)),
        );

        $this->query = array('ownerId'=>Auth::user()->_id);
        return parent::postIndex();
    }

    public function postAdd($data = null)
    {

        $this->validator = array(
            'artistName' => 'required'
        );

        return parent::postAdd($data);
    }

    public function postEdit($id,$data = null)
    {
        $this->validator = array(
            'artistName' => 'required'
        );

        return parent::postEdit($id,$data);
    }

    public function beforeSave($data){
        $data['ownerId'] = Auth::user()->_id;
        $data['ownerName'] = Auth::user()->firstname.' '.Auth::user()->lastname;
        return $data;
    }

    public function beforeUpdate($id,$data){
        $data['ownerId'] = Auth::user()->_id;
        $data['ownerName'] = Auth::user()->firstname.' '.Auth::user()->lastname;
        return $data;
    }

    public function afterSave($data){

        $artist = Artist::where('artistName','=',$data['artist'])
            ->where('ownerId','=',$data['ownerId'])
            ->first();

        if($artist == null){
            $artist = new Artist();
        }

        $artist->artistName = $data['artist'];
        $artist->ownerId = Auth::user()->_id;
        $artist->ownerName = Auth::user()->firstname.' '.Auth::user()->lastname;

        $artist->save();

        return $data;
    }

    public function afterUpdate($id,$data = null){

        $artist = Artist::where('artistName','=',$data['artist'])->where('ownerId','=',Auth::user()->_id)->first();

        if($artist == null){
            $artist = new Artist();
        }

        $artist->artistName = $data['artist'];
        $artist->ownerId = Auth::user()->_id;
        $artist->ownerName = Auth::user()->firstname.' '.Auth::user()->lastname;

        $artist->save();

        return $id;
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

    public function getViewpics($id)
    {

    }


}
