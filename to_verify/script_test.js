window.onload = function(){
    app.start();
}

function myPage(){    
    var me = this;
    this.start = function(){

        $('#myForm').submit(function(e){

            e.preventDefault();

            var myWidth = $('#width').val();
            var myHeight = $('#height').val();
            var myWallpaperId = $('#wallpaperId').val();

            var url = "http://localhost/TenAsMarreDeTonWallpaper/api/wallpaper/resize" + '/' + myWallpaperId  + '/' +  myWidth + '/' + myHeight;

            console.log(url);

            var myDatas = { 
                'width' : myWidth,
                'height' : myHeight,
                'wallpaperId' : myWallpaperId
            };

            console.log(myDatas);
            
            $.ajax({
                //data: myDatas,
                contentType: 'application/x-www-form-urlencoded',
                dataType: 'json',
                type: 'POST',
                url: url,
                success: function(datas) {
                    console.log(datas);
                }
            });
        });
    }

}

var app = new myPage();