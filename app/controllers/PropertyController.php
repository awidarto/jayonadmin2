<?php

class PropertyController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        $this->crumb->append('Home','left',true);
        $this->crumb->append(strtolower($this->controller_name));

        $this->model = new Property();
        //$this->model = DB::collection('documents');

    }

    public function getTest()
    {
        $raw = $this->model->where('docFormat','like','picture')->get();

        print $raw->toJSON();
    }


    public function getIndex()
    {

        $this->heads = array(
            array('Photos',array('search'=>false,'sort'=>false)),
            array('Property ID',array('search'=>true,'sort'=>true)),
            array('Source ID',array('search'=>true,'sort'=>true)),
            array('Number',array('search'=>true,'sort'=>true)),
            array('Address',array('search'=>true,'sort'=>true)),
            array('City',array('search'=>true,'sort'=>true)),
            array('ZIP',array('search'=>true,'sort'=>true)),
            array('State',array('search'=>true,'sort'=>true)),
            array('Bed',array('search'=>true,'sort'=>false)),
            array('Bath',array('search'=>true,'sort'=>true)),
            //array('Pool',array('search'=>true,'sort'=>true)),
            //array('Garage',array('search'=>true,'sort'=>true)),
            //array('Basement',array('search'=>true,'sort'=>true)),
            array('Category',array('search'=>true,'sort'=>true)),
            array('Tags',array('search'=>true,'sort'=>true)),
            array('Status',array('search'=>true,'sort'=>true, 'select'=>Config::get('ia.search_publishing'))),
            array('Created',array('search'=>true,'sort'=>true,'date'=>true)),
            array('Last Update',array('search'=>true,'sort'=>true,'date'=>true)),
        );

        //print $this->model->where('docFormat','picture')->get()->toJSON();

        $this->title = 'Property';

        $this->can_add = true;

        return parent::getIndex();

    }

    public function postIndex()
    {

        $this->fields = array(
            array('number',array('kind'=>'text','query'=>'like','pos'=>'both','callback'=>'namePic','show'=>true)),
            array('propertyId',array('kind'=>'text','query'=>'like','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('sourceID',array('kind'=>'text','query'=>'like','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('number',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('address',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('city',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('zipCode',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('state',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('bed',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('bath',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            //array('pool',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            //array('garage',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            //array('basement',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('category',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('tags',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('propertyStatus',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('createdDate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
            array('lastUpdate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
        );

        return parent::postIndex();
    }

    public function beforeSave($data)
    {
        $defaults = array();

        $files = array();

        // set new sequential ID
        $sequence = new Sequence();

        $seq = $sequence->getNewId('property');

        $data['sequence'] = $seq;

        $data['propertyId'] = Config::get('ia.property_id_prefix').$seq;

        if($data['propertyStatus'] == 'available'){
            $data['publishDate'] = $data['lastUpdate'];
        }

        if($data['propertyStatus'] == 'sold'){
            $data['soldDate'] = $data['lastUpdate'];
        }

        $data['listingPrice'] = new MongoInt32($data['listingPrice']);

        if( isset($data['file_id']) && count($data['file_id'])){

            $data['defaultpic'] = (isset($data['defaultpic']))?$data['defaultpic']:$data['file_id'][0];
            $data['brchead'] = (isset($data['brchead']))?$data['brchead']:$data['file_id'][0];
            $data['brc1'] = (isset($data['brc1']))?$data['brc1']:$data['file_id'][0];
            $data['brc2'] = (isset($data['brc2']))?$data['brc2']:$data['file_id'][0];
            $data['brc3'] = (isset($data['brc3']))?$data['brc3']:$data['file_id'][0];

            for($i = 0 ; $i < count($data['thumbnail_url']);$i++ ){

                if($data['defaultpic'] == $data['file_id'][$i]){
                    $defaults['thumbnail_url'] = $data['thumbnail_url'][$i];
                    $defaults['large_url'] = $data['large_url'][$i];
                    $defaults['medium_url'] = $data['medium_url'][$i];
                    $defaults['full_url'] = $data['full_url'][$i];
                }

                $files[$data['file_id'][$i]]['thumbnail_url'] = $data['thumbnail_url'][$i];
                $files[$data['file_id'][$i]]['large_url'] = $data['large_url'][$i];
                $files[$data['file_id'][$i]]['medium_url'] = $data['medium_url'][$i];
                $files[$data['file_id'][$i]]['full_url'] = $data['full_url'][$i];

                $files[$data['file_id'][$i]]['delete_type'] = $data['delete_type'][$i];
                $files[$data['file_id'][$i]]['delete_url'] = $data['delete_url'][$i];
                $files[$data['file_id'][$i]]['filename'] = $data['filename'][$i];
                $files[$data['file_id'][$i]]['filesize'] = $data['filesize'][$i];
                $files[$data['file_id'][$i]]['temp_dir'] = $data['temp_dir'][$i];
                $files[$data['file_id'][$i]]['filetype'] = $data['filetype'][$i];
                $files[$data['file_id'][$i]]['fileurl'] = $data['fileurl'][$i];
                $files[$data['file_id'][$i]]['file_id'] = $data['file_id'][$i];
                $files[$data['file_id'][$i]]['caption'] = $data['caption'][$i];
            }
        }else{
            $data['thumbnail_url'] = array();
            $data['large_url'] = array();
            $data['medium_url'] = array();
            $data['full_url'] = array();
            $data['delete_type'] = array();
            $data['delete_url'] = array();
            $data['filename'] = array();
            $data['filesize'] = array();
            $data['temp_dir'] = array();
            $data['filetype'] = array();
            $data['fileurl'] = array();
            $data['file_id'] = array();
            $data['caption'] = array();

            $data['defaultpic'] = '';
            $data['brchead'] = '';
            $data['brc1'] = '';
            $data['brc2'] = '';
            $data['brc3'] = '';
        }

        $data['defaultpictures'] = $defaults;
        $data['files'] = $files;

        return $data;
    }

    public function beforeUpdate($id,$data)
    {
        $defaults = array();

        $files = array();

        $data['listingPrice'] = new MongoInt32($data['listingPrice']);

        if($data['propertyStatus'] == 'available'){
            $data['publishDate'] = $data['lastUpdate'];
        }

        if($data['propertyStatus'] == 'sold'){
            $data['soldDate'] = $data['lastUpdate'];
        }

        if( isset($data['file_id']) && count($data['file_id'])){

            $data['defaultpic'] = (isset($data['defaultpic']))?$data['defaultpic']:$data['file_id'][0];
            $data['brchead'] = (isset($data['brchead']))?$data['brchead']:$data['file_id'][0];
            $data['brc1'] = (isset($data['brc1']))?$data['brc1']:$data['file_id'][0];
            $data['brc2'] = (isset($data['brc2']))?$data['brc2']:$data['file_id'][0];
            $data['brc3'] = (isset($data['brc3']))?$data['brc3']:$data['file_id'][0];


            for($i = 0 ; $i < count($data['file_id']); $i++ ){


                $files[$data['file_id'][$i]]['thumbnail_url'] = $data['thumbnail_url'][$i];
                $files[$data['file_id'][$i]]['large_url'] = $data['large_url'][$i];
                $files[$data['file_id'][$i]]['medium_url'] = $data['medium_url'][$i];
                $files[$data['file_id'][$i]]['full_url'] = $data['full_url'][$i];

                $files[$data['file_id'][$i]]['delete_type'] = $data['delete_type'][$i];
                $files[$data['file_id'][$i]]['delete_url'] = $data['delete_url'][$i];
                $files[$data['file_id'][$i]]['filename'] = $data['filename'][$i];
                $files[$data['file_id'][$i]]['filesize'] = $data['filesize'][$i];
                $files[$data['file_id'][$i]]['temp_dir'] = $data['temp_dir'][$i];
                $files[$data['file_id'][$i]]['filetype'] = $data['filetype'][$i];
                $files[$data['file_id'][$i]]['fileurl'] = $data['fileurl'][$i];
                $files[$data['file_id'][$i]]['file_id'] = $data['file_id'][$i];
                $files[$data['file_id'][$i]]['caption'] = $data['caption'][$i];

                if($data['defaultpic'] == $data['file_id'][$i]){
                    $defaults['thumbnail_url'] = $data['thumbnail_url'][$i];
                    $defaults['large_url'] = $data['large_url'][$i];
                    $defaults['medium_url'] = $data['medium_url'][$i];
                }

                if($data['brchead'] == $data['file_id'][$i]){
                    $defaults['brchead'] = $data['large_url'][$i];
                }

                if($data['brc1'] == $data['file_id'][$i]){
                    $defaults['brc1'] = $data['large_url'][$i];
                }

                if($data['brc2'] == $data['file_id'][$i]){
                    $defaults['brc2'] = $data['large_url'][$i];
                }

                if($data['brc3'] == $data['file_id'][$i]){
                    $defaults['brc3'] = $data['large_url'][$i];
                }


            }

        }else{

            $data['thumbnail_url'] = array();
            $data['large_url'] = array();
            $data['medium_url'] = array();
            $data['full_url'] = array();
            $data['delete_type'] = array();
            $data['delete_url'] = array();
            $data['filename'] = array();
            $data['filesize'] = array();
            $data['temp_dir'] = array();
            $data['filetype'] = array();
            $data['fileurl'] = array();
            $data['file_id'] = array();
            $data['caption'] = array();

            $data['defaultpic'] = '';
            $data['brchead'] = '';
            $data['brc1'] = '';
            $data['brc2'] = '';
            $data['brc3'] = '';
        }


        $data['defaultpictures'] = $defaults;
        $data['files'] = $files;

        return $data;
    }

    public function beforeUpdateForm($population)
    {
        if( !isset($population['full_url']))
        {
            $population['full_url'] = $population['large_url'];
        }
        if( !isset($population['sourceID']))
        {
            $population['sourceID'] = 'none';
        }

        return $population;
    }

    public function postAdd($data = null)
    {

        $this->validator = array(
            'FMV' =>'required',
            'HOA' =>'required',
            'address' =>'required',
            'bath' =>'required',
            'bed' =>'required',
            'category' =>'required',
            'city' =>'required',
            'description' =>'required',
            'houseSize' =>'required',
            'insurance' =>'required',
            'leaseStartDate' =>'required',
            'leaseTerms' =>'required',
            'listingPrice' =>'required',
            'lotSize' =>'required',
            'monthlyRental' =>'required',
            'number' =>'required',
            'propertyManager' =>'required',
            'propertyStatus' =>'required',
            //'publishStatus' =>'required',
            'section8' =>'required',
            'state' =>'required',
            'tax' =>'required',
            'type' =>'required',
            'yearBuilt' =>'required',
            'zipCode' =>'required'

        );

        return parent::postAdd($data);
    }

    public function postEdit($id,$data = null)
    {
        $this->validator = array(
            'FMV' =>'required',
            'HOA' =>'required',
            'address' =>'required',
            'bath' =>'required',
            'bed' =>'required',
            'category' =>'required',
            'city' =>'required',
            'description' =>'required',
            'houseSize' =>'required',
            'insurance' =>'required',
            'leaseStartDate' =>'required',
            'leaseTerms' =>'required',
            'listingPrice' =>'required',
            'lotSize' =>'required',
            'monthlyRental' =>'required',
            'number' =>'required',
            'propertyManager' =>'required',
            'propertyStatus' =>'required',
            //'publishStatus' =>'required',
            'section8' =>'required',
            'state' =>'required',
            'tax' =>'required',
            'type' =>'required',
            'yearBuilt' =>'required',
            'zipCode' =>'required'
        );

        return parent::postEdit($id,$data);
    }


    public function postDlxl()
    {

        $this->heads = null;

        $this->fields = array(
                array('propertyId',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('sourceID',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('number',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('address',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('city',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('zipCode',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('state',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('bed',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('bath',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('pool',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('garage',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('basement',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('category',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('tags',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('propertyStatus',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('houseSize',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('lotSize',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('listingPrice',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('FMV',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('monthlyRental',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('leaseStartDate',array('kind'=>'date','query'=>'like','pos'=>'both','show'=>true)),
                array('leaseTerms',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('annualRental',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('dpsqft',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('equity',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('HOA',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('OPR',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('ROI',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('ROIstar',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('RentalYield',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('insurance',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('description',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('maintenanceAllowance',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('parcelNumber',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('propManagement',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('propertyManager',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('publishDate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
                array('publishStatus',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('reservedAt',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
                array('reservedBy',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('section8',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('sequence',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('specialConditionRemarks',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('tax',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('type',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('typeOfConstruction',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('vacancyAllowance',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('yearBuilt',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('createdDate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
                array('lastUpdate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true))
        );

        return parent::postDlxl();
    }

    public function getImport(){

        $this->importkey = 'sourceID';

        return parent::getImport();
    }

    public function postUploadimport()
    {
        $this->importkey = 'sourceID';

        return parent::postUploadimport();
    }

    public function beforeImportCommit($data)
    {
        $defaults = array();

        $files = array();

        if($data['propertyId'] == ''){
            $sequence = new Sequence();

            $seq = $sequence->getNewId('property');

            $data['sequence'] = $seq;

            $data['propertyId'] = Config::get('ia.property_id_prefix').$seq;

        }
        // set new sequential ID

        if($data['propertyStatus'] == 'available'){
            $data['publishDate'] = $data['lastUpdate'];
        }

        if($data['propertyStatus'] == 'sold'){
            $data['soldDate'] = $data['lastUpdate'];
        }

        $data['listingPrice'] = new MongoInt32($data['listingPrice']);

        $data['thumbnail_url'] = array();
        $data['large_url'] = array();
        $data['medium_url'] = array();
        $data['full_url'] = array();
        $data['delete_type'] = array();
        $data['delete_url'] = array();
        $data['filename'] = array();
        $data['filesize'] = array();
        $data['temp_dir'] = array();
        $data['filetype'] = array();
        $data['fileurl'] = array();
        $data['file_id'] = array();
        $data['caption'] = array();

        $data['defaultpic'] = '';
        $data['brchead'] = '';
        $data['brc1'] = '';
        $data['brc2'] = '';
        $data['brc3'] = '';


        $data['defaultpictures'] = array();
        $data['files'] = array();

        return $data;
    }


    public function makeActions($data)
    {
        $change = '<span class="propchg act" data-status="'.$data['propertyStatus'].'" rel="'.$data['propertyId'].'" id="'.$data['_id'].'" ><i class="icon-edit"></i> Change Status</span>';
        $delete = '<span class="del" id="'.$data['_id'].'" ><i class="icon-trash"></i> Delete</span>';
        $edit = '<a href="'.URL::to('property/edit/'.$data['_id']).'"><i class="icon-edit"></i> Update</a>';
        $dl = '<a href="'.URL::to('brochure/dl/'.$data['_id']).'" target="new"><i class="icon-download"></i> Download</a>';
        $print = '<a href="'.URL::to('brochure/print/'.$data['_id']).'" target="new"><i class="icon-print"></i> Print</a>';

        $actions = $change.'<br />'.$edit.'<br />'.$dl.'<br />'.$delete;
        return $actions;
    }

    public function splitTag($data){
        $tags = explode(',',$data['docTag']);
        if(is_array($tags) && count($tags) > 0 && $data['docTag'] != ''){
            $ts = array();
            foreach($tags as $t){
                $ts[] = '<span class="tag">'.$t.'</span>';
            }

            return implode('', $ts);
        }else{
            return $data['docTag'];
        }
    }

    public function splitShare($data){
        $tags = explode(',',$data['docShare']);
        if(is_array($tags) && count($tags) > 0 && $data['docShare'] != ''){
            $ts = array();
            foreach($tags as $t){
                $ts[] = '<span class="tag">'.$t.'</span>';
            }

            return implode('', $ts);
        }else{
            return $data['docShare'];
        }
    }

    public function namePic($data)
    {
        $name = HTML::link('property/view/'.$data['_id'],$data['address']);

        $thumbnail_url = '';

        if(isset($data['files']) && count($data['files']) && isset($data['defaultpic']) && $data['defaultpic'] != '' ){
            $glinks = '';

            $gdata = $data['files'][$data['defaultpic']];

            $thumbnail_url = $gdata['thumbnail_url'];
            foreach($data['files'] as $g){
                $g['caption'] = ($g['caption'] == '')?$data['propertyId']:$data['propertyId'].' : '.$g['caption'];
                $g['full_url'] = isset($g['full_url'])?$g['full_url']:$g['fileurl'];
                $glinks .= '<input type="hidden" class="g_'.$data['_id'].'" data-caption="'.$g['caption'].'" value="'.$g['full_url'].'" >';
            }

            $display = HTML::image($thumbnail_url.'?'.time(), $thumbnail_url, array('class'=>'thumbnail img-polaroid','style'=>'cursor:pointer;','id' => $data['_id'])).$glinks;
            return $display;
        }else{
            return $name;
        }
    }

    public function pics($data)
    {
        $name = HTML::link('products/view/'.$data['_id'],$data['productName']);
        if(isset($data['thumbnail_url']) && count($data['thumbnail_url'])){
            $display = HTML::image($data['thumbnail_url'][0].'?'.time(), $data['filename'][0], array('style'=>'min-width:100px;','id' => $data['_id']));
            return $display.'<br /><span class="img-more" id="'.$data['_id'].'">more images</span>';
        }else{
            return $name;
        }
    }

    public function getViewpics($id)
    {

    }


}
