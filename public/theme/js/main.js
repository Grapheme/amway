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
        if (data.redirect == false) {
          alert('Нет финального состояние формы');
        } else {
          location.href = data.redirect;
        }
      } else {
        $form.prepend('<label class="error">'+data.responseText+'</label>');
        //alert('status==', data.status);
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

function readFile(input, callback) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      callback(e.target.result)
        //$('#blah').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}

function cropImage(data){
  $('#photo-edit .holder > img').attr('src',data);
  showPopup('photo-edit');
  $('#photo-edit .holder > img').cropper('destroy');
  $('#photo-edit .holder > img').cropper({
    aspectRatio: 1 / 1,
    mouseWheelZoom: false,
    minCropBoxWidth: 200,
    minCropBoxHeight: 200
  });
}

function playVideoIframe($btn){
  var $section = $btn.closest('section, .video-holder');
  var $iframe = $section.find('iframe');
  
  var src = $iframe.attr('data-src');
  $iframe.attr('src', src).addClass('active');
}

$(function() {
  parseHash();
  renderVoting();
  
  $('section.video .play, .video-holder a.video-preview').click(function(e){
    e.preventDefault();
    playVideoIframe($(this));
  });
  
  $('.photoupload').change(function(){
    readFile(this, function(data){
      console.log(data);
      cropImage(data);
    });
  });
  
  $('.photo-edit-final').click(function(e){
    e.preventDefault();
    console.log($('#photo-edit .holder > img').cropper('getCroppedCanvas'));
    var data = $('#photo-edit .holder > img').cropper('getCroppedCanvas').toDataURL();
    $('body.profile .photo img').attr('src', data);
    $('form.edit-profile input[name="photo"]').val(data);
    closePopups();
  });
  
  $('a.edit-photo').click(function(e){
    e.preventDefault();
    $('input.photoupload').click();
  })
  
  $('.add-video').click(function(e){
    e.preventDefault();
    $('.videoupload').click();
  });
  
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
  
  $('.spoiler').click(function(e){
    e.preventDefault();
    $(this).nextAll('.spoiler-body').eq(0).slideToggle();
  })
  
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
  $('form.edit-profile').validate({
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
      password: {
        minlength: 6,
        required: true
      },
      password_again: {
        minlength: 6,
        required: true,
        equalTo: "#password"
      }
    },
    //minlength: 6
    submitHandler: function(form) {
      form.submit();
      $(form).find('button[type="submit"]').attr('disabled', true);
      //sendForm(form);
    }
  })
  
  $('#reg form input[name="phone"], form.edit-profile input[name="phone"]').mask("+7(999) 999-9999");
  
  $('.videoupload').fileupload({
    dataType: 'json',
    done: function (e, data) {
      //alert('Готово!');
      location.href=location.href;
      setTimeout(function(){
        window.location.href='';
        alert('2')
      }, 500)
    },
    fail: function (e, data) {
      alert('Ошибка');
      console.log(e, data);
    },
    progressall: function (e, data) {
      var progress = parseInt(data.loaded / data.total * 100, 10);
      console.log(progress);
      $('.add-video').closest('.row').next('.row').find('.progress-bar').css(
          'width',
          progress + '%'
      ).text(progress + '%');
    },
    add: function(e, data) {
      var uploadErrors = [];
      console.log(e, data)
      if(data.originalFiles[0]['size'] && data.originalFiles[0]['size'] > 50000000) {
        uploadErrors.push('Фаил слишком большой! Максимум 50мб.');
      }
      if(uploadErrors.length > 0) {
        alert(uploadErrors.join("\n"));
      } else {
        $('.add-video').closest('.row').slideUp().next('.row').slideDown();
        data.submit();
      }
    },
  });
  
  $('.bxslider').bxSlider({
    pagerCustom: '#bx-pager',
    infiniteLoop: false,
    hideControlOnEnd: true,
    
  });
  
});
