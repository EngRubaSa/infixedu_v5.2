$("#livesearch").hide();
function showResult(str) {

    var url = $('#url').val();
    
    if (str.length == 0) {
         document.getElementById("livesearch").innerHTML="";
         $("#livesearch").hide();
    return;
    }


    $.ajax({
            method:'POST',
            url: url + '/' + 'search',
            data:{search:str},
            success:function(data){
                // console.log(data);
                $("#livesearch").show();
                if (data.length != 0) {
                 document.getElementById("livesearch").innerHTML="";
                 data.forEach(value => {
                     var str = value.name;
                    $("#livesearch").append(`<a href="${url+'/'+value.route}">${str}</a>`);
                 }); 
                }
                else{
                    document.getElementById("livesearch").innerHTML="";
                    $("#livesearch").append("<a id='lol'> Not Found </a>");
                }
            },
            error: function (data) {
                console.log('Error:', data);
            }

        });
    
}
$(document).on("click", function(e) {
    if (!$(e.target).closest('#serching').length)  {
        $("#livesearch").hide();
    }
});
$(document).ready(function () {
    $('#languageChange').on('change', function () {
        
        console.log("lang clicked");
    var str = $('#languageChange').val();
    var url = $('#url').val();
    var formData = {
        id: $(this).val()
    };
    console.log(formData);
    
    // get section for student
    $.ajax({
        type: "POST",
        data: formData,
        dataType: 'json',
        url: url + '/' + 'language-change',
        success: function (data) {
            url= url + '/' + 'locale'+ '/' + data[0].language_universal;
            window.location.href = url;
        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
});
});