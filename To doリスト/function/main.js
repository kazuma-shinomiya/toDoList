(function(){  
  'use strict';  
  //[POINT]idを取得する  
  var open = document.getElementById('open');  
  var close = document.getElementById('close');  
  var mask = document.getElementById('mask');  
  var modal = document.getElementById('modal');  
  //[POINT]クリックイベントを定義  
  open.addEventListener('click',function(){    
    //openではマスクとモーダル画面が表示できるようにする    
    //[POINT]クラス名を変えてCSSを当て込まないようにする    
    mask.className = '';    
    modal.className = '';  });  
  close.addEventListener('click',function(){    
    //closeではマスクとモーダル画面を非表示にする    
    //[POINT]クラス名を再定義し、CSSを当て込む    
    mask.className = 'hidden';    modal.className = 'hidden';  });  
    //モーダル画面外をクリックしてもモーダル画面が閉じるようにする  
    mask.addEventListener('click',function(){    
      //closeと同じなので、closeのクリックイベントを呼び出せば良い    
      close.click(); //このように書くことができる  
    });})();
    