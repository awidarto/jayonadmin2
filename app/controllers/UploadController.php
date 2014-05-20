<?php

class UploadController extends Controller {

    public function __construct()
    {

    }

    public function postIndex()
    {
        $files = Input::file('files');

        $file = $files[0];

        //print_r($file);

        //exit();

        $large_wm = public_path().'/wm/wm_lrg.png';
        $med_wm = public_path().'/wm/wm_med.png';
        $sm_wm = public_path().'/wm/wm_sm.png';

        $rstring = str_random(15);

        $destinationPath = realpath('storage/media').'/'.$rstring;

        $filename = $file->getClientOriginalName();
        $filemime = $file->getMimeType();
        $filesize = $file->getSize();
        $extension =$file->getClientOriginalExtension(); //if you need extension of the file

        $filename = str_replace(Config::get('kickstart.invalidchars'), '-', $filename);

        $uploadSuccess = $file->move($destinationPath, $filename);

        $thumbnail = Image::make($destinationPath.'/'.$filename)
            ->resize(120,null,true)
            ->grab(120,120)
            //->insert($sm_wm,0,0, 'bottom-right')
            ->save($destinationPath.'/th_'.$filename);

        $medium = Image::make($destinationPath.'/'.$filename)
            ->grab(270,200)
            //->insert($med_wm,0,0, 'bottom-right')
            ->save($destinationPath.'/med_'.$filename);

        $large = Image::make($destinationPath.'/'.$filename)
            ->grab(870,420)
            //->insert($large_wm,15,15, 'bottom-right')
            ->save($destinationPath.'/lrg_'.$filename);

        $full = Image::make($destinationPath.'/'.$filename)
            //->insert($large_wm,15,15, 'bottom-right')
            ->save($destinationPath.'/full_'.$filename);

        $fileitems = array();

        if($uploadSuccess){
            $fileitems[] = array(
                    'url'=> URL::to('storage/media/'.$rstring.'/'.$filename),
                    'thumbnail_url'=> URL::to('storage/media/'.$rstring.'/th_'.$filename),
                    'large_url'=> URL::to('storage/media/'.$rstring.'/lrg_'.$filename),
                    'medium_url'=> URL::to('storage/media/'.$rstring.'/med_'.$filename),
                    'full_url'=> URL::to('storage/media/'.$rstring.'/full_'.$filename),
                    'temp_dir'=> $destinationPath,
                    'file_id'=> $rstring,
                    'name'=> $filename,
                    'type'=> $filemime,
                    'size'=> $filesize,
                    'delete_url'=> URL::to('storage/media/'.$rstring.'/'.$filename),
                    'delete_type'=> 'DELETE'
                );

        }

        return Response::JSON(array('files'=>$fileitems) );
    }

    public function postProduct()
    {
        $files = Input::file('files');

        $file = $files[0];

        //print_r($file);

        //exit();

        $large_wm = public_path().'/wm/wm_lrg.png';
        $med_wm = public_path().'/wm/wm_med.png';
        $sm_wm = public_path().'/wm/wm_sm.png';

        $rstring = str_random(15);

        $destinationPath = realpath('storage/media/product').'/'.$rstring;

        $filename = $file->getClientOriginalName();
        $filemime = $file->getMimeType();
        $filesize = $file->getSize();
        $extension =$file->getClientOriginalExtension(); //if you need extension of the file

        $filename = str_replace(Config::get('kickstart.invalidchars'), '-', $filename);

        $uploadSuccess = $file->move($destinationPath, $filename);

        $thumbnail = Image::make($destinationPath.'/'.$filename)
            ->grab(100,74)
            //->insert($sm_wm,0,0, 'bottom-right')
            ->save($destinationPath.'/th_'.$filename);

        $medium = Image::make($destinationPath.'/'.$filename)
            ->grab(270,200)
            //->insert($med_wm,0,0, 'bottom-right')
            ->save($destinationPath.'/med_'.$filename);

        $large = Image::make($destinationPath.'/'.$filename)
            ->grab(870,420)
            //->insert($large_wm,15,15, 'bottom-right')
            ->save($destinationPath.'/lrg_'.$filename);

        $full = Image::make($destinationPath.'/'.$filename)
            //->insert($large_wm,15,15, 'bottom-right')
            ->save($destinationPath.'/full_'.$filename);

        $fileitems = array();

        if($uploadSuccess){
            $fileitems[] = array(
                    'url'=> URL::to('storage/media/product/'.$rstring.'/'.$filename),
                    'thumbnail_url'=> URL::to('storage/media/product/'.$rstring.'/th_'.$filename),
                    'large_url'=> URL::to('storage/media/product/'.$rstring.'/lrg_'.$filename),
                    'medium_url'=> URL::to('storage/media/product/'.$rstring.'/med_'.$filename),
                    'full_url'=> URL::to('storage/media/product/'.$rstring.'/full_'.$filename),
                    'temp_dir'=> $destinationPath,
                    'file_id'=> $rstring,
                    'name'=> $filename,
                    'type'=> $filemime,
                    'size'=> $filesize,
                    'delete_url'=> URL::to('storage/media/'.$rstring.'/'.$filename),
                    'delete_type'=> 'DELETE'
                );

        }

        return Response::JSON(array('files'=>$fileitems) );
    }

