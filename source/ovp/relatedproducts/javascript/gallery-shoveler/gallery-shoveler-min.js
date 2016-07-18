YUI.add("gallery-shoveler",function(A){(function(){function J(T){J.superclass.constructor.apply(this,arguments);}var R="shoveler",D=A.Lang,K=1,F=0,O=0,L=1,P="BeforeAddCell",S="AfterAddCell",M="BeforeRemoveCell",H="AfterRemoveCell",B="BeforeReplaceCell",G="AfterReplaceCell",C="AFTERCREATECELL",I="StartScroll",E="EndScroll",N="RenderFinished",Q="AfterDataRetrieval";J.NAME=R;J.ATTRS={numberOfCells:{value:0,validator:D.isNumber},dynamic:{value:false,validator:D.isBoolean},infinite:{value:false,validator:D.isBoolean},numberOfVisibleCells:{value:1,validator:D.isNumber},cyclical:{value:false,validator:function(U,T){if((this.get("infinite")||(this.get("infinite")===undefined))&&U){return false;}return true;}},loadingCell:{value:"<div class='yui-shoveler-loading'></div>"},button:{value:"button",validator:D.isString},buttonOn:{value:"on",validator:D.isString},buttonDisabled:{value:"disabled",validator:D.isString},prefetch:{value:false,validator:D.isBoolean},delay:{value:35,validator:D.isNumber},page:{value:0,validator:D.isNumber,setter:function(U,T){if(this.get("cyclical")){return(U%this.get("numberOfPages")+this.get("numberOfPages"))%this.get("numberOfPages");}else{if(this.get("infinite")){return Math.max(U,0);}else{return Math.max(Math.min(this.get("numberOfPages")-1,U),0);}}}},numberOfPages:{value:0,validator:D.isNumber},firstVisibleCell:{value:0,validator:D.isNumber},lastVisibleCell:{value:0,validator:D.isNumber,setter:function(U,T){return Math.min(U,this.get("numberOfCells")-1);}},cells:{value:[],validator:D.isArray},cellClass:{value:"cell",validator:D.isString},pageTextClass:{value:"page-text",validator:D.isString},ulClass:{value:"cells",validator:D.isString},strings:{value:{NumberOfPagesPrefix:"Page",NumberOfPagesConnection:"of",NumberOfPagesStart:"start over"}},renderFunctionName:{value:"renderCells"},listOfLoadingCells:{value:{}},stylesheet:{value:undefined},contructDataSrc:{value:function(U,T){},validator:D.isFunction},handleData:{value:function(T){},validator:D.isFunction},leftButton:{value:undefined},rightButton:{value:undefined}};A.extend(J,A.Widget,{initializer:function(T){this.renderFunction=this[this.get("renderFunctionName")];this.publish(P);this.publish(S);this.publish(M);this.publish(H);this.publish(B);this.publish(G);this.publish(C);this.publish(I);this.publish(E);this.publish(N);this.publish(Q);},destructor:function(){},renderUI:function(){this.set("stylesheet",A.StyleSheet());},bindUI:function(){this.after("pageChange",A.bind(this.onPageChange,this));this.after("cellsChange",A.bind(this.onCellsChange,this));this.after("numberOfVisibleCellsChange",A.bind(this.onNumberOfCellsChange,this));this.after("numberOfVisibleCellsChange",A.bind(this.adjustCellWidth,this));this.after("numberOfVisibleCellsChange",A.bind(this.onNumberOfVisibleCellsChange,this));this.after("numberOfCellsChange",A.bind(this.calculateNumberOfPages,this));this.after("numberOfPagesChange",A.bind(this.onNumberOfPagesChange,this));this.get("contentBox").delegate("click",A.bind(this.startOver,this),"."+this.getClassName("start"));var U=this.get("contentBox").one("."+this.getClassName(this.get("button"),"left")),T=this.get("contentBox").one("."+this.getClassName(this.get("button"),"right"));this.set("leftButton",U);this.set("rightButton",T);U.on("click",A.bind(this.scrollBackwards,this));T.on("click",A.bind(this.scrollForward,this));U.on("mousedown",A.bind(this.mouseDownOnButton,this));T.on("mousedown",A.bind(this.mouseDownOnButton,this));U.on("mouseup",A.bind(this.mouseUpOnButton,this));T.on("mouseup",A.bind(this.mouseUpOnButton,this));},syncUI:function(){var V,U,T=[];this.adjustCellWidth();this.calculateNumberOfPages();V=this.get("contentBox").one("ul."+this.getClassName(this.get("ulClass")));this.set("ul",V);U=V.all("li."+this.getClassName(this.get("cellClass")));U.each(function(X,W,Y){T.push(X.cloneNode(true));});this.clearCells();this.set("cells",T);this.initRender();},initRender:function(){this.setPageText();this.setFirstAndLast();this.renderCells(this.getVisibleCells());this.disableButtons();},setFirstAndLast:function(){this.set("firstVisibleCell",this.get("page")*this.get("numberOfVisibleCells"));this.set("lastVisibleCell",this.get("firstVisibleCell")+this.get("numberOfVisibleCells")-1);},onPageChange:function(){this.setPageText();this.disableButtons();},disableButtons:function(){if(!this.get("cylical")){if(this.get("page")===0){this.get("leftButton").addClass(this.getClassName("disabled"));this.get("rightButton").removeClass(this.getClassName("disabled"));}else{if(this.get("page")==this.get("numberOfPages")-1){this.get("leftButton").removeClass(this.getClassName("disabled"));this.get("rightButton").addClass(this.getClassName("disabled"));}else{this.get("leftButton").removeClass(this.getClassName("disabled"));this.get("rightButton").removeClass(this.getClassName("disabled"));}}}},setPageText:function(){var U=this.get("contentBox").one("."+this.getClassName(this.get("pageTextClass"))),T;if(this.get("numberOfPages")>0){T=this.get("strings").NumberOfPagesPrefix+" "+(this.get("page")+1);if(!this.get("infinite")){T+=" "+this.get("strings").NumberOfPagesConnection+" "+this.get("numberOfPages");}if(this.get("page")>0){T+=" (<a class='"+this.getClassName("start")+"'>"+this.get("strings").NumberOfPagesStart+"</a>)";}}if(U&&T){U.setContent(T);}},mouseDownOnButton:function(T){T.target.addClass(this.getClassName(this.get("button"),this.get("buttonOn")));},mouseUpOnButton:function(T){T.target.removeClass(this.getClassName(this.get("button"),this.get("buttonOn")));},addCell:function(U,T){this.fire(P,{content:U});var V=this.insertIntoCells(U,T,F);this.fire(S,{cell:V});},addCells:function(U){for(var V=0,T=U.length;V<T;V++){this.addCell(U[V]);}},adjustCellWidth:function(){var T=90/this.get("numberOfVisibleCells");this.get("stylesheet").set("#"+this.get("contentBox").getAttribute("id")+" li."+this.getClassName(this.get("cellClass")),{"width":T+"%"});},createCell:function(T){if(T===undefined){T="";}var U=A.Node.create("<li>"+T+"</li>");
U.addClass(this.getClassName(this.get("cellClass")));this.fire(C,{cell:U});return U;},createLoadingCell:function(){return this.createCell(this.get("loadingCell"));},insertIntoCells:function(W,U,V){var X=this.createCell(W),T=this.get("cells");if(V===undefined){V=0;}if(U===undefined){U=T.length;}if(this.get("dynamic")){while(U>T.length){T.push(undefined);}}T.splice(U,V,X);this.set("cells",T);if(this.get("dynamic")){this.checkIfLoading(X,U);}return X;},checkIfLoading:function(T,V){var U=this.get("listOfLoadingCells");if(U[V]){this.get("ul").replaceChild(T,U[V]);delete U[V];}},removeCell:function(V){var U,T;this.fire(M,{index:V});U=this.get("cells");T=U.splice(V,1);this.set("cells",U);this.fire(H,{cell:T});},replaceCell:function(U,T){this.fire(B,{content:U});var V=this.insertIntoCells(U,T,K);this.fire(G,{cell:V});},replaceCells:function(V){for(var U=0,T=V.length;U<T;U++){this.replaceCell(V[U].content,V[U].index);}},onCellsChange:function(){if(!this.get("dynamic")){this.set("numberOfCells",this.get("cells").length);}else{this.set("numberOfCells",Math.max(this.get("cells").length,this.get("numberOfCells")));}if(this.get("page")==this.get("numberOfPages")-1&&!this.get("dynamic")){this.setFirstAndLast();this.renderCells(this.getVisibleCells());}},calculateNumberOfPages:function(){this.set("numberOfPages",Math.ceil(this.get("numberOfCells")/this.get("numberOfVisibleCells")));},getPageForIndex:function(T){return Math.floor(T/this.get("numberOfVisibleCells"));},onNumberOfVisibleCellsChange:function(T){this.set("lastVisibleCell",this.get("firstVisibleCell")+T.newVal);this.renderCells(this.getVisibleCells());},onNumberOfPagesChange:function(){this.setPageText();},removeAllCells:function(){this.clearCells();this.set("cells",[]);},clearCells:function(){this.get("ul").all("li."+this.getClassName(this.get("cellClass"))).remove();},getCell:function(T){return this.get("cells")[T];},getAllCells:function(){return this.get("cells");},getFirstVisibleCell:function(){return this.get("cells")[this.get("firstVisibleCell")];},getVisibleCells:function(){var V=this.get("cells").slice(this.get("firstVisibleCell"),this.get("lastVisibleCell")+1),W,Y=false,X,U,T=this.get("listOfLoadingCells");for(W=0;W<this.get("numberOfVisibleCells")&&(this.get("infinite")||this.get("firstVisibleCell")+W<this.get("numberOfCells"));W++){if(V[W]===undefined){X=this.createLoadingCell();V[W]=X;T[this.get("firstVisibleCell")+W]=X;Y=true;}}for(W=V.length;W<this.get("numberOfVisibleCells");W++){U=this.createCell();V.push(U);T[this.get("firstVisibleCell")+W]=U;}this.set("listOfLoadingCells",T);if(Y){this.fetchNextCells(this.get("firstVisibleCell"));}else{this.fire(Q);}return V;},scrollForward:function(){this.set("direction",O);this.scrollTo(this.get("page")+1);},scrollBackwards:function(){this.set("direction",L);this.scrollTo(this.get("page")-1);},startOver:function(T){this.set("direction",undefined);this.scrollTo(0);T.preventDefault();},scrollTo:function(T){this.fire(I);if(this.get("prefetch")){var U=this.on(Q,A.bind(this.prefetchCells,this));this.set("retrievalHandle",U);}this.set("page",T);this.setFirstAndLast();this.renderFunction(this.getVisibleCells());this.fire(E);},renderCells:function(T){var V=this.get("ul"),U;this.clearCells();for(U=0;U<this.get("numberOfVisibleCells");U++){V.append(T[U]);}this.fire(N);},renderCellsWithPop:function(X){var Z=this.get("ul"),U=this.popIndexTransformation(),Y,T,V,W,a;for(Y=0,T=X.length;Y<T;Y++){if(Z.contains(X[Y])){Z.replaceChild(X[Y].cloneNode(true),X[Y]);}}V=Z.all("li."+this.getClassName(this.get("cellClass")));for(Y=0;Y<this.get("numberOfVisibleCells");Y++){a=X[Y];W=undefined;if(Y<V.size()){W=V.item(Y);}window.setTimeout(this.popTimeoutFunction(Z,a,W),this.get("delay")*U(Y));}window.setTimeout(A.bind(function(){this.fire(N);},this),this.get("delay")*Y);},popTimeoutFunction:function(U,V,T){return function(){if(T!==undefined){U.replaceChild(V,T);}else{U.append(V);}};},popIndexTransformation:function(){if(this.get("direction")===undefined){return function(U){return 0;};}else{if(this.get("direction")==L){return function(U){return U;};}else{var T=this.get("numberOfVisibleCells");return function(U){return T-U;};}}},fetchNextCells:function(V){var U,T;this.set("fetchStart",V);U=this.get("contructDataSrc")(V,this.get("numberOfVisibleCells"),this.get("fetchCallback"));T=A.Node.create("<script type='text/javascript' src='"+U+"'><\/script>");A.get("body").append(T);},prefetchCells:function(){this.get("retrievalHandle").detach(Q);var T;if(this.get("direction")==L){T=this.get("firstVisibleCell")-this.get("numberOfVisibleCells");}else{T=this.get("lastVisibleCell")+1;}if(this.get("cells")[T]===undefined&&T<this.get("numberOfCells")){this.fetchNextCells(T);}},handleDataRetrieval:function(T){this.get("handleData").call(this,T);this.fire(Q);}});A.Shoveler=J;}());},"@VERSION@",{requires:["widget","event","event-delegate","stylesheet"]});