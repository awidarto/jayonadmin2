var thumb = '<table class="table" id="par_'+ file.file_id +'" >' +
    '    <tr>' +
    '        <td rowspan="6"><img style="width:125px;" src="' + file.thumbnail_url + '"></td>' +
    '        <td><span class="img-title">' + file.filename + '</span></td>' +
    '        <td><span class="file_del icon-trash" id="' + file.file_id +'"></td>' +
    '    </tr>' +
    '    <tr>' +
    '        <td colspan="2">' +
    '            <label for="defaultpic"><input type="radio" name="defaultpic" ' + isdefault + ' value="' + file.file_id + '" > Default</label>' +
    '        </td>' +
    '    </tr>' +
    '    <tr>' +
    '        <td colspan="2">' +
    '            <input type="radio" name="brchead" ' + isbrchead + ' value="' + file.file_id + '" > Head' +
    '        </td>' +
    '    </tr>' +
    '    <tr>' +
    '        <td colspan="2">' +
    '            <input type="radio" name="brc1" ' + isbrc1 + ' value="' + file.file_id + '" > Pic 1' +
    '        </td>' +
    '    </tr>' +
    '    <tr>' +
    '        <td colspan="2">' +
    '            <input type="radio" name="brc2" ' + isbrc2 + ' value="' + file.file_id + '" > Pic 2' +
    '        </td>' +
    '    </tr>' +
    '    <tr>' +
    '        <td colspan="2">' +
    '            <input type="radio" name="brc3" ' + isbrc3 + ' value="' + file.file_id + '" > Pic 3' +
    '        </td>' +
    '    </tr>' +
    '    <tr>' +
    '        <td style="text-align:right;">Caption</td>' +
    '        <td colspan="2">' +
    '            <input type="text" name="caption[]" />' +
    '        </td>' +
    '    </tr>' +
'</table>';
