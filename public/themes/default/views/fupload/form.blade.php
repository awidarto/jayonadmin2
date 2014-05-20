<div class="control-group">
    <label class="control-label" for="userfile">{{ $label }}</label>
    <div class="controls">
        <span class="btn btn-success fileinput-button">
            <i class="icon-plus icon-white"></i>
            <span>{{ $title }}</span>
            <!-- The file input field used as target for the file upload widget -->
            <input id="{{ $element_id }}" type="file" name="files[]" {{ ($multi)?'multiple':''}}  >
        </span>
        <br />
        <br />
        <div id="{{ $element_id }}_progress" class="progress progress-success progress-striped">
            <div class="bar"></div>
        </div>
        <br />
        <span id="loading-pictures" style="display:none;" ><img src="{{URL::to('/') }}/images/loading.gif" />loading existing pictures...</span>



        <div id="{{ $element_id }}_files" class="files">
            <ul style="margin-left:0px">
                <?php

                    $allin = Input::old();
                    $showold = false;

                    if( count($allin) > 0){
                        $showold = true;
                    }

                    //print_r($formdata);

                    //exit();

                    if( !is_null($formdata) && isset($formdata['files']) && $showold == false ){

                        /* external detail template */

                        $thumb = View::make('fupload.detail')->render();

                        // display previously saved data
                        //for($t = 0; $t < count($filename);$t++){

                        foreach ($formdata['files'] as $k => $v) {

                            if(isset($formdata['defaultpic'])){
                                if($formdata['defaultpic'] == $k){
                                    $isdef = 'checked="checked"';
                                }else{
                                    $isdef = ' ';
                                }

                            }else{
                                if($t == 0){
                                    $isdef = 'checked="checked"';
                                }else{
                                    $isdef = ' ';
                                }
                            }

                            if(isset($formdata['brchead']) && $formdata['brchead'] == $k){
                                $headdef = 'checked="checked"';
                            }else{
                                $headdef = ' ';
                            }
                            if(isset($formdata['brc1']) && $formdata['brc1'] == $k){
                                $isdef1 = 'checked="checked"';
                            }else{
                                $isdef1 = ' ';
                            }
                            if(isset($formdata['brc2']) && $formdata['brc2'] == $k){
                                $isdef2 = 'checked="checked"';
                            }else{
                                $isdef2 = ' ';
                            }
                            if(isset($formdata['brc3']) && $formdata['brc3'] == $k){
                                $isdef3 = 'checked="checked"';
                            }else{
                                $isdef3 = ' ';
                            }


                            printf($thumb,
                                $v['file_id'],
                                $v['thumbnail_url'],
                                $v['filename'],
                                $v['file_id'],
                                $v['file_id'],
                                $isdef,
                                $v['file_id'], $headdef,
                                $v['file_id'], $isdef1,
                                $v['file_id'], $isdef2,
                                $v['file_id'], $isdef3,
                                $v['caption']
                                );

                        }

                    }

                    // display re-populated data from error form

                    if($showold && isset( $allin['thumbnail_url'])){

                        $filename = $allin['filename'];
                        $thumbnail_url = $allin['thumbnail_url'];
                        $file_id = $allin['file_id'];
                        /*
                        $thumb = '<li><img style="width:125px;" src="%s"><span class="file_del icon-trash" id="%s"></span>';
                        $thumb .= '<span class="img-title">%s</span>';
                        $thumb .= '<label for="defaultpic"><input type="radio" name="defaultpic" value="%s" %s > Default</label>';

                        $thumb .= 'Gallery<br />';
                        $thumb .= '<input type="radio" name="brchead" value="%s" %s > Head &nbsp;';
                        $thumb .= '<input type="radio" name="brc1" value="%s" %s > Pic 1 &nbsp;';
                        $thumb .= '<input type="radio" name="brc2" value="%s" %s > Pic 2 &nbsp;';
                        $thumb .= '<input type="radio" name="brc3" value="%s" %s > Pic 3 &nbsp;';

                        $thumb .= '<label for="caption">Caption</label><input type="text" name="caption[]" value="%s" />';
                        $thumb .= '</li>';
                        */

                        $thumb = View::make('fupload.detail')->render();


                        for($t = 0; $t < count($filename);$t++){
                            if(isset($allin['defaultpic'])){
                                if($allin['defaultpic'] == $allin['file_id'][$t]){
                                    $isdef = 'checked="checked"';
                                }else{
                                    $isdef = ' ';
                                }

                            }else{
                                if($t == 0){
                                    $isdef = 'checked="checked"';
                                }else{
                                    $isdef = ' ';
                                }
                            }

                            if( isset($allin['brchead']) && $allin['brchead'] == $allin['file_id'][$t]){
                                $headdef = 'checked="checked"';
                            }else{
                                if($t == 0){
                                    $headdef = 'checked="checked"';
                                }else{
                                    $headdef = ' ';
                                }
                            }
                            if( isset($allin['brc1']) && $allin['brc1'] == $allin['file_id'][$t]){
                                $isdef1 = 'checked="checked"';
                            }else{
                                if($t == 0){
                                    $isdef1 = 'checked="checked"';
                                }else{
                                    $isdef1 = ' ';
                                }
                            }
                            if( isset($allin['brc2']) && $allin['brc2'] == $allin['file_id'][$t]){
                                $isdef2 = 'checked="checked"';
                            }else{
                                if($t == 0){
                                    $isdef2 = 'checked="checked"';
                                }else{
                                    $isdef2 = ' ';
                                }
                            }
                            if( isset($allin['brc3']) && $allin['brc3'] == $allin['file_id'][$t]){
                                $isdef3 = 'checked="checked"';
                            }else{
                                if($t == 0){
                                    $isdef3 = 'checked="checked"';
                                }else{
                                    $isdef3 = ' ';
                                }
                            }

                            printf($thumb,
                                $file_id[$t],
                                $thumbnail_url[$t],
                                $filename[$t],
                                $file_id[$t],
                                $file_id[$t],
                                $isdef,
                                $file_id[$t], $headdef,
                                $file_id[$t], $isdef1,
                                $file_id[$t], $isdef2,
                                $file_id[$t], $isdef3,
                                $allin['caption'][$t]
                            );

                        }

                    }
                ?>
            </ul>
        </div>
        <div id="{{ $element_id }}_uploadedform">
            <ul style="list-style:none">
            <?php

                if(isset( $formdata['filename'] )  && $showold == false ){

                    $count = 0;
                    $upcount = count($formdata['filename']);

                    $upl = '';
                    for($u = 0; $u < $upcount; $u++){
                        $upl .= '<li id="fdel_'.$formdata['file_id'][$u].'">';
                        $upl .= '<input type="hidden" name="delete_type[]" value="' . $formdata['delete_type'][$u] . '">';
                        $upl .= '<input type="hidden" name="delete_url[]" value="' . $formdata['delete_url'][$u] . '">';
                        $upl .= '<input type="hidden" name="filename[]" value="' . $formdata['filename'][$u]  . '">';
                        $upl .= '<input type="hidden" name="filesize[]" value="' . $formdata['filesize'][$u]  . '">';
                        $upl .= '<input type="hidden" name="temp_dir[]" value="' . $formdata['temp_dir'][$u]  . '">';
                        $upl .= '<input type="hidden" name="thumbnail_url[]" value="' . $formdata['thumbnail_url'][$u] . '">';
                        $upl .= '<input type="hidden" name="large_url[]" value="' . $formdata['large_url'][$u] . '">';
                        $upl .= '<input type="hidden" name="medium_url[]" value="' . $formdata['medium_url'][$u] . '">';
                        $upl .= '<input type="hidden" name="full_url[]" value="' . $formdata['full_url'][$u] . '">';
                        $upl .= '<input type="hidden" name="filetype[]" value="' . $formdata['filetype'][$u] . '">';
                        $upl .= '<input type="hidden" name="fileurl[]" value="' . $formdata['fileurl'][$u] . '">';
                        $upl .= '<input type="hidden" name="file_id[]" value="' . $formdata['file_id'][$u] . '">';
                        $upl .= '</li>';
                    }

                    print $upl;
                }

            ?>
            <?php

                if($showold && isset( $allin['filename'] )){

                    $count = 0;
                    $upcount = count($allin['filename']);

                    $upl = '';
                    for($u = 0; $u < $upcount; $u++){
                        $upl .= '<li id="fdel_'.$allin['file_id'][$u].'">';
                        $upl .= '<input type="hidden" name="delete_type[]" value="' . $allin['delete_type'][$u] . '">';
                        $upl .= '<input type="hidden" name="delete_url[]" value="' . $allin['delete_url'][$u] . '">';
                        $upl .= '<input type="hidden" name="filename[]" value="' . $allin['filename'][$u]  . '">';
                        $upl .= '<input type="hidden" name="filesize[]" value="' . $allin['filesize'][$u]  . '">';
                        $upl .= '<input type="hidden" name="temp_dir[]" value="' . $allin['temp_dir'][$u]  . '">';
                        $upl .= '<input type="hidden" name="thumbnail_url[]" value="' . $allin['thumbnail_url'][$u] . '">';
                        $upl .= '<input type="hidden" name="large_url[]" value="' . $allin['large_url'][$u] . '">';
                        $upl .= '<input type="hidden" name="medium_url[]" value="' . $allin['medium_url'][$u] . '">';
                        $upl .= '<input type="hidden" name="full_url[]" value="' . $allin['full_url'][$u] . '">';
                        $upl .= '<input type="hidden" name="filetype[]" value="' . $allin['filetype'][$u] . '">';
                        $upl .= '<input type="hidden" name="fileurl[]" value="' . $allin['fileurl'][$u] . '">';
                        $upl .= '<input type="hidden" name="file_id[]" value="' . $allin['file_id'][$u] . '">';
                        $upl .= '</li>';
                    }

                    print $upl;
                }

            ?>
            </ul>
        </div>
    </div>
