function jsTils(num, expressions) {
  var result;
  count = num % 100;
  if (count >= 5 && count <= 20) {
      result = expressions['2'];
  } else {
      count = count % 10;
      if (count == 1) {
          result = expressions['0'];
      } else if (count >= 2 && count <= 4) {
          result = expressions['1'];
      } else {
          result = expressions['2'];
      }
  }
  return result;
}

function renderVoting(){
  $('.competitors .unit').each(function(index, value){
    var $rating = $(this).find('.rating');
    var $count = $rating.find('.count');
    var $legend = $rating.find('.legend');
    var expressions = ['голос', 'голоса', 'голосов'];
    
    var num = $count.text();
    $legend.text(jsTils(num, expressions));
  });
}

function votePlus($vote_btn){
  var $unit = $vote_btn.closest('.unit');
  var $count = $unit.find('.rating .count');
  $count.text(parseInt($count.text())+1);
  
  renderVoting();
}

function showPopup(id){
  $('.popup-wrapper').addClass('active');
  $('.popup').removeClass('active');
  $('.popup#'+id).addClass('active');
}

function closePopups(){
  $('.popup-wrapper').removeClass('active');
  $('.popup').removeClass('active');
}

function addSocial($btn){
  var $this = $btn.closest('label');
  var $clone = $this.clone();
  $this.after($clone);
  $this.find('.social-plus').remove();
}

$(function() {
  renderVoting();
  
  $('.competitors a.vote').click(function(e){
    e.preventDefault();
    
    votePlus($(this));
  });
  
  $('a.btn-popup').click(function(e){
    e.preventDefault();
    var id = $(this).attr('data-href');
    showPopup(id);
  });
  
  $('.popup-wrapper .popup .close').click(function(e){
    e.preventDefault();
    closePopups();
  });
  
  $('body').on('click', '#reg .social-plus', function(e){
    e.preventDefault();
    addSocial($(this));
  });
  
});
