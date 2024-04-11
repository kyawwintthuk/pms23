

//AJAX functions by KWT 15-02-2023
function ajax(url, method, parameters = null) {

    return new Promise(function (resolve, reject) {
        $.ajax({
            url: url,
            method: method,
            data: parameters,
            success: function (data) {
                resolve(data);

            },
            error: function(xhr, status, error){
                reject(error);
            }
        });
    });
}