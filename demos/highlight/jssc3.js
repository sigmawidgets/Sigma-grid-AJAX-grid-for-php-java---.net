jssc={iIdCount:0,oSyntaxList:{},sPath:"",bMultiLine:false,iMultiLineIndex:0,oTempLi:null,aFoldList:[],aFoldDepth:[],sTab:"&nbsp;&nbsp;&nbsp;&nbsp;",regLine:/\r\n?|\n/g,regSign:/\W/g,regXml:/<(:?(:?[\/?]?[a-zA-Z]+[^<]*?\/?>)|(:?!--.*(:?-->|$)))/g,regRegExp:/^\/.*?[^\\][^(:?\[.*\/(:?g|m|i|gm|gi|mi|gmi)?\s*.*\])]*\/(:?g|m|i|gm|gi|mi|gmi)?\s*\W/,regNum:/^((:?0(:?(:?(:?x|X)[\da-fA-F]+)|(:?[01234567]+)))|(:?\d+[lLfFdD]?))$/,regDecimal:/^\.\d+[fFdD]?/,regCssNum:/\d+(:?ex|em|pt|px|pc|in|mm|cm|%|deg|grad|rad|s|ms|Hz|kHz)?/,sCTypeKeyWords:"if else for break case continue function true false switch default do while int float double long short char return void static null",colorAll:function(_,$){
    if($){
      this.sPath=encodeURI($);
    }
    this.colorList(this.getTagList("pre",_));
    this.colorList(this.getTagList("textarea",_));
  },getTagList:function(A,$){
    var B=[],C=document.getElementsByTagName(A);
    for(var _=0;_<C.length;_++){
      if(C[_].name==$||C[_].getAttribute("name")==$){
        B.push(C[_]);
      }
    }
    return B;
  },colorList:function(C){
    for(var E in C){
      var A=C[E].textContent||C[E].value||C[E].firstChild.nodeValue,F=this.splitLine(A),G=C[E].className,B=this.getSyntax(G),_=this.colorCode(A,F,B,G),$=document.createElement("div"),D=document.createElement("div");
      C[E].id="jssc"+this.iIdCount++;
      if(B&&B.title){
        D.innerHTML=B.title+" Code";
      }else {
        D.innerHTML=G+" Code";
      }
      D.className="jssccodetitle";
      $.appendChild(_);
      $.className="jssc";
      C[E].parentNode.insertBefore(D,C[E]);
      C[E].parentNode.insertBefore($,C[E]);
      C[E].style.display="none";
    }
  },splitLine:function(A){
    var _=0,$,B=[];
    while($=this.regLine.exec(A)){
      B.push({begin:_,end:$.index});
      _=$.index+$.length;
    }
    if(_<A.length){
      B.push({begin:_,end:A.length});
    }
    return B;
  },getSyntax:function(A){
    for(var $ in this.oSyntaxList){
      var B=this.oSyntaxList[$].aliases;
      if(!B){
        continue ;
      }
      for(var _ in B){
        if(A.toLowerCase()==B[_]){
          return this.oSyntaxList[$];
        }
      }
    }
    return null;
  },colorCode:function(E,_,F,G){
    var I=1,B=document.createDocumentFragment(),C=document.createElement("ul"),$=(_.length.toString().length-1)*9+30;
    C.style.marginLeft=$+"px";
    for(var H in _){
      this.iCounter++;
      var A=document.createElement("li");
      if(F&&F.collapse){
        this.oTempLi=A;
      }
      A.innerHTML="<div onmouseover=this.style.backgroundColor=\"#ff9\" onmouseout=this.style.backgroundColor=\"transparent\">"+this.parseLine(E.slice(_[H].begin,_[H].end),F,G)+"</div>";
      for(H in this.aFoldDepth){
        this.aFoldDepth[H]++;
      }
      if(F&&F.collapse){
        var D=document.createElement("img");
        D.src=this.sPath+"highlight/jssc_none.gif";
        A.firstChild.insertBefore(D,A.firstChild.firstChild);
      }
      if(I==1){
        A.className="alt";
      }
      I*=-1;
      C.appendChild(A);
    }
    if(F&&F.collapse&&F.collapse.type=="indent"){
      A=C.firstChild;
      while(A.nextSibling){
        if(parseInt(A.getAttribute("name"))<parseInt(A.nextSibling.getAttribute("name"))){
          A.firstChild.firstChild.src=this.sPath+"highlight/jssc_shrink.gif";
        }
        A=A.nextSibling;
      }
    }
    return B.appendChild(C);
  },parseLine:function($,A,_){
    $=$.replace(/[\r\n]/,"");
    if($==""){
      return "&nbsp;";
    }
    if(this.isXml(_)){
      return this.parseXml($,A);
    }else {
      return this.parseOther($,A);
    }
  },parseXml:function(G,B){
    if(!B){
      return this.encodeStr(G);
    }
    var _,A=0,H=[],E=B.aliases[0],F=B.regLib,$=B.keyWords,C=B.collapse;
    if(this.bMultiLine){
      A=this.getEndIndex(G,0,F[this.iMultiLineIndex].end,F[this.iMultiLineIndex].escape);
      var D=E+F[this.iMultiLineIndex].css;
      if(A>-1){
        var I=A+F[this.iMultiLineIndex].end.length;
        H.push(this.colorStr(this.encodeStr(G.slice(0,I)),D));
        iLastIndex=I;
        this.bMultiLine=false;
        this.reduceDepth();
      }else {
        return this.colorStr(this.encodeStr(G),D);
      }
    }
    if(A>0){
      G=G.slice(A);
    }
    while(_=this.regXml.exec(G)){
      if(_.index>A){
        H.push(this.encodeStr(G.slice(A,_.index)));
      }
      if(_[0].charAt(1)=="/"){
        this.reduceDepth();
      }else {
        if(_[0].charAt(_[0].length-2)!="/"){
          this.addDepth();
        }
      }
      H.push(this.parseOther(_[0],B));
      A=_.index+_[0].length;
    }
    return H.join("");
  },parseOther:function($,A){
    var B,_=[];
    while(B=this.regSign.exec($)){
      _.push(B.index);
    }
    if(_[_.length-1]!=$.length-1){
      _.push($.length);
    }
    return this.parseCode($,A,_);
  },parseCode:function(F,D,L){
    if(!D){
      return this.encodeStr(F);
    }
    var $=[],N=0,_=L.length,J=D.aliases[0],M=D.regLib,G=D.keyWords,B=D.collapse;
    if(B&&B.type&&(B.type=="indent")){
      for(var E=0;E<F.length;E++){
        if(F.charAt(E)!=" "&&F.charAt(E)!="\t"){
          this.oTempLi.setAttribute("name",E);
          this.oTempLi.onclick=this.foldPython;
          break ;
        }
      }
    }
    if(this.bMultiLine){
      var I=this.getEndIndex(F,0,M[this.iMultiLineIndex].end,M[this.iMultiLineIndex].escape),H=J+M[this.iMultiLineIndex].css;
      if(I>-1){
        var A=I+M[this.iMultiLineIndex].end.length;
        $.push(this.colorStr(this.encodeStr(F.slice(0,A)),H));
        N=A;
        this.bMultiLine=false;
      }else {
        return this.colorStr(this.encodeStr(F),H);
      }
    }
    for(E=0;E<_;E++){
      if(L[E]<N){
        continue ;
      }
      if(L[E]>N){
        $.push(this.parseStr(F.slice(N,L[E]),J,G,B));
      }
      var K=-1;
      if((K=this.getRegLibIndex(F.substr(L[E],4),M))>-1){
        H=J+M[K].css;
        if(M[K].reg){
          var C;
          if((C=F.slice(L[E]).match(M[K].reg))!=null){
            $.push(this.colorStr(this.encodeStr(C[0]),H));
            N=L[E]+C[0].length;
            continue ;
          }
        }else {
          I=this.getEndIndex(F,L[E]+1,M[K].end,M[K].escape);
          if(I>-1){
            A=I+M[K].end.length;
            $.push(this.colorStr(this.encodeStr(F.slice(L[E],A)),H));
            N=A;
            continue ;
          }else {
            $.push(this.colorStr(this.encodeStr(F.slice(L[E])),H));
            this.iMultiLineIndex=K;
            this.bMultiLine=true;
            break ;
          }
        }
      }
      $.push(this.encodeStr(F.charAt(L[E])));
      N=L[E]+1;
      if(B&&B.type&&B.type.indexOf("sign")>-1){
        if(F.charAt(L[E])==B.start){
          this.addDepth();
        }else {
          if(F.charAt(L[E])==B.end){
            this.reduceDepth();
          }
        }
      }
    }
    return $.join("");
  },getRegLibIndex:function($,A){
    for(var _ in A){
      if($.indexOf(A[_].index)==0){
        return _;
      }
    }
    return -1;
  },getEndIndex:function(A,$,C,B){
    var _;
    while(1){
      if((_=A.indexOf(C,$))>-1){
        if(this.isEscape(A,$,_,B)){
          $=_+1;
          continue ;
        }else {
          return _;
        }
      }else {
        return -1;
      }
    }
  },isEscape:function($,A,B,C){
    if(!C){
      return false;
    }
    var _=0;
    while(B>A){
      if($.charAt(--B)=="\\"){
        _++;
      }else {
        break ;
      }
    }
    return _%2!=0;
  },colorStr:function(_,$){
    return "<span class=\""+$+"\">"+_+"</span>";
  },parseStr:function($,A,B,_){
    if(_&&_.type&&_.type.indexOf("keywords")>-1){
      if(_.bFold!=null){
        if(_.start.indexOf(" "+$+" ")>-1){
          if(_.bFold){
            this.addDepth();
          }else {
            _.bFold=true;
          }
          return this.colorStr($,A+"keywords");
        }else {
          if(_.end.indexOf(" "+$+" ")>-1){
            this.reduceDepth();
            _.bFold=false;
            return this.colorStr($,A+"keywords");
          }
        }
      }else {
        if(_.start.indexOf(" "+$+" ")>-1){
          this.addDepth();
          return this.colorStr($,A+"keywords");
        }else {
          if(_.end.indexOf(" "+$+" ")>-1){
            this.reduceDepth();
            return this.colorStr($,A+"keywords");
          }
        }
      }
    }
    if(this.isKeyWord($,A,B)){
      return this.colorStr($,A+"keywords");
    }else {
      if(this.isNum($,A)){
        return this.colorStr($,A+"num");
      }else {
        if(this.isXml(A)){
          return this.colorStr($,A+"variables");
        }else {
          return this.encodeStr($);
        }
      }
    }
  },isKeyWord:function(_,$,A){
    if(this.isXml($)||this.isSql($)||this.isCss($)){
      _=_.toUpperCase();
    }
    return (A.indexOf(" "+_+" ")>-1);
  },isNum:function(_,$){
    if($=="css"){
      return this.regCssNum.test(_);
    }else {
      return this.regNum.test(_);
    }
  },isXml:function($){
    return /(xml|x?html|xslt)/i.test($);
  },isSql:function($){
    return /sql/i.test($);
  },isCss:function($){
    return /css/i.test($);
  },encodeStr:function($){
    return $.replace(/&/g,"&amp;").replace(/ /g,"&nbsp;").replace(/\t/g,this.sTab).replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\"/g,"&quot;");
  },addDepth:function(){
    this.aFoldList.push(this.oTempLi);
    this.aFoldDepth.push(0);
  },reduceDepth:function(){
    var _=this.aFoldList.pop(),$=this.aFoldDepth.pop();
    if($>1){
      _.setAttribute("name",$);
      _.firstChild.firstChild.src=this.sPath+"highlight/jssc_shrink.gif";
      _.onclick=this.fold;
    }
  },fold:function($){
    var A=parseInt(this.getAttribute("name")),_=(this.nextSibling.style.position=="absolute");
    if(_){
      jssc.setTargetAttr(this,A,"static","inherit","highlight/jssc_shrink.gif");
    }else {
      jssc.setTargetAttr(this,A,"absolute","hidden","highlight/jssc_extend.gif");
    }
  },setTargetAttr:function(D,$,E,A,C){
    D.firstChild.firstChild.src=this.sPath+C;
    var _=D.nextSibling;
    for(var B=1;B<$;B++){
      _.style.position=this.sPath+E;
      _.style.visibility=A;
      var F=_.firstChild.firstChild;
      if(F.src.slice(F.src.lastIndexOf("/"))=="/jssc_extend.gif"){
        F.src=this.sPath+"highlight/jssc_shrink.gif";
      }
      _=_.nextSibling;
    }
  },foldPython:function(A){
    var _=this.firstChild.firstChild.src;
    if(_.slice(_.lastIndexOf("/"))=="/jssc_none.gif"){
      return ;
    }
    var $=parseInt(this.getAttribute("name")),B=(this.nextSibling.style.position=="absolute");
    if(B){
      jssc.setTargetAttrPython(this,$,"static","inherit","highlight/jssc_shrink.gif");
    }else {
      jssc.setTargetAttrPython(this,$,"absolute","hidden","highlight/jssc_extend.gif");
    }
  },setTargetAttrPython:function(C,D,B,_,A){
    C.firstChild.firstChild.src=this.sPath+A;
    var $=C.nextSibling;
    while($&&(parseInt($.getAttribute("name"))>D)){
      $.style.position=this.sPath+B;
      $.style.visibility=_;
      var E=$.firstChild.firstChild;
      if(E.src.slice(E.src.lastIndexOf("/"))=="/jssc_extend.gif"){
        E.src=this.sPath+"highlight/jssc_shrink.gif";
      }
      $=$.nextSibling;
    }
  },copyToClipboard:function(A){
    var $=document.getElementById(A).firstChild.nodeValue;
    if(window.clipboardData){
      window.clipboardData.setData("text",$);
    }else {
      var _="jsscflashcopier",C;
      if(C=document.getElementById(_)){
        document.body.removeChild(C);
      }
      var B=document.createElement("div");
      B.id=_;
      B.innerHTML="<embed src=\""+this.sPath+"flashcopier.swf\" FlashVars=\"copyString="+encodeURI($)+"\" width=\"0\" height=\"0\" type=\"application/x-shockwave-flash\"></embed>";
      document.body.appendChild(B);
    }
    alert("Code has been copied.");
  }};