    public function postMusic()
    {
        $files = Input::file('files');

        $file = $files[0];

        //print_r($file);

        //exit();

        $rstring = str_random(15);

        $destinationPath = realpath('storage/media').'/'.$rstring;

        $filename = $file->getClientOriginalName();
        $filemime = $file->getMimeType();
        $filesize = $file->getSize();
        $extension =$file->getClientOriginalExtension(); //if you need extension of the file

        $filename = str_replace(Config::get('kickstart.invalidchars'), '-', $filename);

        $uploadSuccess = $file->move($destinationPath, $filename);

        /*
        $thumbnail = Image::make($destinationPath.'/'.$filename)
            ->grab(100,100)
            ->save($destinationPath.'/th_'.$filename);
        */

        $fileitems = array();

        if($uploadSuccess){
            $fileitems[] = array(
                    'url'=> URL::to('storage/media/'.$rstring.'/'.$filename),
                    'thumbnail_url'=> URL::to('storage/media/th_music.jpg'),
                    'temp_dir'=> $destinationPath,
                    'name'=> $filename,
                    'type'=> $filemime,
                    'size'=> $filesize,
                    'delete_url'=> URL::to('storage/media/'.$rstring.'/'.$filename),
                    'delete_type'=> 'DELETE'
                );

        }

        return Response::JSON(array('files'=>$fileitems) );
    }


    public function postAdd()
    {
        $files = Input::file('files');

        $file = $files[0];

        //print_r($file);

        //exit();

        $rstring = str_random(8);

        $destinationPath = realpath('storage/temp').'/'.$rstring;

        $filename = $file->getClientOriginalName();
        $filemime = $file->getMimeType();
        $filesize = $file->getSize();
        $extension =$file->getClientOriginalExtension(); //if you need extension of the file

        $filename = str_replace(Config::get('kickstart.invalidchars'), '-', $filename);

        $uploadSuccess = $file->move($destinationPath, $filename);

        $thumbnail = Image::make($destinationPath.'/'.$filename)
            ->grab(320,240)
            ->save($destinationPath.'/th_'.$filename);

        $fileitems = array();

        if($uploadSuccess){
            $fileitems[] = array(
                    'url'=> URL::to('storage/temp/'.$rstring.'/'.$filename),
                    'thumbnail_url'=> URL::to('storage/temp/'.$rstring.'/th_'.$filename),
                    'temp_dir'=> $destinationPath,
                    'name'=> $filename,
                    'type'=> $filemime,
                    'size'=> $filesize,
                    'delete_url'=> 'http://url.to/delete /file/',
                    'delete_type'=> 'DELETE'
                );

        }

        return Response::JSON(array('files'=>$fileitems) );
    }


    public function postEdit()
    {
        $files = Input::file('files');

        $file = $files[0];

        //print_r($file);

        //exit();

        $rstring = str_random(8);

        $destinationPath = realpath('storage/temp').'/'.$rstring;

        $filename = $file->getClientOriginalName();
        $filemime = $file->getMimeType();
        $filesize = $file->getSize();
        $extension =$file->getClientOriginalExtension(); //if you need extension of the file

        $filename = str_replace(Config::get('kickstart.invalidchars'), '-', $filename);

        $uploadSuccess = $file->move($destinationPath, $filename);

        $thumbnail = Image::make($destinationPath.'/'.$filename)
            ->grab(320,240)
            ->save($destinationPath.'/th_'.$filename);

        $fileitems = array();

        if($uploadSuccess){
            $fileitems[] = array(
                    'url'=> URL::to('storage/temp/'.$rstring.'/'.$filename),
                    'thumbnail_url'=> URL::to('storage/temp/'.$rstring.'/th_'.$filename),
                    'temp_dir'=> $destinationPath,
                    'name'=> $filename,
                    'type'=> $filemime,
                    'size'=> $filesize,
                    'delete_url'=> 'http://url.to/delete /file/',
                    'delete_type'=> 'DELETE'
                );

        }

        return Response::JSON(array('files'=>$fileitems) );
    }

    public function postDelete($file_id)
    {


    }

    public function postUp(){

        $file = Input::file('file');

        $destinationPath = Config::get('kickstart.storage').'/uploads/'.str_random(8);
        $filename = $file->getClientOriginalName();
        //$extension =$file->getClientOriginalExtension();
        $upload_success = Input::file('file')->move($destinationPath, $filename);

        if( $upload_success ) {
           return Response::json('success', 200);
        } else {
           return Response::json('error', 400);
        }

    }


}
