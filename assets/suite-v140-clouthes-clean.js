/* Woo Clothes 3.8.11.40 - minimal final runtime: finder de-dupe + mega positioning only. */
(function(W,D){
  'use strict';
  var H=D.documentElement;
  function qa(sel,root){try{return Array.prototype.slice.call((root||D).querySelectorAll(sel));}catch(e){return[];}}
  function desktop(){return W.matchMedia('(min-width: 992px)').matches;}
  function mark(){if(D.body)D.body.classList.add('wpbb-v140');}

  function cleanFinders(){
    if(!D.body||!(D.body.classList.contains('home')||D.body.classList.contains('front-page')||D.body.classList.contains('wpbb-v140-home')))return;
    var finders=qa('#wp-theme-main .wpbb-v97-hero-finder');
    finders.forEach(function(f,i){
      if(i===0){f.classList.add('wpbb-v140-hero-finder');return;}
      if(f.parentNode)f.parentNode.removeChild(f);
    });
  }

  function menuTrigger(menu){
    var li=menu&&menu.parentElement;if(!li)return null;
    try{return li.querySelector(':scope > a, :scope > button, :scope > .wp-theme-nav-link')||li;}
    catch(e){return li.querySelector('a,button')||li;}
  }
  function positionMega(menu){
    if(!menu)return;
    if(!desktop()){menu.style.removeProperty('top');return;}
    var trigger=menuTrigger(menu);if(!trigger||!trigger.getBoundingClientRect)return;
    var r=trigger.getBoundingClientRect(),li=menu.parentElement,lr=li&&li.getBoundingClientRect?li.getBoundingClientRect():r;
    var bottom=Math.ceil(Math.max(r.bottom,lr.bottom)-6);
    if(bottom>0){menu.style.setProperty('top',bottom+'px','important');H.style.setProperty('--wpbb-v140-mega-top',bottom+'px');}
  }
  function bindMegas(){
    qa('.wp-theme-primary-menu>li>.wp-theme-mega-menu').forEach(function(menu){
      if(!menu.dataset.wpbbV140Bound){
        menu.dataset.wpbbV140Bound='1';
        var li=menu.parentElement;
        if(li){li.addEventListener('pointerenter',function(){positionMega(menu);},{passive:true});li.addEventListener('focusin',function(){positionMega(menu);});}
      }
      positionMega(menu);
    });
  }
  function run(){mark();cleanFinders();bindMegas();}
  var t=0;function schedule(){W.clearTimeout(t);t=W.setTimeout(run,40);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',schedule,{once:true});else schedule();
  W.addEventListener('load',function(){run();W.setTimeout(run,300);});
  W.addEventListener('resize',function(){W.clearTimeout(t);t=W.setTimeout(bindMegas,90);},{passive:true});
})(window,document);
