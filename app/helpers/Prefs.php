<?php

class Prefs {

    public static $category;
    public static $section;
    public static $faqcategory;
    public static $productcategory;
    public static $outlet;

    public function __construct()
    {

    }

    public static function getCategory(){
        $c = Category::get();

        self::$category = $c;
        return new self;
    }

    public static function getSection(){
        $s = Section::get();

        self::$section = $s;
        return new self;
    }

    public function sectionToSelection($value, $label, $all = true)
    {
        if($all){
            $ret = array(''=>'All');
        }else{
            $ret = array();
        }

        foreach (self::$section as $s) {
            $ret[$s->{$value}] = $s->{$label};
        }

        return $ret;
    }


    public function catToSelection($value, $label, $all = true)
    {
        if($all){
            $ret = array(''=>'All');
        }else{
            $ret = array();
        }

        foreach (self::$category as $c) {
            $ret[$c->{$value}] = $c->{$label};
        }


        return $ret;
    }

    public function sectionToArray()
    {
        return self::$section;
    }

    public function catToArray()
    {
        return self::$category;
    }


    public static function getFAQCategory(){
        $c = Faqcat::get();

        self::$faqcategory = $c;
        return new self;
    }

    public function FAQcatToSelection($value, $label, $all = true)
    {
        if($all){
            $ret = array(''=>'All');
        }else{
            $ret = array();
        }

        foreach (self::$faqcategory as $c) {
            $ret[$c->{$value}] = $c->{$label};
        }


        return $ret;
    }

    public function FAQcatToArray()
    {
        return self::$faqcategory;
    }


    public static function getProductCategory(){
        $c = Productcategory::get();

        self::$productcategory = $c;
        return new self;
    }

    public function ProductCatToSelection($value, $label, $all = true)
    {
        if($all){
            $ret = array(''=>'Select Category');
        }else{
            $ret = array();
        }

        foreach (self::$productcategory as $c) {
            $ret[$c->{$value}] = $c->{$label};
        }


        return $ret;
    }

    public function ProductCatToArray()
    {
        return self::$productcategory;
    }


    public static function getOutlet(){
        $c = Outlet::get();

        self::$outlet = $c;
        return new self;
    }

    public function OutletToSelection($value, $label, $all = true)
    {
        if($all){
            $ret = array(''=>'Select Outlet');
        }else{
            $ret = array();
        }

        foreach (self::$outlet as $c) {
            $ret[$c->{$value}] = $c->{$label};
        }


        return $ret;
    }

    public function OutletToArray()
    {
        return self::$outlet;
    }

    public static function GetBatchId($SKU, $year, $month){

        $seq = DB::collection('batchnumbers')->raw();

        $new_id = $seq->findAndModify(
                array(
                    'SKU'=>$SKU,
                    'year'=>$year,
                    'month'=>$month
                    ),
                array('$inc'=>array('sequence'=>1)),
                null,
                array(
                    'new' => true,
                    'upsert'=>true
                )
            );


        $batchid = $year.$month.str_pad($new_id['sequence'], 4, '0', STR_PAD_LEFT);

        return $batchid;

    }

    public static function ExtractProductCategory($selection = true)
    {
        $category = Product::distinct('category')->get()->toArray();
        if($selection){
            $cats = array(''=>'All');
        }else{
            $cats = array();
        }

        //print_r($category);
        foreach($category as $cat){
            $cats[$cat[0]] = $cat[0];
        }

        return $cats;
    }


    public static function themeAssetsUrl()
    {
        return URL::to('/').'/'.Theme::getCurrentTheme();
    }

    public static function themeAssetsPath()
    {
        return 'themes/'.Theme::getCurrentTheme().'/assets/';
    }

    public static function getActiveTheme()
    {
        return Config::get('kickstart.default_theme');
    }

}