</div>

<style type="text/css">
    .file_del{
        cursor: pointer;
    }
</style>

<script type="text/javascript">

$(document).ready(function(){

    var url = '{{ URL::to($url) }}';

    $('#{{ $element_id }}_files').on('click',function(e){

        if ($(e.target).is('.file_del')) {
            var _id = e.target.id;
            var answer = confirm("Are you sure you want to delete this item ?");

            console.log($(e.target).parent());

            if (answer == true){
                $('#par_' + _id).remove();
                //$(e.target).parent().remove();
                $('#fdel_'+e.target.id).remove();
                /*
                $.post('',{'id':_id}, function(data) {
                    if(data.status == 'OK'){



                        alert("Item id : " + _id + " deleted");
                    }
                },'json');
                */
            }else{
                alert("Deletion cancelled");
            }
        }
    });

    $('#{{ $element_id }}').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            $('#{{ $element_id }}_progress .bar').css(
                'width',
                '0%'
            );

            $.each(data.result.files, function (index, file) {

                {{ View::make('fupload.jsdetail') }}

                $(thumb).prependTo('#{{ $element_id }}_files ul');

                var upl = '<li id="fdel_' + file.file_id +'" ><input type="hidden" name="delete_type[]" value="' + file.delete_type + '">';
                upl += '<input type="hidden" name="delete_url[]" value="' + file.delete_url + '">';
                upl += '<input type="hidden" name="filename[]" value="' + file.name  + '">';
                upl += '<input type="hidden" name="filesize[]" value="' + file.size  + '">';
                upl += '<input type="hidden" name="temp_dir[]" value="' + file.temp_dir  + '">';
                upl += '<input type="hidden" name="thumbnail_url[]" value="' + file.thumbnail_url + '">';
                upl += '<input type="hidden" name="large_url[]" value="' + file.large_url + '">';
                upl += '<input type="hidden" name="medium_url[]" value="' + file.medium_url + '">';
                upl += '<input type="hidden" name="full_url[]" value="' + file.full_url + '">';
                upl += '<input type="hidden" name="filetype[]" value="' + file.type + '">';
                upl += '<input type="hidden" name="fileurl[]" value="' + file.url + '">';
                upl += '<input type="hidden" name="file_id[]" value="' + file.file_id + '"></li>';

                $(upl).prependTo('#{{ $element_id }}_uploadedform ul');

            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#{{ $element_id }}_progress .bar').css(
                'width',
                progress + '%'
            );
        }
    })
    .prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');


});


</script>

