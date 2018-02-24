function dbclick_edit(ob, callback) {
    var ob = $(ob);
    var oldVal = ob.text();
    var input = "<input type='text' id='tmpId' value='" + oldVal + "' >";
    ob.text('');
    ob.append(input);
    $('#tmpId').focus();
    $('#tmpId').blur(function () {
        var newVal = $(this).val();
        if (newVal != '' && newVal != oldVal) {
            callback(ob, newVal);
        } else {
            ob.text(oldVal);
        }
    });
}

