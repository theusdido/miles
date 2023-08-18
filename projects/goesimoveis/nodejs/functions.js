exports.is_empty = function is_empty(value){
    if (value == '' || value == undefined || value == null){
        return true;
    }else{
        return false;
    }
}