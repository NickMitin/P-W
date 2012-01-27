$(document).ready(function(){
        
  var referer = document.referrer;
  if (referer != '')
  {
    var img = document.createElement('img');
    img.src = 'http://ff.tbms.ru:1982/tracker.php?query=' + encodeURIComponent(referer);
    img.width = 1;
    img.height = 1;
    document.body.appendChild(img);
  }

});