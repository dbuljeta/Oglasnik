$(document).ready(function(){
    var myUrl = location.protocol + "//" + location.hostname + "/";

    $('button#obrisi').click(function(){
       var id = $(this).attr('data-param');
       console.log(myUrl + 'obrisi.php');
       var data = {'id': id};
        $.ajax({
            url: myUrl + 'obrisi.php',
            dataType: 'json',
            data: JSON.stringify(data),
            type: 'post'
        }).done( function(data){
            console.log("success");
            var responseStatus = data.status;
            window.location.replace(myUrl + 'index.php');
            console.log('stauts: ' + data.data);
            console.log('stauts: ' + data.id);
            console.log('stauts: ' + data);
        }).fail( function(data){
            console.log('fail');
            console.log(data);
        });
    });

    $('#ocjeni').click( function(){
        var proizvod = $(this).attr('data-param');
        var ocjenjivac = $(this).attr('data-param1');
        var korisnik = $(this).attr('data-param2');
        var element = $(this);

        var data = {'proizvod': proizvod, 'ocjenjivac': ocjenjivac, 'korisnik':korisnik};
        console.log('kor ' + korisnik);
        console.log('ocj ' + ocjenjivac);
        if(element.hasClass('fa fa-star-o fa-4x')) {
            $.ajax({
                url: myUrl + 'ocjeni.php',
                dataType: 'json',
                data: JSON.stringify(data),
                type: 'post'
            }).done( function(data){
                console.log("success");
                var responseStatus = data.status;
                console.log('stauts: ' + data.data);
                console.log('stauts: ' + data.id);
                console.log('stauts: ' + data);
                element.removeClass().addClass('fa fa-star fa-4x');
                element.attr("id", "neocjeni");

            }).fail( function(data){
                console.log('fail');
                console.log(data);
            });
        } else {
            $.ajax({
                url: myUrl + 'neocjeni.php',
                dataType: 'json',
                data: JSON.stringify(data),
                type: 'post'
            }).done( function(data){
                console.log("success");
                var responseStatus = data.status;
                console.log('stauts: ' + data.data);
                console.log('stauts: ' + data.id);
                element.removeClass().addClass('fa fa-star-o fa-4x');
                element.attr("id", "ocjeni");
            }).fail( function(data){
                console.log('fail');
                console.log(data);
            });
        }

    });

});