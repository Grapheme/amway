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
  
  $('.rating').each(function(index, value){
    var $rating = $(this);
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
  var dh = $(document).height();
  var st = $(document).scrollTop();
  $('.popup-wrapper').height(dh);
  $('.popup-wrapper').addClass('active');
  $('.popup').removeClass('active');
  $('.popup').css({
    top: st
  });
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

function sendForm(form){
  var $form = $(form);
  var $btn = $form.find('button[type="submit"]');
  $.ajax({
    type: $form.attr('method') || 'POST',
    url: $form.attr('action'),
    data: $form.serialize(),
    complete: function(data){
      $btn.removeAttr('disabled');
    },
    success: function(data){
      console.log(data);
      if (data.status == true) {
        alert('Нет финального состояние формы');
      } else {
        alert('status==', data.status);
      }
    },
    error: function(data){
      console.log(data);
      alert('Ошибка');
    }
  });
}

function parseHash(){
  var hash = window.location.hash;
  if (hash != '' && hash.charAt(0)=='#') {
    hash = hash.split("#")[1];
    if (hash.split('=').length > 1) {
      var key = hash.split('=')[0];
      var val = hash.split('=')[1];
      if (key=='popup') showPopup(val);
    }
  }
}

$(function() {
  parseHash();
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
  
  $('.popup-wrapper').click(function(e){
    if( e.target != this ) return;
    closePopups();
  });
  
  $('body').on('click', '#reg .social-plus', function(e){
    e.preventDefault();
    addSocial($(this));
  });
  
  $('#enter form').validate({
    rules: {
      email: {
        required: true,
        email: true
      },
      password: {
        required: true
      },
    },
    //minlength: 6
    submitHandler: function(form) {
      //form.submit();
      sendForm(form);
    }
  })
  
  $('#reg form').validate({
    rules: {
      email: {
        required: true,
        email: true
      },
      name: {
        required: true
      },
      location: {
        required: true
      },
      age: {
        required: true
      },
      phone: {
        required: true
      },
      agree1: {
        required: true
      },
      agree2: {
        required: true
      },
    },
    //minlength: 6
    submitHandler: function(form) {
      //form.submit();
      sendForm(form);
    }
  })
  $('#reg form input[name="phone"]').mask("+7(999) 999-9999");
  
});
