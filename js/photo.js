(function() {
    var video = document.getElementById('video'),
        canvas = document.getElementById('canvas'),
        context = canvas.getContext('2d'),
        photo = document.getElementById('photo'),
        vendorUrl = window.URL || window.webkitURL;

    navigator.getMedia = navigator.getUserMedia ||
                         navigatot.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
                         navigatior.msGetUserMedia;
                        
    navigator.getMedia({
        video: true,
        audio: false,   
    }, function(stream) {
        video.src = vendorUrl.createObjectURL(stream);
        video.play();
    }, function(error)  {

    });

    document.getElementById('capture').addEventListener('click', 
    function() {
        context.drawImage(video, 0, 0, 400, 300);
        var image = canvas.toDataURL('image/png');
        photo.setAttribute('src', image);
        document.getElementById('hidden_data').value = image;
        var fd = new FormData(document.forms["form1"]);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', './inc/saveimage.php', true);

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                var percentComplete = (e.loaded / e.total) * 100;
                console.log(percentComplete + '% uploaded');
                //alert('Succesfully uploaded');
            }
        };

        xhr.onload = function() {

        };
        xhr.send(fd);

        context.drawImage(video, 0, 0, 400, 300);
        
        photo.setAttribute('src', './images/output.png');

    });

})();