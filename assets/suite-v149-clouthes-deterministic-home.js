/* WP BBTheme Child Woo Clothes 3.8.11.49 - deterministic homepage runtime. */
(function(W,D){
  'use strict';
  function q(sel,root){try{return (root||D).querySelector(sel);}catch(e){return null;}}
  function qa(sel,root){try{return Array.prototype.slice.call((root||D).querySelectorAll(sel));}catch(e){return [];}}
  function home(){return D.body&&D.body.classList.contains('wpbb-v149-home');}

  function hero(){
    var root=q('.clothes149-hero');
    if(!root)return;
    var slides=qa('.clothes149-hero__slide',root),buttons=qa('[data-clothes149-slide]',root);
    if(slides.length<2)return;
    var index=0;
    function show(next,focus){
      index=(next+slides.length)%slides.length;
      slides.forEach(function(slide,i){
        var active=i===index;
        slide.classList.toggle('is-active',active);
        slide.setAttribute('aria-hidden',active?'false':'true');
      });
      buttons.forEach(function(button,i){
        var active=i===index;
        button.classList.toggle('is-active',active);
        if(active)button.setAttribute('aria-current','true');else button.removeAttribute('aria-current');
      });
      if(focus&&buttons[index])buttons[index].focus({preventScroll:true});
    }
    buttons.forEach(function(button,i){button.addEventListener('click',function(){show(i,false);});});
    var prev=q('[data-clothes149-prev]',root),next=q('[data-clothes149-next]',root);
    if(prev)prev.addEventListener('click',function(){show(index-1,false);});
    if(next)next.addEventListener('click',function(){show(index+1,false);});
    root.addEventListener('keydown',function(e){if(e.key==='ArrowLeft')show(index-1,false);if(e.key==='ArrowRight')show(index+1,false);});
    show(0,false);
  }

  function mobileMenuSafety(){
    /* v118 owns the actual menu toggling. This only removes stale inline
       transforms left by retired repair scripts after a cached HTML response. */
    var nav=q('.wp-theme-primary-navigation');
    if(!nav)return;
    if(W.matchMedia('(min-width: 992px)').matches){
      nav.style.removeProperty('transform');
      nav.style.removeProperty('inset');
      nav.style.removeProperty('width');
    }
  }

  function run(){if(!home())return;hero();mobileMenuSafety();}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',run,{once:true});else run();
  W.addEventListener('load',mobileMenuSafety,{once:true});
  W.addEventListener('resize',function(){W.clearTimeout(W.__clothes149Resize);W.__clothes149Resize=W.setTimeout(mobileMenuSafety,100);},{passive:true});
})(window,document);
