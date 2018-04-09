(function() {
  //alert('Succesfully reloaded');
  var overlay = document.getElementById('photo');

  document.getElementById('switch').addEventListener('click', 
  function() { 
    var xhr = new XMLHttpRequest();
    xhr.open('POST', './inc/overlay.php', true);

    xhr.onload = function() {
      //alert('Succesfully changed');
    };
    
      setTimeout(function()
      {
          overlay.setAttribute('src', './images/overlay.png');
          window.location.href = './camagru.php';
      }, 200);
      //alert('Succesfully reloaded');
  }); 

})();
