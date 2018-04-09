(function() {
    var video = document.getElementById('video');
    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    var photo = document.getElementById('photo');

    context.drawImage(video, 0, 0, 400, 300);
    document.getElementById('capture').addEventListener('click', 
    function() {
        setTimeout(function()
        {
            photo.setAttribute('src', './images/output.png');
            window.location.href = './camagru.php';
        }, 200);
        //alert('Succesfully reloaded');
    });

})();