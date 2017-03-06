window.onload = function(){
    app.start();
}

function myPage(){    
    var me = this;
    this.start = function(){

        var url = "http://localhost/TenAsMarreDeTonWallpaper/api/membre/logout";

        $('#myForm').submit(function(e){

            e.preventDefault();

            var myPseudo = $('#pseudo').val();
            var myPass = $('#password').val();
            /*var myMailAdress = $('#mailAdress').val();*/

            var myDatas = { 
                'pseudo' : myPseudo,
                'password' : myPass/*,
                'mailAdress' : myMailAdress*/
            };

            console.log(myDatas);
            
            $.ajax({
                //data: myDatas,
                contentType: 'application/x-www-form-urlencoded',
                dataType: 'json',
                type: 'GET',
                url: url,
                success: function(datas) {
                    console.log(datas);
                }
            });
        });
    }

}

var app = new myPage();