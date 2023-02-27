
function success(type, message)
{

    if(type == 'toster') {

        toastr.options = {
            "closeButton" : true,
            "progressBar" : true
        }

        toastr.success(message)
        
    } else {

    }
    
}
function warning(type, message)
{

    if(type == 'toster') {

        toastr.options = {
            "closeButton" : true,
            "progressBar" : true
        }

        toastr.warning(message)

    } else {

    }
    
}