jssc.oSyntaxList.css={title:"CSS",aliases:["css"],keyWords:" ASCENT AZIMUTH ATTACHMENT REPEAT BACKGROUND BASELINE BBOX "+"COLLAPSE BORDER CAP CAPTION SIDE CENTERLINE CLEAR CLIP CONTENT "+"INCREMENT COUNTER RESET CUE CURSOR DEFINITION DESCENT DIRECTION "+"DISPLAY ELEVATION EMPTY CELLS FLOAT ADJUST STRETCH VARIANT WEIGHT "+"FONT LETTER LINE IMAGE TYPE LIST MARGIN MARKER OFFSET MARKS "+"MATHLINE MAX MIN ORPHANS COLOR STYLE OUTLINE OVERFLOW TOP RIGHT "+"BOTTOM LEFT PADDING PAGE BREAK INSIDE AFTERPAUSE BEFORE PITCH "+"PLAY DURING POSITION QUOTES RICHNESS SIZE SLOPE SRC HEADER "+"NUMERAL PUNCTUATION SPEAK SPEECH RATE STEMH STEMV STRESS TABLE "+"LAYOUT DECORATION INDENT SHADOW TEXT TRANSFORM BIDI UNICODE RANGE "+"UNITS PER EM VERTICAL ALIGN VISIBILITY VOICE FAMILY VOLUME WHITE "+"SPACE WIDOWS WIDTH WIDTHS WORD SPACING X HEIGHT Z INDEX ABOVE "+"ABSOLUTE ALL ALWAYS AQUA ARMENIAN ATTR AVOID BASELINE BEHIND BELOW "+"BIDI OVERRIDE BLACK BLINK BLOCK BLUE BOLD BOLDER BOTH BRAILLE "+"CAPITALIZE CENTER CIRCLE CODE COLLAPSE COMPACT CONTINUOUS COUNTER "+"COUNTERS CROP CROSS CROSSHAIR CURSIVE DASHED DECIMAL LEADING ZERO "+"DEFAULT DIGITS DISC DOTTED DOUBLE EMBED EMBOSSED E EXTRA FANTASY "+"FAR FASTER FIXED FORMAT FUCHSIA GRAY GREEN GROOVE HANDHELD HEBREW "+"HELP HIDDEN HIDE HIGHER ICON INLINE INSET INSIDE INVERT ITALIC "+"JUSTIFY LANDSCAPE LARGER LEFT LEFTWARDS LEVEL LIGHTER LIME LINE "+"THROUGH LIST ITEM LOCAL LOWERCASE GREEK LOWER LTR MARKER MAROON "+"MEDIUM MESSAGE BOX MIDDLEMIX MOVE NARROWER NAVY NE CLOSE NONE NO "+"NORMAL NOWRAP N NW OBLIQUEOLIVE ONCE OPEN QUOTE OUTSET OUTSIDE "+"OVERLINE POINTER PORTRAIT PRE PRINT PROJECTION PURPLE RED RELATIVE "+"REPEAT Y RGB RIDGE RIGHT SIDE RIGHTWARDS RTL RUN IN SCREEN SCROLL "+"SEMI SEPARATE SE SHOW SILENT SILVER SLOWER CAPS SMALLER SOLID "+"SPEECH SPELL OUT SQUARE S STATIC STATUS BAR SUB SUPER SW CAPTION "+"CELL COLUMN FOOTER HEADER TABLE ROW GROUP TEAL BOTTOM TEXT THICK "+"THIN TOP TRANSPARENT TTY TV CONDENSED ULTRA EXPANDED UNDERLINE "+"ALPHA UPPERCASE LATIN UPPER ROMAN URL VISIBLE WAIT WHITEWIDER W XX "+"RESIZE FAST HIGH LOUD LOW SLOW X SOFT LARGE SMALL YELLOW AURAL AUTO ",regLib:[{index:"#",reg:new RegExp("^\\#[a-zA-Z0-9]{3,6}"),css:"num"},{index:"//",reg:new RegExp("^//.*$"),css:"comment"},{index:"/*",end:"*/",css:"comment",multiLine:true,escape:false},{index:"\"",end:"\"",css:"string",multiLine:true,escape:true},{index:"'",end:"'",css:"char",multiLine:true,escape:true},{index:".",reg:new RegExp("^\\.\\d+(:?ex|em|pt|px|pc|in|mm|cm|%|deg|grad|rad|s|ms|Hz|kHz)?"),css:"num"},{index:"@",reg:new RegExp("^\\@.*$"),css:"string"},{index:"!",reg:new RegExp("^\\![a-zA-Z]+"),css:"string"}],collapse:{type:"sign",start:"{",end:"}"}};
jssc.oSyntaxList.html={title:"HTML",aliases:["html","xhtml"],keyWords:" !DOCTYPE A ABBR ACRONYM ADDRESS APPLET AREA B BASE BASEFONT "+"BDO BIG BLOCKQUOTE BODY BR BUTTON CAPTION CENTER CITE CODE COL "+"COLGROUP DD DEL DFN DIR DIV DL DT EM FIELDEST FONT FORM FRAME "+"FRAMESET H1 H2 H3 H4 H5 H6 HEAD HR HTML I IFRAME IMG INPUT INS "+"ISINDEX KBD LABEL LEGEND LI LINK MAP MENU META NOFRAMES NOSCRIPT "+"OBJECT OL OPTGROUP OPTION P PARAM PRE Q S SAMP SCRIPT SELECT SMALL "+"SPAN STRIKE STRONG STYLE SUB SUP TABLE TBODY TD TEXTAREA TFOOT TH "+"THEAD TITLE TR TT U UL VAR ",regLib:[{index:"<!--",end:"-->",css:"comment",multiLine:true,escape:false},{index:"<",reg:new RegExp("<"),css:"sign"},{index:">",reg:new RegExp(">"),css:"sign"},{index:"/",reg:new RegExp("\\/"),css:"sign"},{index:"?",reg:new RegExp("\\?"),css:"sign"},{index:"\"",end:"\"",css:"string",multiLine:true,escape:true},{index:"'",end:"'",css:"char",multiLine:true,escape:true}],collapse:{}};
jssc.oSyntaxList.javascript={title:"JavaScript",aliases:["javascript","js","jscript"],keyWords:" abstract boolean byte class const debugger delete "+"enum export extends final finally goto implements import "+"in instanceof interface native new package private protected "+"public super synchronized this throw throws transient try "+"typeof var volatile with document window "+jssc.sCTypeKeyWords,regLib:[{index:"//",reg:new RegExp("^//.*$"),css:"comment"},{index:"/*",end:"*/",css:"comment",multiLine:true,escape:false},{index:"\"",end:"\"",css:"string",multiLine:true,escape:true},{index:"'",end:"'",css:"char",multiLine:true,escape:true},{index:".",reg:jssc.regDecimal,css:"num"},{index:"/",reg:jssc.regRegExp,css:"reg"}],collapse:{type:"sign",start:"{",end:"}"}};
jssc.oSyntaxList.xml={title:"XML",aliases:["xml","xslt"],keyWords:" XML ",regLib:[{index:"<!--",end:"-->",css:"comment",multiLine:true,escape:false},{index:"<",reg:new RegExp("<"),css:"sign"},{index:">",reg:new RegExp(">"),css:"sign"},{index:"/",reg:new RegExp("\\/"),css:"sign"},{index:"?",reg:new RegExp("\\?"),css:"sign"},{index:"\"",end:"\"",css:"string",multiLine:true,escape:true},{index:"'",end:"'",css:"char",multiLine:true,escape:true}],collapse:{}};