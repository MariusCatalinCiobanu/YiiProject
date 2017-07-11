$(document).ready(function () {
    var id = GetParameterValues('id');
    console.log('Id paramater = ' + id);
    var data = {id: id};


    $.ajax({
        url: '/trip/async/trip/get-trip-pictures',
        data: data,
        type: 'GET',
        dataType: 'JSON',
        success: function (result) {
            if (!result.success) {
                new PNotify({title: "Failure", text: result.message, type: 'error'});
                return;
            }
            var container = $('#picturesForUpdate');
            $.each(result.image, function (index, value) {
                var wrapper = $('<div id="wrapper' + value.id + '" class="wrapper"></div>');
                var img = $('<img  src="' + value.imagePath + '" class="imagesForUpdate">');
                var deleteButton = $('<span class="close" onclick="deletePictureModal(\'' + value.id + '\')">&times;</span>')
                wrapper.append(img);
                wrapper.append(deleteButton);
                container.append(wrapper);
            });

        },
        error: function (result) {
            new PNotify({title: "Failure", text: 'Internal error, could not load the pictures,' +
                        ' please try again later', type: 'error'});


            console.log(JSON.stringify(result));
        }
    });

});
//get the value of the id query string
function GetParameterValues(param) {
    var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < url.length; i++) {
        var urlparam = url[i].split('=');
        if (urlparam[0] == param) {
            return urlparam[1];
        }
    }
}
function deletePictureModal(id) {
    $('#confirmDeletePicture').data('id', id).modal();

}
function deletePictureOK() {
    $('#confirmDeletePicture').modal('hide');
    var id = $('#confirmDeletePicture').data('id');
    console.log(id);
    var data = {id: id};
    $.ajax({
        url: '/trip/async/trip/delete-picture',
        data: data,
        type: 'GET',
        dataType: 'JSON',
        error: function (result) {
            new PNotify({title: "Failure", text: result.message, type: 'error'});
            console.log(JSON.stringify(result));
        },
        success: function (result) {

            //check if the request finished correctly
            if (result.success) {

                //in case of success remove the picture and notify the user
                $("#wrapper" + id).fadeOut(300, function () {
                    $(this).remove()
                });
                new PNotify({title: "Success", text: result.message, type: 'success'});

                //in case of failure only notify the user
            } else {

                new PNotify({title: "Failure", text: 'Internal error, could not delete the picture,' +
                            ' please try again later', type: 'error'});
            }

            console.log(JSON.stringify(result));
        }
    });
}
