<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<footer>
    <div class="holder">
        <div class="left">
            @if (Request::is('/'))
            <div class="logo"><img src="{{ asset(Config::get('site.theme_path')) }}/img/logo2.png" alt=""></div>
            @else
            <a href="{{ URL::route('mainpage') }}" class="logo"><img src="{{ asset(Config::get('site.theme_path')) }}/img/logo2.png" alt=""></a>
            @endif
            <div class="copy">
                © 2015, Amway, A-Gen, 2015<? if (date('Y') > 2015) { echo '-' . date('Y'); } ?><br>
                Все права защищены.
            </div>
            <a class="mail" href="mailto:info@agen-project.ru">info@agen-project.ru</a>
        </div>
        <div class="right">
          
          <div class="socials">
            <!-- <a href="https://vk.com/agenproject" target="_blank" class="vk">
                <?='<?xml version="1.0" encoding="utf-8"?>'?>
                <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   viewBox="231.4 13.5 18.4 24" enable-background="new 231.4 13.5 18.4 24" xml:space="preserve">
                <g>
                  <path d="M242.6,27.5c-0.8-0.5-1.9-0.5-2.7-0.5H237v6h2.7c1,0,2.2,0.1,3.1-0.5c0.8-0.5,1.2-1.6,1.2-2.5
                    C244,29,243.4,28,242.6,27.5z M241.5,22.4c0.6-0.5,1-1.4,1-2.2c0-0.9-0.4-1.7-1.2-2.2c-0.8-0.5-2.2-0.3-3.1-0.3H237V23h1.7
                    C239.6,23,240.7,23,241.5,22.4z M241.3,37.5h-9.9v-24h10.9c3.1,0,6.1,2,6.1,5.5c0,2.7-1.5,4.6-3.5,5.2v0.1c2.9,0.6,4.9,2.2,4.9,5.9
                    C249.8,33.7,247.4,37.5,241.3,37.5z"/>
                </g>
                </svg>
              </a>
              <a target="_blank" href="https://www.facebook.com/agenproject" class="fb">
                <?='<?xml version="1.0" encoding="utf-8"?>'?>
                <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   viewBox="-149.4 12.9 13 24" enable-background="new -149.4 12.9 13 24" xml:space="preserve">
                <g>
                  <path d="M-136.9,12.9l-3.1,0c-3.5,0-5.8,2.3-5.8,5.9v2.7h-3.1c-0.3,0-0.5,0.2-0.5,0.5V26c0,0.3,0.2,0.5,0.5,0.5h3.1
                    v10c0,0.3,0.2,0.5,0.5,0.5h4.1c0.3,0,0.5-0.2,0.5-0.5v-10h3.7c0.3,0,0.5-0.2,0.5-0.5l0-3.9c0-0.1-0.1-0.3-0.1-0.3
                    c-0.1-0.1-0.2-0.1-0.3-0.1h-3.7v-2.3c0-1.1,0.3-1.7,1.7-1.7l2.1,0c0.3,0,0.5-0.2,0.5-0.5v-3.7C-136.5,13.2-136.7,12.9-136.9,12.9z"
                    />
                </g>
                </svg>
              </a> -->
              <script type="text/javascript">(function() {
                if (window.pluso)if (typeof window.pluso.start == "function") return;
                if (window.ifpluso==undefined) { window.ifpluso = 1;
                  var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                  s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
                  s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
                  var h=d[g]('body')[0];
                  h.appendChild(s);
                }})();</script>
              <div class="pluso" data-background="transparent" data-options="small,round,line,horizontal,nocounter,theme=04" data-services="vkontakte,odnoklassniki,facebook"></div>
          </div>
          <a href="http://myxa-digital.com/" target="_blank" class="dev"><small>MYXA Digital</small></a>
        </div>
    </div>
</footer>
