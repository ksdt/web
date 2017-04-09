/*
$()
$.ajax({
  url: "http://ksdt.ucsd.edu:8000/status.xsl",
  error: function() {
     $('#info').html('<p>An error has occurred</p>');
  },
  dataType: 'html',
  success: function(data) {
    var $title = $('<h1>').text("boyyyyyy");
    var $description = $('<p>').text("get got get adsfadsf");
    $('.exampleb')
   .append($title)
   .append($description);
  },
  type: 'GET'
});

*/

// This file is for parsing current song name for displaying on index
